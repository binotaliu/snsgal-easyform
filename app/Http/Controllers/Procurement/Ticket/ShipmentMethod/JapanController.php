<?php

namespace App\Http\Controllers\Procurement\Ticket\ShipmentMethod;

use App\Http\Controllers\Procurement\Ticket\ShipmentMethodAbstract;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class JapanController extends ShipmentMethodAbstract
{
    /**
     * @return Collection
     */
    public function index(): Collection
    {
        return $this->shipmentMethodRepository->getJapanShipments();
    }

    public function store(Request $request): array
    {
        $this->validate($request, [
            'methods.*.name' => 'required|max:512',
            'methods.*.price' => 'required|numeric'
        ]);

        $methods = $this->filterItems($request->get('methods'), function ($item) {
            return [
                'name' => $item['name'],
                'price' => $item['price']
            ];
        }, function ($item) {
            return [
                'id' => $item['id'],
                'name' => $item['name'],
                'price' => $item['price']
            ];
        }, function ($item) {
            return $item['id'];
        });

        foreach ($methods['new'] as $method) {
            $this->shipmentMethodRepository->addJapanShipment($method['name'], $method['price']);
        }

        foreach ($methods['update'] as $method) {
            $this->shipmentMethodRepository->updateJapanShipment($method['id'], $method['name'], $method['price']);
        }

        foreach ($methods['delete'] as $method) {
            $this->shipmentMethodRepository->removeJapanShipment($method);
        }

        return ['code' => '200', 'msg' => 'OK'];
    }
}
