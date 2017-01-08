<?php


namespace App\Repositories\Procurement;


use App\Eloquent\Procurement\Ticket as ProcurementTicket;
use App\Eloquent\Procurement\Ticket\Item as ProcurementTicketItem;
use Ramsey\Uuid\Uuid;

class TicketRepository
{
    /**
     * @var ProcurementTicket
     */
    protected $ticket;

    /**
     * @var ProcurementTicketItem
     */
    protected $item;

    /**
     * TicketRepository constructor.
     * @param ProcurementTicket $ticket
     */
    public function __construct(ProcurementTicket $ticket, ProcurementTicketItem $item)
    {
        $this->ticket = $ticket;
        $this->item = $item;
    }

    /**
     * Create a new ticket.
     * @param string $name
     * @param string $email
     * @param string $contact
     * @param string $note
     * @param int $status
     * @param float $rate
     * @param array $items
     * @return ProcurementTicket
     */
    public function createTicket(string $name, string $email, string $contact, string $note, int $status, float $rate, array $items)
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
            'rate' => $rate
        ]);
        $ticket->save();

        $itemModels = [];
        foreach ($items as $item) {
            $itemModels[] = new $this->item([
                'url' => $item['url'],
                'title' => $item['title'],
                'price' => $item['price'],
                'note' => $item['note']
            ]);
        }

        $ticket->items()->saveMany($itemModels);

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
        return $this->ticket->with('items')->whereToken($token)->first();
    }

    /**
     * @param int $id
     * @return bool|null
     */
    public function removeTicket(int $id)
    {
        return $this->ticket->find($id)->delete();
    }
}