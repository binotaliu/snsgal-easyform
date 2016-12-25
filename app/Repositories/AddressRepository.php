<?php

namespace App\Repositories;

use App\Eloquent\Address\Cvs as CvsAddress;
use App\Eloquent\Address\Request;
use App\Eloquent\Address\Standard as StandardAddress;

class AddressRepository
{
    /**
     * @var Request
     */
    protected $request;

    /**
     * @var StandardAddress
     */
    protected $standardAddress;

    /**
     * @var CvsAddress
     */
    protected $cvsAddress;

    /**
     * AddressRepository constructor.
     * @param Request $request
     * @param StandardAddress $standardAddress
     * @param CvsAddress $cvsAddress
     */
    public function __construct(Request $request, StandardAddress $standardAddress, CvsAddress $cvsAddress)
    {
        $this->request = $request;
        $this->standardAddress = $standardAddress;
        $this->cvsAddress = $cvsAddress;
    }

    /**
     * @param String $token
     * @return StandardAddress|CvsAddress
     */
    public function getAddress(String $token)
    {
        $request = Request::where('token', $token)
            ->where('responded', true)->first();
        if (is_null($request)) abort(500, 'Address Request Not Found');

        return $request->address;
    }

    /**
     * @param String $token
     * @param array $data
     * @return StandardAddress|CvsAddress|null
     */
    public function createAddress(String $token, Array $data)
    {
        $request = Request::where('token', $token)
            ->where('responded', false)->first();
        if (is_null($request)) abort(500, 'Address Request Not Found');

        $data['request_id'] = $request->id;

        $retval = null;
        switch ($request->address_type) {
            case 'cvs':
                $retval = CvsAddress::create($data);
                break;
            case 'standard':
                $retval = StandardAddress::create($data);
                break;
        }

        $request->responded = true;
        $request->save();

        return $retval;
    }

    /**
     * @param Int $id
     * @param array $data
     */
    public function updateAddress(Int $id, Array $data)
    {
        $request = Request::where('id', $id)
            ->where('responded', true)->first();
        if (is_null($request)) abort(500, 'Address Request Not Found');

        $request->address->update($data);
        return;
    }

    /**
     * @param Int $id
     */
    public function removeAddress(Int $id)
    {
        $request = Request::where('id', $id)
            ->where('responded', true)->first();
        if (is_null($request)) abort(500, 'Address Request Not Found');

        $request->address->delete();
        $request->responded = false;
        $request->save();
        return;
    }
}
