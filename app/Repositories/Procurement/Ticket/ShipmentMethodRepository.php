<?php


namespace App\Repositories\Procurement\Ticket;


use App\Eloquent\Procurement\Ticket\ShipmentMethod;
use Illuminate\Database\Eloquent\Collection;

class ShipmentMethodRepository
{
    /**
     * @var ShipmentMethod\Local
     */
    protected $localShipment;

    /**
     * @var ShipmentMethod\Japan
     */
    protected $japanShipment;

    /**
     * ShipmentMethodRepository constructor.
     * @param ShipmentMethod\Local $localShipment
     * @param ShipmentMethod\Japan $japanShipment
     */
    function __construct(ShipmentMethod\Local $localShipment, ShipmentMethod\Japan $japanShipment)
    {
        $this->localShipment = $localShipment;
        $this->japanShipment = $japanShipment;
    }

    /**
     * @return Collection
     */
    public function getJapanShipments(): Collection
    {
        return $this->japanShipment->all();
    }

    /**
     * @return Collection
     */
    public function getLocalShipments(): Collection
    {
        return $this->localShipment->all();
    }

    /**
     * @param string $name
     * @param int $price
     * @return ShipmentMethod\Japan
     */
    public function addJapanShipment(string $name, int $price): ShipmentMethod\Japan
    {
        return $this->japanShipment->create([
            'name' => $name,
            'price' => $price
        ]);
    }

    /**
     * @param string $type
     * @param string $name
     * @param int $price
     * @param bool $show
     * @return ShipmentMethod\Local
     */
    public function addLocalShipment(string $type, string $name, int $price, bool $show): ShipmentMethod\Local
    {
        return $this->localShipment->create([
            'type' => $type,
            'name' => $name,
            'price' => $price,
            'show' => $show
        ]);
    }

    /**
     * @param int $id
     * @param string $name
     * @param int $price
     * @return bool
     */
    public function updateJapanShipment(int $id, string $name, int $price): bool
    {
        return $this->japanShipment->where('id', $id)->update([
            'name' => $name,
            'price' => $price
        ]);
    }

    /**
     * @param int $id
     * @param string $type
     * @param string $name
     * @param int $price
     * @param bool $show
     * @return bool
     */
    public function updateLocalShipment(int $id, string $type, string $name, int $price, bool $show): bool
    {
        return $this->localShipment->where('id', $id)->update([
            'type' => $type,
            'name' => $name,
            'price' => $price,
            'show' => $show
        ]);
    }

    /**
     * @param int $id
     * @return bool
     */
    public function removeJapanShipment(int $id): bool
    {
        return $this->japanShipment->where('id', $id)->delete();
    }

    /**
     * @param int $id
     * @return bool
     */
    public function removeLocalShipment(int $id): bool
    {
        return $this->localShipment->where('id', $id)->delete();
    }
}
