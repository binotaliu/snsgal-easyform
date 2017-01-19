<?php

namespace App\Repositories\Shipment\Address;

use App\Eloquent\Shipment\Address\Request;
use App\Eloquent\User\RequestProfile;
use App\Exceptions\Shipment\Address\InvalidTypeException;
use Ramsey\Uuid\Uuid;

class RequestRepository
{
    const PER_PAGE = 15;

    /**
     * @var Request
     */
    protected $request;

    /**
     * @var RequestProfile
     */
    protected $requestProfile;

    /**
     * RequestRepository constructor.
     * @param Request $request
     * @param RequestProfile $requestProfile
     */
    public function __construct(Request $request, RequestProfile $requestProfile)
    {
        $this->request = $request;
        $this->requestProfile = $requestProfile;
    }

    /**
     * Get all requests
     * @param bool $archived
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function getRequests(bool $archived = false)
    {
        return $this->request->with(['cvs_address', 'standard_address'])
            ->archived($archived)
            ->orderBy('id', 'desc')->get();
    }

    /**
     * Get specified request
     * @param string $token
     * @param bool $archived
     * @return Request
     */
    public function getRequest(string $token, bool $archived = false)
    {
        return $this->request->where('token', $token)->archived($archived)->first();
    }

    /**
     * Create new request
     * @param string $title
     * @param string $description
     * @param string $type
     * @return Request
     * @throws InvalidTypeException
     */
    public function createRequest(string $title, string $description, string $type)
    {
        if (!in_array($type, ['cvs', 'standard'])) {
            throw new InvalidTypeException();
        }

        return $this->request->create([
            'title' => $title,
            'description' => $description,
            'address_type' => $type,
            'token' => Uuid::uuid1(),
            'responded' => false
        ]);
    }

    /**
     * Update request
     * @param string $token
     * @param string $title
     * @param string $description
     * @param int $ecpayId
     * @return bool
     */
    public function updateRequest(string $token, string $title, string $description, int $ecpayId)
    {
        return $this->request->where('token', $token)->update([
            'title' => $title,
            'description' => $description,
            'exported' => $ecpayId
        ]);
    }

    /**
     * Remove specified request
     * @param string $token
     */
    public function removeRequest(string $token)
    {
        $this->request->where('token', $token)->delete();
    }

    /**
     * @param string $token
     * @return bool
     */
    public function archiveRequest(string $token): bool
    {
        return $this->request->where('token', $token)->update([
            'archived' => true
        ]);
    }

    /**
     * Update user's profile
     * @param int $user
     * @param array $data
     */
    public function updateProfile(int $user, array $data)
    {
        $updateData = [];
        if ($data['name']) $updateData['name'] = $data['name'];
        if ($data['phone']) $updateData['phone'] = $data['phone'];
        if ($data['postcode']) $updateData['postcode'] = $data['postcode'];
        if ($data['address']) $updateData['address'] = $data['address'];

        // check whether the profile already exists
        if ($this->requestProfile->where('user_id', $user)->exists()) {
            $this->requestProfile->where('user_id', $user)->update($updateData);
        } else {
            $updateData['user_id'] = $user;
            $this->requestProfile->create($updateData);
        }
    }
}
