<?php

namespace App\Eloquent\Procurement\Ticket;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Eloquent\Procurement\Ticket\JapanShipment
 *
 * @property int $id
 * @property int $ticket_id
 * @property string $title
 * @property float $price
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property string $deleted_at
 * @property-read \App\Eloquent\Procurement\Ticket $ticket
 * @method static \Illuminate\Database\Query\Builder|\App\Eloquent\Procurement\Ticket\JapanShipment whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Eloquent\Procurement\Ticket\JapanShipment whereTicketId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Eloquent\Procurement\Ticket\JapanShipment whereTitle($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Eloquent\Procurement\Ticket\JapanShipment wherePrice($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Eloquent\Procurement\Ticket\JapanShipment whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Eloquent\Procurement\Ticket\JapanShipment whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Eloquent\Procurement\Ticket\JapanShipment whereDeletedAt($value)
 * @mixin \Eloquent
 */
class JapanShipment extends Model
{
    /**
     * @var string
     */
    protected $table = 'procurement_ticket_japan_shipments';

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
