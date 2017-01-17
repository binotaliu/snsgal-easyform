<?php

namespace App\Eloquent\Procurement\Ticket\ShipmentMethod;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Eloquent\Procurement\Ticket\ShipmentMethod\Japan
 *
 * @property int $id
 * @property string $name
 * @property int $price
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property string $deleted_at
 * @method static \Illuminate\Database\Query\Builder|\App\Eloquent\Procurement\Ticket\ShipmentMethod\Japan whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Eloquent\Procurement\Ticket\ShipmentMethod\Japan whereName($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Eloquent\Procurement\Ticket\ShipmentMethod\Japan wherePrice($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Eloquent\Procurement\Ticket\ShipmentMethod\Japan whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Eloquent\Procurement\Ticket\ShipmentMethod\Japan whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Eloquent\Procurement\Ticket\ShipmentMethod\Japan whereDeletedAt($value)
 * @mixin \Eloquent
 */
class Japan extends Model
{
    use SoftDeletes;

    protected $table = 'procurement_ticket_japan_shipment_methods';

    protected $guarded = ['id'];
}
