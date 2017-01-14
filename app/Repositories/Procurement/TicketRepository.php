<?php


namespace App\Repositories\Procurement;


use App\Codes\Procurement\ItemStatus;
use App\Eloquent\Procurement\Ticket as ProcurementTicket;
use App\Services\Procurement\Ticket\TotalService;
use Ramsey\Uuid\Uuid;

class TicketRepository
{
    /**
     * @var ProcurementTicket
     */
    protected $ticket;

    /**
     * @var ProcurementTicket\Item
     */
    protected $item;

    /**
     * @var ProcurementTicket\JapanShipment
     */
    protected $japanShipment;

    /**
     * @var ProcurementTicket\Total
     */
    protected $total;

    /**
     * @var TotalService
     */
    protected $totalService;

    /**
     * TicketRepository constructor.
     * @param ProcurementTicket $ticket
     * @param ProcurementTicket\Item $item
     * @param ProcurementTicket\JapanShipment $japanShipment
     * @param ProcurementTicket\Total $total
     * @param TotalService $totalService
     */
    public function __construct(ProcurementTicket $ticket, ProcurementTicket\Item $item, ProcurementTicket\JapanShipment $japanShipment, ProcurementTicket\Total $total, TotalService $totalService)
    {
        $this->ticket = $ticket;
        $this->item = $item;
        $this->japanShipment = $japanShipment;
        $this->total = $total;
        $this->totalService = $totalService;
    }

    private function saveTotals(ProcurementTicket $ticket)
    {
        $totals = $this->totalService->getTotal($ticket);
        $totalModels = [];
        foreach ($totals['items'] as $total) {
            $totalModels[] = new $this->total([
                'name' => $total['name'],
                'note' => $total['note'],
                'price' => $total['price']
            ]);
        }
        $ticket->totals->each(function (ProcurementTicket\Total $total) {
            $total->delete();
        });
        $ticket->totals()->saveMany($totalModels);
        $ticket->update(['total' => $totals['total']]);
    }

    /**
     * Create a new ticket.
     * @param string $name
     * @param string $email
     * @param string $contact
     * @param string $note
     * @param int $status
     * @param float $rate
     * @param string $localShipmentMethod
     * @param int $localShipmentPrice
     * @param array $items
     * @param array $japanShipments
     * @return ProcurementTicket
     */
    public function createTicket(string $name, string $email, string $contact, string $note, int $status, float $rate, string $localShipmentMethod, int $localShipmentPrice, array $items, array $japanShipments)
    {
        // Create a new token
        $token = Uuid::uuid1();

        /** @var ProcurementTicket $ticket */
        $ticket = new $this->ticket([
            'token' => $token,
            'name' => $name,
            'email' => $email,
            'contact' => $contact,
            'note' => $note,
            'status' => $status,
            'rate' => $rate,
            'total' => 0,
            'local_shipment_method' => $localShipmentMethod,
            'local_shipment_price' => $localShipmentPrice
        ]);
        $ticket->save();

        $itemModels = [];
        foreach ($items as $item) {
            $itemModels[] = new $this->item([
                'status' => $item['status'] ?? ItemStatus::WAITING_CHECK,
                'url' => $item['url'],
                'title' => $item['title'],
                'price' => $item['price'],
                'note' => $item['note']
            ]);
        }

        $japanShipmentModels = [];
        foreach ($japanShipments as $japanShipment) {
            $japanShipmentModels[] = new $this->japanShipment([
                'title' => $japanShipment['title'],
                'price' => $japanShipment['price']
            ]);
        }

        $ticket->items()->saveMany($itemModels);
        $ticket->japanShipments()->saveMany($japanShipmentModels);

        $this->saveTotals($ticket);
        return $ticket;
    }

    /**
     * Update status of ticket.
     * @param int $id
     * @param int $status
     * @return bool
     */
    public function updateTicketStatus(int $id, int $status)
    {
        $ticket = $this->ticket->find($id);
        return $ticket->update([
            'status' => $status
        ]);
    }

    /**
     * Get ticket by token.
     * @param string $token
     * @return ProcurementTicket
     */
    public function getTicket(string $token)
    {
        return $this->ticket->with('items', 'japanShipments')->token($token);
    }

    /**
     * @param string $token
     * @param string $name
     * @param string $email
     * @param string $contact
     * @param string $note
     * @param int $status
     * @param float $rate
     * @param string $localShipmentMethod
     * @param int $localShipmentPrice
     * @param array $items
     * @param array $japanShipments
     */
    public function updateTicket(string $token, string $name, string $email, string $contact, string $note, int $status, float $rate, string $localShipmentMethod, int $localShipmentPrice, array $items, array $japanShipments)
    {
        //@TODO: it's too hard to read.
        if (count($items['new'])) {
            $newItems = [];
            foreach ($items['new'] as $item) {
                $newItems[] = new $this->item([
                    'status' => $item['status'],
                    'url' => $item['url'],
                    'title' => $item['title'],
                    'price' => $item['price'],
                    'note' => $item['note']
                ]);
            } // foreach
            $this->ticket->token($token)->items()->saveMany($newItems);
        } // if count new

        if (count($items['update'])) {
            foreach ($items['update'] as $item) {
                $this->item->find($item['id'])->update([
                    'status' => $item['status'],
                    'title' => $item['title'],
                    'price' => $item['price'],
                    'url' => $item['url'],
                    'note' => $item['note']
                ]);
            } // foreach
        } // if count update

        if (count($items['delete'])) {
            $this->item->whereIn('id', $items['delete'])->delete();
        } // if count delete

        if (count($japanShipments['new'])) {
            $newJapanShipments = [];
            foreach ($japanShipments['new'] as $japanShipment) {
                $newJapanShipments[] = new $this->japanShipment([
                    'title' => $japanShipment['title'],
                    'price' => $japanShipment['price']
                ]);
            }

            $this->ticket->token($token)->japanShipments()->saveMany($newJapanShipments);
        } // if count new

        if (count($japanShipments['update'])) {
            foreach ($japanShipments['update'] as $japanShipment) {
                $this->japanShipment->find($japanShipment['id'])->update([
                    'title' => $japanShipment['title'],
                    'price' => $japanShipment['price']
                ]);
            } // foreach
        } //if update

        if (count($japanShipments['delete'])) {
            $this->japanShipment->whereIn('id', $japanShipments['delete'])->delete();
        } //if count delete

        $ticket = $this->ticket->token($token);

        $ticket->update([
            'name' => $name,
            'email' => $email,
            'contact' => $contact,
            'note' => $note,
            'status' => $status,
            'rate' => $rate,
            'local_shipment_method' => $localShipmentMethod,
            'local_shipment_price' => $localShipmentPrice
        ]);

        $this->saveTotals($ticket);
    }

    /**
     * Get all tickets
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function getTickets()
    {
        return $this->ticket->with('items', 'japanShipments')->orderBy('updated_at', 'desc')->get();
    }

    /**
     * @param int $id
     * @return bool|null
     */
    public function removeTicket(int $id)
    {
        return $this->ticket->find($id)->delete();
    }

    /**
     * @param ProcurementTicket $ticket
     * @param string $title
     * @param float $price
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function addJapanShipment(ProcurementTicket $ticket, string $title, float $price)
    {
        return $ticket->japanShipments()->save(
            new $this->japanShipment([
                'title' => $title,
                'price' => $price
            ])
        );
    }
}