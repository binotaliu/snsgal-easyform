<?php

namespace App\Eloquent\Procurement\Ticket\ShipmentMethod;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Eloquent\Procurement\Ticket\ShipmentMethod\Local
 *
 * @property int $id
 * @property string $name
 * @property string $type
 * @property bool $show
 * @property int $price
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property string $deleted_at
 * @method static \Illuminate\Database\Query\Builder|\App\Eloquent\Procurement\Ticket\ShipmentMethod\Local whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Eloquent\Procurement\Ticket\ShipmentMethod\Local whereName($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Eloquent\Procurement\Ticket\ShipmentMethod\Local whereType($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Eloquent\Procurement\Ticket\ShipmentMethod\Local whereShow($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Eloquent\Procurement\Ticket\ShipmentMethod\Local wherePrice($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Eloquent\Procurement\Ticket\ShipmentMethod\Local whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Eloquent\Procurement\Ticket\ShipmentMethod\Local whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Eloquent\Procurement\Ticket\ShipmentMethod\Local whereDeletedAt($value)
 * @mixin \Eloquent
 */
class Local extends Model
{
    protected $table = 'procurement_ticket_local_shipment_methods';

    protected $guarded = ['id'];
}
