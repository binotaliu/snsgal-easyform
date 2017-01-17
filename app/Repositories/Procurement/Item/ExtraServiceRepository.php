<?php


namespace App\Repositories\Procurement\Item;


use App\Eloquent\Procurement\Item\ExtraService;
use Illuminate\Database\Eloquent\Collection;

class ExtraServiceRepository
{
    /**
     * @var ExtraService
     */
    protected $extraService;

    /**
     * ExtraServiceRepository constructor.
     * @param ExtraService $extraService
     */
    function __construct(ExtraService $extraService)
    {
        $this->extraService = $extraService;
    }

    /**
     * Create service
     * @param string $name
     * @param int $price
     * @param bool $show
     * @return ExtraService
     */
    public function addService(string $name, int $price, bool $show): ExtraService
    {
        return $this->extraService->create([
            'name' => $name,
            'price' => $price,
            'show' => $show
        ]);
    }

    /**
     * @param bool $filterShowOnly
     * @return array
     */
    public function getServices(bool $filterShowOnly = false): array
    {
        if ($filterShowOnly) {
            $services = $this->extraService->where('show', true)->get();
        } else {
            $services = $this->extraService->all();
        }

        $retval = [];
        foreach ($services as $service) {
            $retval[$service->id] = $service;
        }

        return $retval;
    }

    /**
     * @param int $id
     * @param string $name
     * @param int $price
     * @param bool $show
     * @return bool
     */
    public function updateService(int $id, string $name, int $price, bool $show): bool
    {
        return $this->extraService->where('id', $id)->update([
            'name' => $name,
            'price' => $price,
            'show' => $show
        ]);
    }

    /**
     * @param int $id
     * @return bool
     */
    public function removeService(int $id): bool
    {
        return $this->extraService->where('id', $id)->delete();
    }
}