<?php

namespace App\Http\Controllers\Shipment\Address;

use App\Codes\Shipment\EcpayShipmentStatus;
use App\Exceptions\Shipment\Address\InvalidTypeException;
use App\Http\Controllers\Controller;
use App\Repositories\Shipment\AddressRepository;
use App\Repositories\Shipment\Address\RequestRepository;
use App\Services\ECPay\ECPayLogisticsService;
use Auth;
use Binota\ECPay\Logistics\Enum\Collection;
use Binota\ECPay\Logistics\Enum\Distance;
use Binota\ECPay\Logistics\Enum\Specification;
use Binota\ECPay\Logistics\Enum\Temperature;
use Binota\ECPay\Logistics\Enum\Time;
use Binota\ECPay\Logistics\Enum\Vendor;
use ECPay;
use Illuminate\Http\Request;

class RequestController extends Controller
{
    /**
     * @var RequestRepository
     */
    protected $requestRepository;

    /**
     * @var AddressRepository
     */
    protected $addressRepository;

    /**
     * @var ECPayLogisticsService
     */
    protected $ecpayLogisticsService;

    public function __construct(RequestRepository $requestRepository, AddressRepository $addressRepository, ECPayLogisticsService $ecpayLogisticsService)
    {
        $this->requestRepository = $requestRepository;
        $this->addressRepository = $addressRepository;
        $this->ecpayLogisticsService = $ecpayLogisticsService;
    }

    /**
     * Show the page for vue.js handle
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function view()
    {
        return view('shipment.requests', [
            'ecpay_codes' => EcpayShipmentStatus::getCodes(),
            'temperature' => Temperature::getConstants(),
            'time' => Time::getConstants(),
            'distance' => Distance::getConstants(),
            'specifications' => Specification::getConstants(),
            'vendors' => Vendor::getConstants(),
            'collections' => Collection::getConstants()
        ]);
    }

    /**
     * List requests
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function index()
    {
        return $this->requestRepository->getRequests();
    }

    /**
     * Store new request
     * @param Request $request
     * @return \App\Eloquent\Shipment\Address\Request
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'title' => 'required|max:255',
            'method' => 'required|in:cvs,standard'
        ]);
        $ticket = $this->requestRepository->createRequest($request->input('title'), $request->input('description'), $request->input('method'));
        return $ticket;
    }

    /**
     * Get the response form request
     * @param String $token
     * @return mixed
     */
    public function get(String $token)
    {
        $request = $this->requestRepository->getRequest($token);
        if (!$request) return abort(404, 'Request Not Found');

        if ($request->responded) {
            return view('shipment.request.response.success', [
                'request' => $request,
                'ecpay_status' => $request->shipment_status ? EcpayShipmentStatus::getCode($request->shipment_status) : ''
            ]);
        }

        switch ($request->address_type) {
            case 'standard':
            case 'cvs':
                return view('shipment.request.response', [
                    'request' => $request
                ]);
                break;
            default:
                return abort(500, 'Unknown Address Type');
                break;
        }
    }

    /**
     * Export the shipping information to ECPay
     * @param String $token
     * @param Request $req
     * @return mixed
     */
    public function export(String $token, Request $req)
    {
        $request = $this->requestRepository->getRequest($token);
        if (!$request) return abort(404, 'Request Not Found');

        $ticket = $this->ecpayLogisticsService->createTicket($request->id . '-' . substr($token, 0, 8), strtotime($request->created_at));

        $ticket = $ticket
            ->setVendor($request->address_type == 'standard' ? $req->input('package')['vendor'] : $request->cvs_address->vendor)
            ->useC2C()
            ->setAmount($req->input('package')['amount'])
            ->setCollection($req->input('package')['collect'] ? $req->input('package')['amount'] : 0)
            ->setServerReplyUrl(url("/shipment/requests/{$token}/notify"))
            ->setC2CReplyUrl(url("/shipment/requests/{$token}/notify"))
            ->setGoods([$req->input('package')['products']])
            ->setRemark($token . "\n" . $request->description)
            ->setSenderName($req->input('sender')['name'])
            ->setSenderCellPhone($req->input('sender')['phone']);


        if ($request->address_type == 'standard') {
            $ticket = $ticket
                ->setReceiverName($request->standard_address->receiver)
                ->setReceiverCellPhone($request->standard_address->phone)
                ->setSenderZipCode($req->input('sender')['postcode'])
                ->setSenderAddress($req->input('sender')['address'])
                ->setReceiverZipCode($request->standard_address->postcode)
                ->setReceiverAddress(
                    $request->standard_address->county . $request->standard_address->city .
                        $request->standard_address->address1 . ' ' . $request->standard_address->address2)
                ->setTemperature($req->input('package')['temperature'])
                ->setDistance($req->input('package')['distance'])
                ->setSpecification($req->input('package')['specification'])
                ->setDeliveryTime($request->standard_address->time)
                ->setPackageCount(1);
        } elseif ($request->address_type == 'cvs') {
            $ticket = $ticket
                ->setReceiverName($request->cvs_address->receiver)
                ->setReceiverCellPhone($request->cvs_address->phone)
                ->setReceiverStore($request->cvs_address->store);
        }

        $ecpayRequest = $ticket->create();
        $ecpayResponse = $ecpayRequest->send();
        $this->requestRepository->updateRequest($token, $request->title, $request->description, $ecpayResponse->data('AllPayLogisticsID'));

        $ecpayStatus = $ecpayResponse->data('RtnCode');
        $ecpayShipmentId = $ecpayResponse->data('CVSPaymentNo');
        if (empty($ecpayShipmentId)) $ecpayShipmentId = $ecpayResponse->data('BookingNote');
        $ecpayShipmentValidation = $ecpayResponse->data('CVSValidationNo');
        $this->requestRepository->updateRequestShipment($token, $ecpayShipmentId, $ecpayShipmentValidation, $ecpayStatus);

        return $ecpayResponse->all();
    }

