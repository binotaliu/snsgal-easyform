<?php


namespace App\Services\Procurement\Ticket;


use App\Eloquent\Procurement\Ticket as ProcurementTicket;
use App\Repositories\ConfigRepository;
use Illuminate\Database\Eloquent\Collection;

class TotalService
{
    const INT_SHIPPING_FEE = 0.015;

    /**
     * @var ConfigRepository
     */
    protected $configRepository;

    function __construct(ConfigRepository $configRepository)
    {
        $this->configRepository = $configRepository;
    }

    /**
     * @param float $number
     * @param float $rate
     * @return float
     */
    private function clearNumber(float $number, float $rate = 1.0): float
    {
        return round($number * $rate, 2);
    }

    /**
     * Get items' total price
     * @param Collection $items
     * @return int
     */
    private function getItemTotal(Collection $items): int
    {
        $retval = 0;
        foreach ($items as $item) {
            $retval += $item->price;
        }
        // this should be an integer, since it is in Japanese Yen
        return $retval;
    }

    /**
     * Get shipments' total price
     * @param Collection $shipments
     * @return int
     */
    private function getShipmentTotal(Collection $shipments): int
    {
        $retval = 0;
        foreach ($shipments as $shipment) {
            $retval += $shipment->price;
        }
        // this should be an integer, since it is in Japanese Yen
        return $retval;
    }

    /**
     * @param float $total
     * @return float
     */
    private function getIntPaymentFee(float $total): float
    {
        return $total * self::INT_SHIPPING_FEE;
    }

    /**
     * @param Collection $items
     * @param int $minimum
     * @return float
     */
    private function getServiceFee(Collection $items, int $minimum): float
    {
        $fee = 0;
        foreach ($items as $item) {
            /** @var $item ProcurementTicket\Item */
            $itemFee = $item->price * ($item->category->value / 100);
            $fee += max($itemFee, $item->category->lower);
        }
        return max($fee, $minimum);
    }

    /**
     * Get total price
     * @param ProcurementTicket $ticket
     * @return array
     */
    public function getTotal(ProcurementTicket $ticket): array
    {
        $itemTotal = $this->clearNumber($this->getItemTotal($ticket->items), $ticket->rate);
        $shipmentTotal = $this->clearNumber($this->getShipmentTotal($ticket->japanShipments), $ticket->rate);
        $total = $itemTotal + $shipmentTotal;

        $intPaymentFee = $this->clearNumber($this->getIntPaymentFee($total));
        $total += $intPaymentFee;

        $total += $ticket->local_shipment_price;

        $serviceFee = $this->clearNumber($this->getServiceFee($ticket->items, $this->configRepository->getConfig('procurement.minimum_fee')), $ticket->rate);
        $total += $serviceFee;

        // if total = 1089.87:
        //     discount = 9.87
        $discount = 0 - (($total * 100) % 1000) / 100;
        $total += $discount;

        $items = [];
        $items[] = [
            'name' => trans('procurement_ticket_totals.items'),
            'note' => trans('procurement_ticket_totals.items_note'),
            'price' => $itemTotal
        ];
        $items[] = [
            'name' => trans('procurement_ticket_totals.japan_shipment'),
            'note' => trans('procurement_ticket_totals.japan_shipment_note'),
            'price' => $shipmentTotal
        ];
        $items[] = [
            'name' => trans('procurement_ticket_totals.int_payment_fee'),
            'note' => trans('procurement_ticket_totals.int_payment_fee_note'),
            'price' => $intPaymentFee
        ];
        $items[] = [
            'name' => trans('procurement_ticket_totals.local_shipment'),
            'note' => $ticket->local_shipment_method,
            'price' => $ticket->local_shipment_price
        ];
        $items[] = [
            'name' => trans('procurement_ticket_totals.service_fee'),
            'note' => trans('procurement_ticket_totals.service_fee_note'),
            'price' => $serviceFee
        ];
        $items[] = [
            'name' => trans('procurement_ticket_totals.discount'),
            'note' => trans('procurement_ticket_totals.discount_note'),
            'price' => $discount
        ];

        return [
            'items' => $items,
            'total' => $total
        ];
    }
}