<?php

namespace App\Http\Controllers\Procurement\Ticket\ShipmentMethod;

use App\Http\Controllers\Procurement\Ticket\ShipmentMethodAbstract;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class LocalController extends ShipmentMethodAbstract
{
    /**
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function index(): Collection
    {
        return $this->shipmentMethodRepository->getLocalShipments();
    }

    public function store(Request $request): array
    {
        // TODO: Implement store() method.
    }
}
