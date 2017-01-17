<?php

namespace App\Http\Controllers\Shipment;

use App\Repositories\Shipment\Address\RequestRepository;
use Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SenderController extends Controller
{
    /**
     * @var RequestRepository
     */
    protected $requestRepository;

    public function __construct(RequestRepository $requestRepository)
    {
        $this->requestRepository = $requestRepository;
    }

    /**
     * Get user's profile
     * @return \App\Eloquent\User\RequestProfile|array
     */
    public function index()
    {
        $requestProfile = Auth::user()->requestProfile;
        if ($requestProfile) {
            return $requestProfile;
        }
        return [
            'name' => '',
            'phone' => '',
            'postcode' => '',
            'address' => ''
        ];
    }

    /**
     * Save user's profile
     * @param Request $request
     * @return array
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'phone' => 'required|regex:/^09\d{8}$/',
            'postcode' => 'required|numeric|digits:3',
            'address' => 'required'
        ]);
        $this->requestRepository->updateProfile(Auth::user()->id, [
            'name' => $request->input('name'),
            'phone' => $request->input('phone'),
            'postcode' => $request->input('postcode'),
            'address' => $request->input('address')
        ]);
        return ['status' => 'ok'];
    }

}
