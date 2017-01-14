<?php

namespace App\Eloquent\Procurement\Ticket;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Eloquent\Procurement\Ticket\Total
 *
 * @property int $id
 * @property int $ticket_id
 * @property string $name
 * @property float $price
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property-read \App\Eloquent\Procurement\Ticket $ticket
 * @method static \Illuminate\Database\Query\Builder|\App\Eloquent\Procurement\Ticket\Total whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Eloquent\Procurement\Ticket\Total whereTicketId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Eloquent\Procurement\Ticket\Total whereName($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Eloquent\Procurement\Ticket\Total wherePrice($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Eloquent\Procurement\Ticket\Total whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Eloquent\Procurement\Ticket\Total whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property string $note
 * @method static \Illuminate\Database\Query\Builder|\App\Eloquent\Procurement\Ticket\Total whereNote($value)
 */
class Total extends Model
{
    /**
     * @var string
     */
    protected $table = 'procurement_ticket_totals';

    /**
     * @var array
     */
    protected $guarded = ['id'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function ticket()
    {
        return $this->belongsTo('App\Eloquent\Procurement\Ticket');
    }
}
