<?php

namespace App\Http\Controllers\Shipment;

use App\Codes\Shipment\EcpayShipmentStatus;
use App\Ecpay\Api\Request as EcpayRequest;
use App\Exceptions\Shipment\Address\InvalidTypeException;
use App\Http\Controllers\Controller;
use App\Models\AddressTicket;
use App\Models\Shipment\CvsAddress;
use App\Models\Shipment\StandardAddress;
use Auth;
use App\Ecpay\Enum\Logistics\Collection;
use App\Ecpay\Enum\Logistics\Distance;
use App\Ecpay\Enum\Logistics\Specification;
use App\Ecpay\Enum\Logistics\Temperature;
use App\Ecpay\Enum\Logistics\Time;
use App\Ecpay\Enum\Logistics\Vendor;
use Carbon\Carbon;
use ElfSundae\Laravel\Hashid\Facades\Hashid;
use Illuminate\Http\Request;

class AddressTicketsController extends Controller
{
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
     * @return \Illuminate\Database\Eloquent\Collection|\App\Models\AddressTicket[]
     */
    public function index()
    {
        return \App\Models\AddressTicket
            ::latest()
            ->where('archived', false)
            ->get();
    }

    /**
     * Store new request
     * @param Request $request
     * @return null|\App\Models\AddressTicket
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'title' => 'required|max:255',
            'method' => 'required|in:cvs,standard'
        ]);

        $ticket = new \App\Models\AddressTicket;
        $ticket->title = $request->input('title');
        $ticket->description = $request->input('description', $ticket->title);
        $ticket->address_type = $request->input('method');
        $ticket->responded = false;

        return tap($ticket)->save();
    }

    public function batch(Request $request)
    {
        $this->validate($request, [
            'data' => 'required',
            'method' => 'required|in:cvs,standard'
        ]);

        $titles = explode("\n", $request->input('data'));
        $method = $request->input('method');
        $tickets = [];

        foreach ($titles as $title) {
            $title = trim($title);
            if (empty($title)) continue;

            $ticket = new \App\Models\AddressTicket;
            $ticket->title = $title;
            $ticket->description = $title;
            $ticket->address_type = $method;
            $ticket->responded = false;

            $tickets[] = tap($ticket)->save();
        }

        return $tickets;
    }

    /**
     * Get the response form request
     * @param String $token
     * @return mixed
     */
    public function get(string $token)
    {
        $ticket = \App\Models\AddressTicket::where('token', $token)->firstOrFail();

        if ($ticket->responded) {
            return view('shipment.request.response.success', [
                'request' => $ticket,
                'ecpay_status' => $ticket->shipment_status ? EcpayShipmentStatus::getCode($ticket->shipment_status) : ''
            ]);
        }

        switch ($ticket->address_type) {
            case 'standard':
            case 'cvs':
                return view('shipment.request.response', [
                    'request' => $ticket
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
     * @param Request $request
     * @return mixed
     */
    public function export(string $token, Request $request)
    {
        $ticket = \App\Models\AddressTicket::where('token', $token)->firstOrFail();

        /** @var EcpayRequest $ecpayReq */
        $ecpayReq = app()->make(EcpayRequest::class);
        $ecpayReq->setData('MerchantTradeNo', 'SNS' . Hashid::encode($ticket->id));
        $ecpayReq->setData('MerchantTradeDate', $ticket->created_at->format('Y-m-d H:i:s'));

        $ecpayReq->setData('LogisticsType', $ticket->address_type === 'standard' ? 'Home' : 'CVS');
        $ecpayReq->setData('LogisticsSubType', $ticket->address_type == 'standard' ? $request->input('package.vendor') : $ticket->address['vendor']);
        $ecpayReq->setData('GoodsAmount', $request->input('package.amount'));
        $ecpayReq->setData('CollectionAmount', $request->input('package.collect') === 'Y' ? $request->input('package.amount') : 0);
        $ecpayReq->setData('IsCollection', $request->input('package.collect') === 'Y' ? 'Y' : 'N');
        $ecpayReq->setData('GoodsName', $request->input('package.products'));
        $ecpayReq->setData('SenderName', $request->input('sender.name'));
        $ecpayReq->setData('SenderCellPhone', $request->input('sender.phone'));
        $ecpayReq->setData('ServerReplyURL', url("/shipment/requests/{$token}/notify"));
        $ecpayReq->setData('LogisticsC2CReplyURL', url("/shipment/requests/{$token}/notify"));
        $ecpayReq->setData('Remark', "{$token}\n{$ticket->description}");


        if ($ticket->address_type == 'standard') {
            $ecpayReq->setData('ReceiverName', $ticket->receiver_name);
            $ecpayReq->setData('ReceiverCellphone', $ticket->receiver_phone);

            $ecpayReq->setData('SenderZipCode', $request->input('sender.postcode'));
            $ecpayReq->setData('SenderAddress', $request->input('sender.address'));
            $ecpayReq->setData('ReceiverZipCode', $ticket->address['postcode']);
            $ecpayReq->setData(
                'ReceiverAddress',
                $ticket->address['county'] . $ticket->address['city'] . $ticket->address['address_1'] . ' ' . $ticket->address['address_2']
            );
            $ecpayReq->setData('Temperature', $request->input('package.temperature'));
            $ecpayReq->setData('Distance', $request->input('package.distance'));
            $ecpayReq->setData('Specification', $request->input('package.specification'));
            $ecpayReq->setData('ScheduledDeliveryTime', $ticket->address['delivery_time']);
            $ecpayReq->setData('PackageCount', 1);
        } elseif ($ticket->address_type == 'cvs') {
            $ecpayReq->setData('ReceiverName', $ticket->receiver_name);
            $ecpayReq->setData('ReceiverCellphone', $ticket->receiver_phone);
            $ecpayReq->setData('ReceiverStoreID', $ticket->address['store']);
        }

        $ecpayResponse = $ecpayReq->send('logistics', '/Express/Create');

        if ($ecpayResponse->getCode() !== '1') {
            return response(['code' => $ecpayResponse->getCode(), 'message' => $ecpayResponse->getErrorMessage()], 500);
        }

        if (!$ecpayResponse->isValid()) {
            return response(['code' => '', 'message' => 'Invalid CheckMacValue'], 500);
        }

        $ticket->exported = $ecpayResponse->getData('AllPayLogisticsID');
        $ticket->shipment_ticket_id = $ecpayResponse->getData('CVSPaymentNo') ?: $ecpayResponse->getData('BookingNote');
        $ticket->shipment_validation = $ecpayResponse->getData('CVSValidationNo');
        $ticket->shipment_status = $ecpayResponse->getData('RtnCode');

        $ticket->save();

        return $ecpayResponse->getData();
    }

    /**
     * @param string $token
     * @return array
     */
    public function archive(string $token)
    {
        $ticket = \App\Models\AddressTicket
            ::where('token', $token)
            ->firstOrFail();

        $ticket->archived = true;
        $ticket->save();

        return ['status' => 200];
    }

    /**
     * Handle the notifications from ECPay
     * @param String $token
     * @param Request $request
     * @return mixed
     * @throws \Exception
     */
    public function notify(String $token, Request $request)
    {
        $ticket = \App\Models\AddressTicket
            ::where('token', $token)
            ->firstOrFail();

        $ecpayData = [
            'MerchantID' => $request->input('MerchantID'),
            'MerchantTradeNo' => $request->input('MerchantTradeNo'),
            'RtnCode' => $request->input('RtnCode'),
            'RtnMsg' => $request->input('RtnMsg'),
            'AllPayLogisticsID' => $request->input('AllPayLogisticsID'),
            'LogisticsType' => $request->input('LogisticsType'),
            'LogisticsSubType' => $request->input('LogisticsSubType'),
            'GoodsAmount' => $request->input('GoodsAmount'),
            'UpdateStatusDate' => $request->input('UpdateStatusDate'),
            'ReceiverName' => $request->input('ReceiverName'),
            'ReceiverPhone' => $request->input('ReceiverPhone'),
            'ReceiverCellPhone' => $request->input('ReceiverCellPhone'),
            'ReceiverEmail' => $request->input('ReceiverEmail'),
            'ReceiverAddress' => $request->input('ReceiverAddress'),
            'CVSPaymentNo' => $request->input('CVSPaymentNo'),
            'CVSValidationNo' => $request->input('CVSValidationNo'),
            'BookingNote' => $request->input('BookingNote')
        ];

        $ecpayCredential = app()->make(\App\Ecpay\Api\Credential::class);
        throw_if(
            \App\Ecpay\Utils::getCheckMacValue($ecpayCredential, $ecpayData) !== $request->input('CheckMacValue'),
            new \Exception('Invalid CheckMacValue')
        );


        $ticket->exported = $request->input('AllPayLogisticsID');
        $ticket->shipment_ticket_id = $request->input('CVSPaymentNo') ?: $request->input('BookingNote');
        $ticket->shipment_validation = $request->input('CVSValidationNo');
        $ticket->shipment_status = $request->input('RtnCode');

        if(Auth::guest()) {
            return '1|OK';
        } else {
            return redirect("shipment/requests#/{$token}");
        }
    }

    public function print(string $token)
    {
        $ticket = \App\Models\AddressTicket
            ::where('token', $token)
            ->firstOrFail();

        if (!$ticket->exported) return abort(500, 'Request Haven\'t been exported.');

        $ecpayRequest = app()->make(EcpayRequest::class);
        $ecpayRequest->setData('AllpayLogisticsID', $ticket->exported);

        switch ($ticket->address_type) {
            case 'cvs':
                $vendor = $ticket->address['vendor'];
                $isC2C = false;
                if (substr($vendor, -3) === 'C2C') {
                    $vendor = substr($vendor, 0, -3);
                    $isC2C = true;
                }

                if ($isC2C) {
                    $ecpayRequest->setData('CVSPaymentNo', $ticket->shipment_ticket_id);

                    if ($vendor === 'UNIMART') {
                        $ecpayRequest->setData('CVSValidationNo', $ticket->shipment_validation);
                    }
                }

                $endpoint = [
                    'UNIMARTC2C' => '/Express/PrintUniMartC2COrderInfo',
                    'FAMIC2C' => '/Express/PrintFAMIC2COrderInfo',
                    'HILIFEC2C' => '/Express/PrintHILIFEC2COrderInfo',
                ];

                return $ecpayRequest->makeAutoForm('logistics', $isC2C ? $endpoint[$ticket->address['vendor']] : '/helper/printTradeDocument');
            case 'standard':
                return $ecpayRequest->makeAutoForm('logistics', '/helper/printTradeDocument');
            default:
                throw new InvalidTypeException('Unknown address type: ' . $ticket->address_type);
        }
    }

    /**
     * Save the address user responded
     * @param String $token
     * @param Request $req
     * @return mixed
     */
    public function addAddress(string $token, Request $req)
    {
        /** @var \App\Models\AddressTicket $ticket */
        $ticket = \App\Models\AddressTicket::where('token', $token)->firstOrFail();

        $ticket->receiver_name = $req->input('receiver');
        $ticket->receiver_phone = $req->input('phone');

        switch ($ticket->address_type) {
            case 'standard':
                $this->validate($req, [
                    'receiver' => 'required|max:128',
                    'phone' => 'required|regex:/^09\d{8}$/',
                    'postcode' => 'required|numeric|digits:3',
                    'county' => 'required',
                    'city' => 'required',
                    'address1' => 'required',
                    'time' => 'required|min:1|max:15'
                ]);

                $ticket->address = [
                    'postcode' => $req->input('postcode'),
                    'county' => $req->input('county'),
                    'city' => $req->input('city'),
                    'address_1' => $req->input('address1'),
                    'address_2' => $req->input('address2'),
                    'delivery_time' => $req->input('time'),
                ];

                break;
            case 'cvs':
                $this->validate($req, [
                    'receiver' => 'required|max:128',
                    'phone' => 'required|regex:/^09\d{8}$/',
                    'store' => 'required'
                ]);

                $ticket->address = [
                    'vendor' => $req->input('vendor'),
                    'store' => $req->input('store'),
                ];

                break;
            default:
                return abort(500, 'Unknown Address Type');
                break;
        }

        $ticket->responded = true;
        $ticket->responded_at = Carbon::now();
        $ticket->saveOrFail();

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
