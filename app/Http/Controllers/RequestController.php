<?php

namespace App\Http\Controllers;

use App\Repositories\AddressRepository;
use App\Repositories\RequestRepository;
use Auth;
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

    /**
     * @return \Illuminate\Contracts\View\Factory
     */
    public function list()
    {
        return view('request.list', [
            'requests' => $this->requestRepository->pagination()
        ]);
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function createForm()
    {
        return view('request.create');
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function create(Request $request)
    {
        $this->validate($request, [
            'title' => 'required|max:255',
            'type' => 'required|in:cvs,standard'
        ]);
        $ticket = $this->requestRepository->createRequest($request->input('title'), $request->input('description'), $request->input('type'));
        return redirect("request/{$ticket->token}/detail");
    }

    /**
     * @param String $token
     * @return mixed
     */
    public function detail(String $token)
    {
        $request = $this->requestRepository->getRequest($token);
        if (!$request) return abort(404, 'Request Not Found');

        return view('request.detail', [
            'request' => $request
        ]);
    }

    /**
     * @param String $token
     * @return mixed
     */
    public function get(String $token)
    {
        $request = $this->requestRepository->getRequest($token);
        if (!$request) return abort(404, 'Request Not Found');

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

        $ecpayData = [];
        $ecpayData['MerchantID'] = env('ECPAY_MERCHANTID');
        $ecpayData['MerchantTradeNo'] = $request->id . substr($token, 0, 8) . rand(100,999);
        $ecpayData['MerchantTradeDate'] = date('Y/m/d H:i:s');
        $ecpayData['LogisticsType'] = $request->address_type == 'cvs' ? 'CVS' : 'Home';
        $ecpayData['LogisticsSubType'] = $request->address_type == 'cvs' ? $request->address->vendor . 'C2C' : $req->input('vendor');
        $ecpayData['GoodsAmount'] = $req->input('amount');
        $ecpayData['IsCollection'] = $req->input('collect');
        if ($req->input('collect') == 'Y') {
            $ecpayData['CollectionAmount'] = $req->input('amount');
        }
        $ecpayData['GoodsName'] = $req->input('product_name');
        $ecpayData['SenderName'] = $req->input('sender');
        $ecpayData['SenderCellPhone'] = $req->input('sender_phone');
        $ecpayData['ReceiverName'] = $request->address->receiver;
        $ecpayData['ReceiverCellPhone'] = $request->address->phone;
        $ecpayData['TradeDesc'] = $token;
        $ecpayData['ServerReplyURL'] = url("/request/{$token}/notify");
        $ecpayData['ClientReplyURL'] = url("/request/{$token}/notify");
        $ecpayData['LogisticsC2CReplyURL'] = url("/request/{$token}/notify");
        $ecpayData['Remark'] = $token;
        $ecpayData['PlatformID'] = '';

        if ($request->address_type == 'standard') {
            $ecpayData['SenderZipCode'] = $req->input('sender_postcode');
            $ecpayData['SenderAddress'] = $req->input('sender_address');
            $ecpayData['ReceiverZipCode'] = $request->address->postcode;
            $ecpayData['ReceiverAddress'] = $request->address->county . $request->address->city . $request->address->address1 . ' ' . $request->address->address2;
            $ecpayData['Temperature'] = $req->input('temperature');
            $ecpayData['Distance'] = $req->input('distance');
            $ecpayData['Specification'] = $req->input('specification');
            $ecpayData['ScheduledDeliveryTime'] = $request->address->time == 0 ? 4 : $request->address->time;
        } elseif ($request->address_type == 'cvs') {
            $ecpayData['ReceiverStoreID'] = $request->address->store;
        }

        $ecpayData['CheckMacValue'] = $this->generateCheckMacValue($ecpayData);

        return view('request.export', [
            'data' => $ecpayData
        ]);
    }

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
        $checkMacValue = $this->generateCheckMacValue($ecpayData);

        //@TODO
        if ($checkMacValue === $req->input('CheckMacValue')) {
            $this->requestRepository->updateRequest($token, $request->title, $request->description, $req->input('AllPayLogisticsID'));
        } else {
            return abort(403, 'Invalid CheckMacValue');
        }

        if(Auth::guest()) {
            return '1|OK';
        } else {
            return redirect("request/{$token}/detail");
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

    /**
     * @param array $data
     * @return string
     */
    private function generateCheckMacValue(Array $data)
    {
        uksort($data, function ($a, $b) { return strcasecmp($a, $b); });

        // buidling http query string
        $checkMacValue = 'HashKey=' . env('ECPAY_HASHKEY');
        // note: DO NOT use http_build_query, since it will do urlencode
        foreach ($data as $key => $value) {
            $checkMacValue .= '&' . $key . '=' . $value;
        }
        $checkMacValue .= '&HashIV=' . env('ECPAY_HASHIV');
        $checkMacValue = strtolower(urlencode($checkMacValue));

        $checkMacValue = str_replace(
            ['%2d', '%5f', '%2e', '%21', '%2a', '%28', '%29'],
            ['-', '-', '.', '!', '*', '(', ')'],
            $checkMacValue
        );

        return strtoupper(md5($checkMacValue));
    }
}
