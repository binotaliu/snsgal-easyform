<?php

namespace App\Http\Controllers\Shipment;

use App\Eloquent\User\RequestProfile;
use App\Http\Controllers\Controller;
use App\Repositories\AddressRepository;
use App\Repositories\RequestRepository;
use Auth;
use Binota\ECPay\ECPay;
use Binota\ECPay\Response as ECPayResponse;
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

    public function __construct(RequestRepository $requestRepository, AddressRepository $addressRepository)
    {
        $this->requestRepository = $requestRepository;
        $this->addressRepository = $addressRepository;
    }

    public function view()
    {
        return view('shipment.requests');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function index()
    {
        return $this->requestRepository->getRequests();
    }

    /**
     * @param Request $request
     * @return \App\Eloquent\Address\Request
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
     * @param String $token
     * @return mixed
     */
    public function get(String $token)
    {
        $request = $this->requestRepository->getRequest($token);
        if (!$request) return abort(404, 'Request Not Found');

        if ($request->responded) {
            return view('request.response.success');
        }

        switch ($request->address_type) {
            case 'standard':
            case 'cvs':
                return view("request.response.{$request->address_type}", [
                    'request' => $request
                ]);
                break;
            default:
                return abort(500, 'Unknown Address Type');
                break;
        }
    }

    /**
     * @param String $token
     * @param Request $req
     * @return mixed
     */
    public function export(String $token, Request $req)
    {
        $request = $this->requestRepository->getRequest($token);
        if (!$request) return abort(404, 'Request Not Found');


        $ecpay = new ECPay(env('ECPAY_MERCHANTID'), env('ECPAY_HASHKEY'), env('ECPAY_HASHIV'));
        $ticket = $ecpay->getLogisticFactory()
            ->makeTicket($request->id . substr($token, 0, 8), strtotime($request->created_at));

        $ticket = $ticket
            ->amount($req->input('package')['amount'])
            ->collect($req->input('package')['collect'])
            ->replyServer(url("/shipment/request/{$token}/notify"))
            ->replyC2C(url("/shipment/request/{$token}/notify"))
            ->products([$req->input('package')['products']])
            ->remark($token)
            ->vendor($request->address_type == 'standard' ? $req->input('package')['vendor'] : $request->cvs_address->vendor . 'C2C');

        if ($request->address_type == 'standard') {
            $ticket = $ticket
                ->sender(
                    $req->input('sender')['name'],
                    null,
                    $req->input('sender')['phone'],
                    $req->input('sender')['postcode'],
                    $req->input('sender')['address'])
                ->receiver(
                    $request->standard_address->receiver,
                    null,
                    $request->standard_address->phone,
                    $request->standard_address->postcode,
                    $request->standard_address->county . $request->standard_address->city .
                        $request->standard_address->address1 . ' ' . $request->standard_address->address2)
                ->temperature($req->input('package')['temperature'])
                ->distance($req->input('package')['distance'])
                ->specification($req->input('package')['specification']);
        } elseif ($request->address_type == 'cvs') {
            $ticket = $ticket
                ->sender(
                    $req->input('sender')['name'],
                    null,
                    $req->input('sender')['phone'])
                ->receiver(
                    $request->cvs_address->receiver,
                    null,
                    $request->cvs_address->phone,
                    $request->cvs_address->store);
        }

        $ecpayRequest = $ticket->create($request->address_type == 'cvs' ? 'cvs' : 'home');
        $ecpayResponse = $ecpayRequest->send();
        $this->requestRepository->updateRequest($token, $request->title, $request->description, $ecpayResponse->data('AllPayLogisticsID'));
        return $ecpayResponse->all();
    }

    /**
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
        new ECPayResponse(env('ECPAY_MERCHANTID'), env('ECPAY_HASHIV'), env('ECPAY_HASHKEY'), $ecpayData);

        $this->requestRepository->updateRequest($token, $request->title, $request->description, $req->input('AllPayLogisticsID'));

        if(Auth::guest()) {
            return '1|OK';
        } else {
            return redirect("shipment/request#/{$token}");
        }
    }

    /**
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
                    'time' => 'required|in:0,1,2,3'
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
        return view('request.response.success');
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function cvsmap()
    {
        return view('request.map.select');
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function cvsmapResponse(Request $request)
    {
        return view('request.map.response', [
            'vendor' => $request->input('LogisticsSubType'),
            'store' => $request->input('CVSStoreID'),
            'name' => $request->input('CVSStoreName')
        ]);
    }
}
