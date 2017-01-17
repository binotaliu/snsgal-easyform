<?php

namespace App\Http\Controllers\Procurement;

use App\Codes\Procurement\ItemStatus;
use App\Codes\Procurement\TicketStatus;
use App\Eloquent\Procurement\Ticket;
use App\Http\Controllers\Controller;
use App\Repositories\ConfigRepository;
use App\Repositories\CurrencyRepository;
use App\Repositories\Procurement\Item\ExtraServiceRepository;
use App\Repositories\Procurement\Ticket\ShipmentMethodRepository;
use App\Repositories\Procurement\TicketRepository;
use App\Services\Procurement\Ticket\TotalService;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;

class TicketController extends Controller
{
    const DEFAULT_STATUS = TicketStatus::WAITING_CHECK;

    /**
     * @var TicketRepository
     */
    protected $ticketRepository;

    /**
     * @var CurrencyRepository
     */
    protected $currencyRepository;

    /**
     * @var ConfigRepository
     */
    protected $configRepository;

    /**
     * @var ShipmentMethodRepository
     */
    protected $shipmentMethodRepository;

    /**
     * @var ExtraServiceRepository
     */
    protected $extraServiceRepository;

    protected $ticketValidation = [
        'name' => 'required|max:256',
        'email' => 'required|email|max:256',
        'contact' => 'required|max:256',
        'note' => 'max:512',
        'items.*.url' => 'url|max:256',
        'items.*.price' => 'required|numeric',
        'items.*.title' => 'required|max:512',
        'items.*.note' => 'max:512',
        'japanShipments.*.title' => 'max:512',
        'japanShipments.*.price' => 'numeric',
        'shipment' => 'numeric'
    ];

    /**
     * TicketController constructor.
     * @param TicketRepository $ticketRepository
     * @param CurrencyRepository $currencyRepository
     * @param TotalService $totalService
     * @param ShipmentMethodRepository $shipmentMethodRepository
     */
    public function __construct(TicketRepository $ticketRepository, CurrencyRepository $currencyRepository, TotalService $totalService, ShipmentMethodRepository $shipmentMethodRepository, ExtraServiceRepository $extraServiceRepository)
    {
        $this->ticketRepository = $ticketRepository;
        $this->currencyRepository = $currencyRepository;
        $this->shipmentMethodRepository = $shipmentMethodRepository;
        $this->extraServiceRepository = $extraServiceRepository;
    }

    /**
     * Show create form
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function new()
    {
        return view('procurement.tickets.new', [
            'shipments' => $this->shipmentMethodRepository->getLocalShipments(),
            'extraServices' => $this->extraServiceRepository->getServices(true)
        ]);
    }

    /**
     * @param Request $request
     * @return Ticket
     */
    public function store(Request $request)
    {
        $this->validate($request, $this->ticketValidation);
        $shipment = $this->shipmentMethodRepository->getLocalShipment((int)$request->get('shipment'));

        $ticket = $this->ticketRepository->createTicket(
            $request->get('name'),
            $request->get('email'),
            $request->get('contact'),
            $request->get('note'),
            self::DEFAULT_STATUS,
            $this->currencyRepository->getRate('JPY'),
            $shipment->name,
            $shipment->price,
            $request->get('items'),
            []
        );

        return $ticket;
    }

    public function archive(string $token): array
    {
        $this->ticketRepository->archiveTicket($token);

        return ['code' => 200, 'msg' => 'OK'];
    }

    /**
     * Get ticket by token
     * @param string $token
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function get(string $token)
    {
        /** @var Ticket $ticket */
        $ticket = $this->ticketRepository->getTicket($token);

        if (!$ticket) abort(404, 'Ticket not found');

        return view('procurement.tickets.view', [
            'ticket' => $ticket,
            'ticket_status' => TicketStatus::getCodes(),
            'item_status' => ItemStatus::getCodes(),
            'rate' => $this->currencyRepository->getRate('JPY')
        ]);
    }

    /**
     * Get list view.
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function view()
    {
        return view('procurement.tickets.list', [
            'ticket_status' => TicketStatus::getCodes(),
            'item_status' => ItemStatus::getCodes(),
            'rate' => $this->currencyRepository->getRate('JPY')
        ]);
    }

    /**
     * Get all tickets
     * @return Collection
     */
    public function index()
    {
        /** @var Collection $tickets */
        $tickets = $this->ticketRepository->getTickets();

        return $tickets;
    }

    /**
     * Filter input into [new => [], delete => [], update => []]
     * @param array $items
     * @param callable $onDelete
     * @param callable $onUpdate
     * @param callable $onCreate
     * @return array
     */
    private function filterItems(array $items, callable $onDelete, callable $onUpdate, callable $onCreate)
    {
        $newItems = [];
        $deleteItems = [];
        $updateItems = [];
        foreach ($items as $item) {
            switch (true) {
                case !empty($item['deleted']) && ($item['deleted'] == true):
                    if (empty($item['id'])) break;
                    $deleteItems[] = $onDelete($item);
                    break;
                case !empty($item['created_at']):
                    $updateItems[] = $onUpdate($item);
                    break;
                default:
                    $newItems[] = $onCreate($item);
                    break;
            }
        }

        return [
            'new' => $newItems,
            'delete' => $deleteItems,
            'update' => $updateItems
        ];
    }

    public function update(Request $request)
    {
        $this->validate($request, $this->ticketValidation);

        $items = $this->filterItems($request->get('items'), function ($item) {
            return $item['id'];
        }, function ($item) {
            return [
                'id' => $item['id'],
                'status' => $item['status'],
                'category_id' => $item['category_id'],
                'title' => $item['title'],
                'url' => $item['url'],
                'price' => $item['price'],
                'note' => $item['note']
            ];
        }, function ($item) {
            return [
                'status' => $item['status'],
                'category_id' => $item['category_id'],
                'title' => $item['title'],
                'url' => $item['url'],
                'price' => $item['price'],
                'note' => $item['note']
            ];
        });
        $japanShipments = $this->filterItems($request->get('japanShipments'), function ($item) {
            return $item['id'];
        }, function ($item) {
            return [
                'id' => $item['id'],
                'title' => $item['title'],
                'price' => $item['price']
            ];
        }, function ($item) {
            return [
                'title' => $item['title'],
                'price' => $item['price']
            ];
        });

        $this->ticketRepository->updateTicket(
            $request->get('token'),
            $request->get('name'),
            $request->get('email'),
            $request->get('contact'),
            $request->get('note'),
            $request->get('status'),
            $request->get('rate'),
            $request->get('localShipment')['method'],
            $request->get('localShipment')['price'],
            $items,
            $japanShipments
        );
    }
}
