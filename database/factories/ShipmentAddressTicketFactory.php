<?php

namespace Database\Factories;

use App\Models\Shipment\AddressTicket;
use Illuminate\Database\Eloquent\Factories\Factory;

class ShipmentAddressTicketFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = AddressTicket::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'title' => $this->faker->company,
            'description' => $this->faker->paragraph,
            'address_type' => $this->faker->boolean ? 'cvs' : 'standard',
            'token' => $this->faker->uuid
        ];
    }

    public function cvs()
    {
        return $this->state(function (array $_) {
            return [
                'address_type' => 'cvs',
            ];
        });
    }

    public function standard()
    {
        return $this->state(function (array $_) {
            return [
                'address_type' => 'standard',
            ];
        });
    }
}
