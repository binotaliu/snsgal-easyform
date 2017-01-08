<?php

namespace App\Http\Controllers\Procurement;

use App\Eloquent\Procurement\Ticket;
use App\Repositories\CurrencyRepository;
use App\Repositories\Procurement\TicketRepository;
use App\Codes\Procurement\TicketStatus;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

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
     * TicketController constructor.
     * @param TicketRepository $ticketRepository
     */
    public function __construct(TicketRepository $ticketRepository, CurrencyRepository $currencyRepository)
    {
        $this->ticketRepository = $ticketRepository;
        $this->currencyRepository = $currencyRepository;
    }

    /**
     * Show create form
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function new()
    {
        return view('procurement.tickets.new');
    }

    /**
     * @param Request $request
     * @return Ticket
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|max:256',
            'email' => 'required|email|max:256',
            'contact' => 'required|max:256',
            'note' => 'max:512',
            'items.*.url' => 'required|url|max:256',
            'items.*.title' => 'required|max:512',
            'items.*.note' => 'max:512'
        ]);

        $ticket = $this->ticketRepository->createTicket(
            $request->get('name'),
            $request->get('email'),
            $request->get('contact'),
            $request->get('note'),
            self::DEFAULT_STATUS,
            $this->currencyRepository->getRate('JPY'),
            $request->get('items')
        );

        return $ticket;
    }

    public function view(string $token)
    {
        /** @var Ticket $ticket */
        $ticket = $this->ticketRepository->getTicket($token);

        if (!$ticket) abort(404, 'Ticket not found');

        return view('procurement.tickets.view', [
            'ticket' => $ticket,
            'rate' => $this->currencyRepository->getRate('JPY')
        ]);
    }
}