    /**
     * @param string $token
     * @return array
     */
    public function archive(string $token)
    {
        $this->requestRepository->archiveRequest($token);

        return ['status' => 200];
    }

    /**
     * Handle the notifications from ECPay
     * @param String $token
     * @param Request $req
     * @return mixed
     */
    public function notify(String $token, Request $req)
    {
        $request = $this->requestRepository->getRequest($token);
        if (!$request) return abort(404, 'Request Not Found');

        $ecpayData = [
            'MerchantID' => $req->input('MerchantID'),
            'MerchantTradeNo' => $req->input('MerchantTradeNo'),
            'RtnCode' => $req->input('RtnCode'),
            'RtnMsg' => $req->input('RtnMsg'),
            'AllPayLogisticsID' => $req->input('AllPayLogisticsID'),
            'LogisticsType' => $req->input('LogisticsType'),
            'LogisticsSubType' => $req->input('LogisticsSubType'),
            'GoodsAmount' => $req->input('GoodsAmount'),
            'UpdateStatusDate' => $req->input('UpdateStatusDate'),
            'ReceiverName' => $req->input('ReceiverName'),
            'ReceiverPhone' => $req->input('ReceiverPhone'),
            'ReceiverCellPhone' => $req->input('ReceiverCellPhone'),
            'ReceiverEmail' => $req->input('ReceiverEmail'),
            'ReceiverAddress' => $req->input('ReceiverAddress'),
            'CVSPaymentNo' => $req->input('CVSPaymentNo'),
            'CVSValidationNo' => $req->input('CVSValidationNo'),
            'BookingNote' => $req->input('BookingNote')
        ];

        if (ECPay::GetCheckMacValue($ecpayData) !== $req->input('CheckMacValue')) throw new \Exception('Invalid CheckMacValue');

        $this->requestRepository->updateRequest($token, $request->title, $request->description, $req->input('AllPayLogisticsID'));

        $ecpayStatus = $req->input('RtnCode');
        $ecpayShipmentId = $req->input('CVSPaymentNo');
        if (empty($ecpayShipmentId)) $ecpayShipmentId = $req->input('BookingNote');
        $ecpayShipmentValidation = $req->input('CVSValidationNo');
        $this->requestRepository->updateRequestShipment($token, $ecpayShipmentId, $ecpayShipmentValidation, $ecpayStatus);

        if(Auth::guest()) {
            return '1|OK';
        } else {
            return redirect("shipment/requests#/{$token}");
        }
    }

    public function print(string $token)
    {

        $request = $this->requestRepository->getRequest($token);
        if (!$request) return abort(404, 'Request Not Found');
        if (!$request->exported) return abort(500, 'Request Haven\'t been exported.');

        switch ($request->address_type) {
            case 'cvs':
                return $this->ecpayLogisticsService->printTicket($request->cvs_address->vendor, $request->exported, $request->shipment_ticket_id, $request->shipment_validation);
            case 'standard':
                // No meter the first argument is TCAT or ECAN, just use Vendors/HomeVendor,
                //    the value will not be sent to ECPay, only ECPay id will be sent.
                // 第一個參數不管填 TCAT 或 ECAN 都可以，只要是 Home 類別的就好了，實際不會帶給 ECPay，只要有 ECPayID 就好了
                return $this->ecpayLogisticsService->printTicket(Vendor::TCAT, $request->exported, $request->shipment_ticket_id);
            default:
                throw new InvalidTypeException('Unknown address type: ' . $request->address_type);
        }
    }

    /**
     * Save the address user responded
     * @param String $token
     * @param Request $request
     * @return mixed
     */
    public function addAddress(String $token, Request $request)
    {
        switch ($request->input('address_type')) {
            case 'standard':
                $this->validate($request, [
                    'receiver' => 'required|max:128',
                    'phone' => 'required|regex:/^09\d{8}$/',
                    'postcode' => 'required|numeric|digits:3',
                    'county' => 'required',
                    'city' => 'required',
                    'address1' => 'required',
                    'time' => 'required|min:1|max:15'
                ]);
                $this->addressRepository->createAddress($token, [
                    'receiver' => $request->input('receiver'),
                    'phone' => $request->input('phone'),
                    'postcode' => $request->input('postcode'),
                    'county' => $request->input('county'),
                    'city' => $request->input('city'),
                    'address1' => $request->input('address1'),
                    'address2' => $request->input('address2'),
                    'time' => $request->input('time')
                ]);
                break;
            case 'cvs':
                $this->validate($request, [
                    'receiver' => 'required|max:128',
                    'phone' => 'required|regex:/^09\d{8}$/',
                    'store' => 'required'
                ]);
                $this->addressRepository->createAddress($token, [
                    'receiver' => $request->input('receiver'),
                    'phone' => $request->input('phone'),
                    'vendor' => $request->input('vendor'),
                    'store' => $request->input('store')
                ]);
                break;
            default:
                return abort(500, 'Unknown Address Type');
                break;
        }
        return redirect("/shipment/requests/{$token}");
    }

    /**
     * Show CVS map selector
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function cvsmap()
    {
        return view('shipment.map.select');
    }

    /**
     * Return CVS selected for parent page
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function cvsmapResponse(Request $request)
    {
        return view('shipment.map.response', [
            'vendor' => $request->input('LogisticsSubType'),
            'store' => $request->input('CVSStoreID'),
            'name' => $request->input('CVSStoreName')
        ]);
    }
}
