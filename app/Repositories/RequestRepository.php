<?php

namespace App\Repositories;

use App\Eloquent\Address\Request;
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
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function pagination()
    {
        return Request::paginate(self::PER_PAGE);
    }

    /**
     * @return int
     */
    public function count()
    {
        return Request::all()->count();
    }

    /**
     * @param Int $page
     * @return \Illuminate\Database\Eloquent\Collection|\Illuminate\Support\Collection|static[]
     */
    public function getPage(Int $page)
    {
        return Request::forPage($page, self::PER_PAGE)->get();
    }

    /**
     * @param String $token
     * @return Request
     */
    public function getRequest(String $token)
    {
        return Request::where('token', $token)->first();
    }

    /**
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
     * @param String $token
     * @param String $title
     * @param String $description
     * @return Request
     */
    public function updateRequest(String $token, String $title, String $description)
    {
        Request::where('token', $token)->update([
            'title' => $title,
            'description' => $description
        ]);
    }

    /**
     * @param String $token
     */
    public function removeRequest(String $token)
    {
        Request::where('token', $token)->delete();
    }
}
