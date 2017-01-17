<?php

namespace App\Http\Controllers\Procurement\Ticket;

use App\Repositories\Procurement\Ticket\ShipmentMethodRepository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

abstract class ShipmentMethodAbstract extends Controller
{
    /**
     * @var ShipmentMethodRepository
     */
    protected $shipmentMethodRepository;

    function __construct(ShipmentMethodRepository $shipmentMethodRepository)
    {
        $this->shipmentMethodRepository = $shipmentMethodRepository;
    }

    protected function filterItems(array $items, callable $onCreate, callable $onUpdate, callable $onDelete) {
        //@TODO: DRY
        $retval = [
            'new' => [],
            'update' => [],
            'delete' => []
        ];

        foreach ($items as $item) {
            switch (true) {
                case (!empty($item['deleted_at']) && $item['deleted_at']):
                    $retval['delete'][] = $onDelete($item);
                    break;
                case (!empty($item['new']) && $item['new']):
                    $retval['new'][] = $onCreate($item);
                    break;
                default:
                    $retval['update'][] = $onUpdate($item);
                    break;
            }
        }

        return $retval;
    }

    abstract public function index(): Collection;
    abstract public function store(Request $request): array;
}
