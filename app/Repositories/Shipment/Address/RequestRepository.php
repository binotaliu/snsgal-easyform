<?php

namespace App\Repositories\Shipment\Address;

use App\Eloquent\Shipment\Address\Request;
use App\Eloquent\User\RequestProfile;
use Ramsey\Uuid\Uuid;

class RequestRepository
{
    const PER_PAGE = 15;

    /**
     * @var Request
     */
    protected $request;

    /**
     * RequestRepository constructor.
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * Get all requests
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function getRequests()
    {
        return Request::with(['cvs_address', 'standard_address'])->orderBy('id', 'desc')->get();
    }

    /**
     * Get specified request
     * @param String $token
     * @param bool $archived
     * @return Request
     */
    public function getRequest(String $token, bool $archived = false)
    {
        return Request::where('token', $token)->archived($archived)->first();
    }

    /**
     * Create new request
     * @param String $title
     * @param String $description
     * @param String $type
     * @return Request
     */
    public function createRequest(String $title, String $description, String $type)
    {
        Switch ($type) {
            case 'cvs':
            case 'standard':
                break;
            default:
                return abort(500, 'Address Type Error');
                break;
        }

        return Request::create([
            'title' => $title,
            'description' => $description,
            'address_type' => $type,
            'token' => Uuid::uuid1(),
            'responded' => false
        ]);
    }

    /**
     * Update request
     * @param String $token
     * @param String $title
     * @param String $description
     * @param Int $ecpayId
     * @return Request
     */
    public function updateRequest(String $token, String $title, String $description, Int $ecpayId)
    {
        Request::where('token', $token)->update([
            'title' => $title,
            'description' => $description,
            'exported' => $ecpayId
        ]);
    }

    /**
     * Remove specified request
     * @param String $token
     */
    public function removeRequest(String $token)
    {
        Request::where('token', $token)->delete();
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
     * @param Int $user
     * @param array $data
     */
    public function updateProfile(Int $user, Array $data)
    {
        $updateData = [];
        if ($data['name']) $updateData['name'] = $data['name'];
        if ($data['phone']) $updateData['phone'] = $data['phone'];
        if ($data['postcode']) $updateData['postcode'] = $data['postcode'];
        if ($data['address']) $updateData['address'] = $data['address'];

        // check whether the profile already exists
        if (RequestProfile::where('user_id', $user)->exists()) {
            RequestProfile::where('user_id', $user)->update($updateData);
        } else {
            $updateData['user_id'] = $user;
            RequestProfile::create($updateData);
        }
    }
}
