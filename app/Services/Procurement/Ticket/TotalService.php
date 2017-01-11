<?php


namespace App\Services\Procurement\Ticket;


use App\Eloquent\Procurement\Ticket as ProcurementTicket;
use Illuminate\Database\Eloquent\Collection;

class TotalService
{
    const INT_SHIPPING_FEE = 0.015;

    /**
     * Get items' total price
     * @param Collection $items
     * @return int
     */
    private function getItemTotal(Collection $items)
    {
        $retval = 0;
        foreach ($items as $item) {
            $retval += $item->price;
        }
        return $retval;
    }

    /**
     * Get shipments' total price
     * @param Collection $shipments
     * @return int
     */
    private function getShipmentTotal(Collection $shipments)
    {
        $retval = 0;
        foreach ($shipments as $shipment) {
            $retval += $shipment->price;
        }
        return $retval;
    }

    private function getIntPaymentFee(float $total)
    {
        return $total * self::INT_SHIPPING_FEE;
    }

    /**
     * Get total price
     * @param ProcurementTicket $ticket
     * @return array
     */
    public function getTotal(ProcurementTicket $ticket)
    {
        $itemTotal = round($this->getItemTotal($ticket->items) * $ticket->rate, 2);
        $shipmentTotal = round($this->getShipmentTotal($ticket->japanShipments) * $ticket->rate, 2);
        $total = $itemTotal + $shipmentTotal;

        $intPaymentFee = round($this->getIntPaymentFee($total), 2);
        $total += $intPaymentFee;

        $total += $ticket->local_shipment_price;

        $discount = 0 - (($total * 100) % 1000) / 100;

        $retval = [];
        $retval[] = [
            'name' => trans('procurement_ticket_totals.items'),
            'final' => false,
            'note' => trans('procurement_ticket_totals.items_note'),
            'price' => $itemTotal
        ];
        $retval[] = [
            'name' => trans('procurement_ticket_totals.japan_shipment'),
            'final' => false,
            'note' => trans('procurement_ticket_totals.japan_shipment_note'),
            'price' => $shipmentTotal
        ];
        $retval[] = [
            'name' => trans('procurement_ticket_totals.int_payment_fee'),
            'final' => false,
            'note' => trans('procurement_ticket_totals.int_payment_fee_note'),
            'price' => $intPaymentFee
        ];
        $retval[] = [
            'name' => trans('procurement_ticket_totals.local_shipment'),
            'final' => false,
            'note' => $ticket->local_shipment_method,
            'price' => $ticket->local_shipment_price
        ];
        $retval[] = [
            'name' => trans('procurement_ticket_totals.discount'),
            'final' => false,
            'note' => trans('procurement_ticket_totals.discount_note'),
            'price' => $discount
        ];
        $retval[] = [
            'name' => trans('procurement_ticket_totals.total'),
            'final' => true,
            'note' => trans('procurement_ticket_totals.total_note'),
            'price' => $total + $discount
        ];

        return $retval;
    }
}