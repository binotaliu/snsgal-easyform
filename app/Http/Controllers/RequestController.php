<?php

namespace App\Http\Controllers;

use App\Repositories\AddressRepository;
use App\Repositories\RequestRepository;
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
}
