<?php

namespace App\Http\Controllers\Procurement\Item;

use App\Repositories\Procurement\Item\ExtraServiceRepository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ExtraServiceController extends Controller
{
    /**
     * @var ExtraServiceRepository
     */
    protected $extraServiceRepository;

    /**
     * @var array
     */
    private $validation = [
        'services.*.name' => 'required|max:512',
        'services.*.price' => 'required|integer',
        'services.*.show' => 'boolean'
    ];

    /**
     * ExtraServiceController constructor.
     * @param ExtraServiceRepository $extraServiceRepository
     */
    function __construct(ExtraServiceRepository $extraServiceRepository)
    {
        $this->extraServiceRepository = $extraServiceRepository;
    }

    /**
     * @return Collection
     */
    public function index(): Collection
    {
        return $this->extraServiceRepository->getServices();
    }

    /**
     * @param Request $request
     * @return array
     */
    public function store(Request $request): array
    {
        $this->validate($request, $this->validation);

        //@TODO: DRY
        foreach ($request->get('services') as $service) {
            if (!empty($service['deleted_at']) && $service['deleted_at']) {
                $this->extraServiceRepository->removeService($service['id']);
                continue;
            } elseif (!empty($service['new']) && $service['new']) {
                $this->extraServiceRepository->addservice($service['name'], (int)$service['price'], (bool)$service['show']);
                continue;
            }
            $this->extraServiceRepository->updateservice($service['id'], $service['name'], (int)$service['price'], (bool)$service['show']);
        }

        return ['code' => '200', 'msg' => 'OK'];
    }
}
