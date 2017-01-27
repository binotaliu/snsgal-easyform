<?php

use App\Eloquent\Procurement;
use App\Eloquent\Procurement\Ticket as ProcurementTicket;
use App\Codes\Procurement\TicketStatus;
use App\Codes\Procurement\ItemStatus;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class TicketRepositoryTest extends TestCase
{
    use DatabaseMigrations;

    /**
     * @var \App\Repositories\Procurement\TicketRepository
     */
    protected $ticketRepository;

    /**
     * @var ProcurementTicket\Item\Category
     */
    protected $category;

    public function setUp()
    {
        parent::setUp();
        $this->ticketRepository = app('App\Repositories\Procurement\TicketRepository');
        Artisan::call('configs:initial');

        $this->category = $this->createCategory();
    }

    /**
     * @return Procurement\Item\Category
     */
    public function createCategory(): Procurement\Item\Category
    {
        return factory(Procurement\Item\Category::class)->create();
    }

    /**
     * @param int $itemCount
     * @return ProcurementTicket
     */
    public function createTicket(int $itemCount = 3)
    {
        $items = [];
        while ($itemCount--) {
            $items[] = [
                'status' => ItemStatus::WAITING_CHECK,
                'category_id' => $this->category->id,
                'url' => 'https://www.example.com/products/' . $itemCount,
                'title' => 'Product' . $itemCount,
                'price' => '1750',
                'note' => '',
                'extraServices' => []
            ];
        }

        return $this->ticketRepository->createTicket(
            'BinotaLIU',
            'binota@binota.org',
            'LINE:binota',
            'dfasklfsadklafkljsfdjskl',
            TicketStatus::WAITING_CHECK,
            0.2807,
            '',
            0,
            $items,
            []
        );
    }

    public function testCreateTicket()
    {
        $expectedName = 'BinotaLIU';
        $expectedItem = 3;

        $this->createTicket($expectedItem);

        $ticket = ProcurementTicket::all()->first();
        $this->assertEquals($expectedName, $ticket->name);
        $this->assertEquals($expectedItem, $ticket->items->count());
    }

    public function testUpdateTicketStatus()
    {
        $expected = TicketStatus::TRANSFERRING;

        $ticket = $this->createTicket(3);

        $this->ticketRepository->updateTicketStatus($ticket->token, $expected);
        $actual = ProcurementTicket::find($ticket->id)->status;

        $this->assertEquals($expected, $actual);
    }

    public function testGetTicket()
    {
        $ticket = $this->createTicket(3);
        $expected = $ticket->note;

        $actual = $this->ticketRepository->getTicket($ticket->token)->note;

        $this->assertEquals($expected, $actual);
    }

    public function testUpdateTicket()
    {
        $ticket = $this->createTicket(3);
        $expected = 'Usapyon';
        $this->ticketRepository->updateTicket(
            $ticket->token,
            $expected,
            $ticket->email,
            $ticket->contact,
            $ticket->note,
            $ticket->status,
            $ticket->rate,
            $ticket->local_shipment_method,
            $ticket->local_shipment_price,
            [
                'new' => [],
                'update' => [],
                'delete' => []
            ],
            [
                'new' => [],
                'update' => [],
                'delete' => []
            ]
        );

        $actual = $this->ticketRepository->getTicket($ticket->token)->name;
        $this->assertEquals($expected, $actual);
    }

    public function testGetTickets()
    {
        $expected = 10;
        for ($i = 0; $i < $expected; $i++) {
            $this->createTicket(3);
        }

        $actual = $this->ticketRepository->getTickets()->count();
        $this->assertEquals($expected, $actual);
    }

    public function testRemoveTicket()
    {
        $expected = 10;

        $ticket = null;
        for ($i = 0; $i <= $expected; $i++) { // this will do $expected + 1 times
            $ticket = $this->createTicket(2);
        }

        $this->ticketRepository->removeTicket($ticket->id); //delete last on

        $actual = ProcurementTicket::all()->count();

        $this->assertEquals($expected, $actual);
    }

    public function testArchiveTicket()
    {
        $expected = 1; //archived
        $ticket = $this->createTicket(2);

        $this->ticketRepository->archiveTicket($ticket->token);

        $actual = ProcurementTicket::token($ticket->token)->archived;

        $this->assertEquals($expected, $actual);
    }

    public function testAddJapanShipment()
    {
        $expected = 'Melonbooks';

        $ticket = $this->createTicket(2);
        $this->ticketRepository->addJapanShipment($ticket, $expected, 500);

        $actual = ProcurementTicket::find($ticket->id)->japanShipments->first()->title;

        $this->assertEquals($expected, $actual);
    }
}
