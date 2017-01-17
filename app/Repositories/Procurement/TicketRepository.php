<?php


namespace App\Repositories\Procurement;


use App\Codes\Procurement\ItemStatus;
use App\Eloquent\Procurement;
use App\Eloquent\Procurement\Ticket as ProcurementTicket;
use App\Exceptions\Procurement\Ticket\WrongArgumentException;
use App\Repositories\Procurement\Item\ExtraServiceRepository;
use App\Services\Procurement\Ticket\TotalService;
use Illuminate\Database\Eloquent\Model;
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
     * @var ExtraServiceRepository
     */
    protected $extraServiceRepository;

    /**
     * @var ProcurementTicket\Item\ExtraService
     */
    protected $extraService;

    /**
     * TicketRepository constructor.
     * @param ProcurementTicket $ticket
     * @param ProcurementTicket\Item $item
     * @param ProcurementTicket\JapanShipment $japanShipment
     * @param ProcurementTicket\Total $total
     * @param TotalService $totalService
     * @param ProcurementTicket\Item\ExtraService $extraService
     * @param ExtraServiceRepository $extraServiceRepository
     */
    public function __construct(ProcurementTicket $ticket, ProcurementTicket\Item $item, ProcurementTicket\JapanShipment $japanShipment, ProcurementTicket\Total $total, TotalService $totalService, ProcurementTicket\Item\ExtraService $extraService, ExtraServiceRepository $extraServiceRepository)
    {
        $this->ticket = $ticket;
        $this->item = $item;
        $this->japanShipment = $japanShipment;
        $this->total = $total;
        $this->totalService = $totalService;
        $this->extraService = $extraService;
        $this->extraServiceRepository = $extraServiceRepository;
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
        $this->total->where('ticket_id', $ticket->id)->delete();
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
     * @throws WrongArgumentException
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
        $extraServices = $this->extraServiceRepository->getServices();

        foreach ($items as $item) {
            /** @var ProcurementTicket\Item $query */
            $query = new $this->item([
                'ticket_id' => $ticket->id,
                'status' => $item['status'] ?? ItemStatus::WAITING_CHECK,
                'category_id' => $item['category_id'] ?? 1, //@TODO: default value
                'url' => $item['url'],
                'title' => $item['title'],
                'price' => $item['price'],
                'note' => $item['note']
            ]);
            $query->save();

            // for extra services
            $itemExtraServices = [];
            foreach ($item['extraServices'] as $id => $service) {
                if ($service != true) continue;

                if (!array_has($extraServices, $id)) throw new WrongArgumentException("ExtraService: {$id} not found.");

                $itemExtraServices[] = new $this->extraService([
                    'name' => $extraServices[$id]->name,
                    'price' => $extraServices[$id]->price
                ]);
            }

            $query->extraServices()->saveMany($itemExtraServices);
        }

        $japanShipmentModels = [];
        foreach ($japanShipments as $japanShipment) {
            $japanShipmentModels[] = new $this->japanShipment([
                'title' => $japanShipment['title'],
                'price' => $japanShipment['price']
            ]);
        }

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
        return $this->ticket->with('items.category', 'items.extraServices', 'japanShipments')->token($token);
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
        $ticket = $this->getTicket($token);

        //@TODO: it's too hard to read.
        if (count($items['new'])) {
            foreach ($items['new'] as $item) {
                $this->addItem($ticket, $item['status'], $item['category_id'], $item['title'], $item['price'], $item['url'], $item['note'], $item['extraServices']);
            } // foreach
        } // if count new

        if (count($items['update'])) {
            foreach ($items['update'] as $item) {
                $this->updateItem($item['id'], $item['status'], $item['category_id'], $item['title'], $item['price'], $item['url'], $item['note'], $item['extraServices']);
            } // foreach
        } // if count update

        if (count($items['delete'])) {
            $this->item->whereIn('id', $items['delete'])->delete();
        } // if count delete

        if (count($japanShipments['new'])) {
            foreach ($japanShipments['new'] as $japanShipment) {
                $this->addJapanShipment($ticket, $japanShipment['title'], $japanShipment['price']);
            }
        } // if count new

        if (count($japanShipments['update'])) {
            foreach ($japanShipments['update'] as $japanShipment) {
                $this->updateJapanShipment($japanShipment['id'], $japanShipment['title'], $japanShipment['price']);
            } // foreach
        } //if update

        if (count($japanShipments['delete'])) {
            $this->removeJapanShipments($japanShipments['delete']);
        } //if count delete

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
        return $this->ticket->with('items.category', 'items.extraServices', 'japanShipments')->archived(false)->orderBy('updated_at', 'desc')->get();
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
     * @param string $token
     * @return bool
     */
    public function archiveTicket(string $token): bool
    {
        return $this->ticket->where('token', $token)->update([
            'archived' => 1
        ]);
    }

    /**
     * @param ProcurementTicket $ticket
     * @param string $title
     * @param float $price
     * @return Model
     */
    public function addJapanShipment(ProcurementTicket $ticket, string $title, float $price): Model
    {
        return $ticket->japanShipments()->save(
            new $this->japanShipment([
                'title' => $title,
                'price' => $price
            ])
        );
    }

    /**
     * @param int $id
     * @param string $title
     * @param float $price
     * @return bool
     */
    public function updateJapanShipment(int $id, string $title, float $price): bool
    {
        return $this->japanShipment->where('id', $id)->update([
            'title' => $title,
            'price' => $price
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
     * @param array $ids
     * @return bool
     */
    public function removeJapanShipments(array $ids): bool
    {
        return $this->japanShipment->whereIn('id', $ids)->delete();
    }

    /**
     * @param ProcurementTicket $ticket
     * @param int $status
     * @param int $category
     * @param string $title
     * @param int $price
     * @param string $url
     * @param string $note
     * @return Model
     */
    public function addItem(ProcurementTicket $ticket, int $status, int $category, string $title, int $price, string $url, string $note, array $extraServices): Model
    {
        $item = $this->item->create([
            'ticket_id' => $ticket->id,
            'status' => $status,
            'category_id' => $category,
            'url' => $url,
            'title' => $title,
            'price' => $price,
            'note' => $note
        ]);
        foreach ($extraServices as $service) {
            $this->addItemExtraService($item->id, $service['name'], $service['price']);
        }
        return $item;
    }

    /**
     * @param int $id
     * @param int $status
     * @param int $category
     * @param string $title
     * @param int $price
     * @param string $url
     * @param string $note
     * @return bool
     */
    public function updateItem(int $id, int $status, int $category, string $title, int $price, string $url, string $note, array $extraServices): bool
    {
        $item = $this->item->where('id', $id)->update([
            'status' => $status,
            'category_id' => $category,
            'url' => $url,
            'title' => $title,
            'price' => $price,
            'note' => $note
        ]);
        foreach ($extraServices['new'] as $service) {
            $this->addItemExtraService($id, $service['name'], $service['price']);
        }
        foreach ($extraServices['update'] as $service) {
            $this->updateItemExtraService($service['id'], $service['name'], $service['price']);
        }
        $this->removeItemExtraServices($extraServices['delete']);

        return $item;
    }

    /**
     * @param int $id
     * @return bool
     */
    public function removeItem(int $id): bool
    {
        return $this->item->where('id', $id)->delete();
    }

    /**
     * @param array $ids
     * @return bool
     */
    public function removeItems(array $ids): bool
    {
        return $this->item->whereIn('id', $ids)->delete();
    }

    /**
     * @param int $item
     * @param string $name
     * @param int $price
     * @return Model
     */
    public function addItemExtraService(int $item, string $name, int $price): Model
    {
        return $this->extraService->create([
            'item_id' => $item,
            'name' => $name,
            'price' => $price
        ]);
    }

    /**
     * @param int $id
     * @param string $name
     * @param int $price
     * @return bool
     */
    public function updateItemExtraService(int $id, string $name, int $price): bool
    {
        return $this->extraService->where('id', $id)->update([
            'name' => $name,
            'price' => $price
        ]);
    }

    /**
     * @param int $id
     * @return bool
     */
    public function removeItemExtraService(int $id): bool
    {
        return $this->extraService->where('id', $id)->delete();
    }

    /**
     * @param array $ids
     * @return bool
     */
    public function removeItemExtraServices(array $ids): bool
    {
        return $this->extraService->whereIn('id', $ids)->delete();
    }
}