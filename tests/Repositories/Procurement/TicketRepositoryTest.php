<?php

use App\Eloquent\Procurement\Ticket as ProcurementTicket;
use App\Status\Procurement\TicketStatus;
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

    public function setUp()
    {
        parent::setUp();
        $this->ticketRepository = app('App\Repositories\Procurement\TicketRepository');
    }

    /**
     * @param int $itemCount
     * @return mixed
     */
    public function createTicket(int $itemCount = 3)
    {
        $items = [];
        while ($itemCount--) {
            $items[] = [
                'url' => 'https://www.example.com/products/' . $itemCount,
                'title' => 'Product' . $itemCount,
                'price' => '1750',
                'note' => ''
            ];
        }

        return $this->ticketRepository->createTicket(
            'BinotaLIU',
            'binota@binota.org',
            'LINE:binota',
            'dfasklfsadklafkljsfdjskl',
            TicketStatus::WAITING_CHECK,
            0.2807,
            $items
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

        $this->ticketRepository->updateTicketStatus($ticket->id, $expected);
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

    public function testRemoveTicket()
    {
        $expected = 10;

        for ($i = 0; $i <= $expected; $i++) { // this will do $expected + 1 times
            $ticket = $this->createTicket(2);
        }

        $this->ticketRepository->removeTicket($ticket->id); //delete last on

        $actual = ProcurementTicket::all()->count();

        $this->assertEquals($expected, $actual);
    }
}
