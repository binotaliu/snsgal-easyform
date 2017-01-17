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
        $this->validate($request, [
            'methods.*.type' => 'required|in:cvs,standard,none',
            'methods.*.name' => 'required|max:512',
            'methods.*.price' => 'required|numeric',
            'methods.*.show' => 'required|boolean'
        ]);

        $methods = $this->filterItems($request->get('methods'), function ($item) {
            return [
                'type' => $item['type'],
                'name' => $item['name'],
                'price' => $item['price'],
                'show' => $item['show']
            ];
        }, function ($item) {
            return [
                'id' => $item['id'],
                'type' => $item['type'],
                'name' => $item['name'],
                'price' => $item['price'],
                'show' => $item['show']
            ];
        }, function ($item) {
            return $item['id'];
        });

        foreach ($methods['new'] as $method) {
            $this->shipmentMethodRepository->addLocalShipment($method['type'], $method['name'], $method['price'], $method['show']);
        }

        foreach ($methods['update'] as $method) {
            $this->shipmentMethodRepository->updateLocalShipment($method['id'], $method['type'], $method['name'], $method['price'], $method['show']);
        }

        foreach ($methods['delete'] as $method) {
            $this->shipmentMethodRepository->removeLocalShipment($method);
        }

        return ['code' => '200', 'msg' => 'OK'];
    }
}
