<?php

namespace App\Repositories\Shipment;

use App\Eloquent\Shipment\Address\AddressTypeInterface;
use App\Eloquent\Shipment\Address\Cvs as CvsAddress;
use App\Eloquent\Shipment\Address\Request;
use App\Eloquent\Shipment\Address\Standard as StandardAddress;
use App\Exceptions\Shipment\Address\InvalidTypeException;
use App\Exceptions\Shipment\Address\RequestNotFoundException;

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
     * Get the address of the request
     * @param string $token
     * @return AddressTypeInterface
     * @throws RequestNotFoundException
     * @throws InvalidTypeException
     */
    public function getAddress(string $token): AddressTypeInterface
    {
        $request = $this->request->where('token', $token)
            ->where('responded', true)->first();
        if (is_null($request)) throw new RequestNotFoundException();

        switch ($request->address_type) {
            case 'cvs':
                return $request->cvs_address;
                break;
            case 'standard':
                return $request->standard_address;
                break;
            default:
                throw new InvalidTypeException();
        }
    }

    /**
     * Create an address for the request
     * @param string $token
     * @param array $data
     * @return AddressTypeInterface
     * @throws RequestNotFoundException
     * @throws InvalidTypeException
     */
    public function createAddress(string $token, array $data): AddressTypeInterface
    {
        $request = $this->request->where('token', $token)
            ->where('responded', false)->first();
        if (is_null($request)) throw new RequestNotFoundException();

        $data['request_id'] = $request->id;

        switch ($request->address_type) {
            case 'cvs':
                $retval = $this->cvsAddress->create($data);
                break;
            case 'standard':
                $retval = $this->standardAddress->create($data);
                break;
            default:
                throw new InvalidTypeException();
                break;
        }

        $request->responded = true;
        $request->save();

        return $retval;
    }

    /**
     * Update an address
     * @param Int $id
     * @param array $data
     * @throws RequestNotFoundException
     * @throws InvalidTypeException
     */
    public function updateAddress(int $id, array $data)
    {
        $request = $this->request->where('id', $id)
            ->where('responded', true)->first();
        if (is_null($request)) throw new RequestNotFoundException();

        switch ($request->address_type) {
            case 'cvs':
                $request->cvs_address->update($data);
                break;
            case 'standard':
                $request->standard_address->update($data);
                break;
            default:
                throw new InvalidTypeException();
                break;
        }
        return;
    }

    /**
     * Remove address
     * @param int $id
     * @throws RequestNotFoundException
     * @throws InvalidTypeException
     */
    public function removeAddress(int $id)
    {
        $request = $this->request->where('id', $id)
            ->where('responded', true)->first();
        if (is_null($request)) throw new RequestNotFoundException();

        switch ($request->address_type) {
            case 'cvs':
                $request->cvs_address->delete();
                break;
            case 'standard':
                $request->standard_address->delete();
                break;
            default:
                throw new InvalidTypeException();
                break;
        }
        $request->responded = false;
        $request->save();
        return;
    }
}
