<?php


namespace App\Repositories\Procurement\Ticket\Item;


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
     * @return Collection
     */
    public function getServices(): Collection
    {
        return $this->extraService->all();
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