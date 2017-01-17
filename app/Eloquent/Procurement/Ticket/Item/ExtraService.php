<?php

namespace App\Eloquent\Procurement\Ticket\Item;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Eloquent\Procurement\Ticket\Item\ExtraService
 *
 * @property int $id
 * @property string $name
 * @property int $price
 * @property bool $show
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property string $deleted_at
 * @method static \Illuminate\Database\Query\Builder|\App\Eloquent\Procurement\Ticket\Item\ExtraService whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Eloquent\Procurement\Ticket\Item\ExtraService whereName($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Eloquent\Procurement\Ticket\Item\ExtraService wherePrice($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Eloquent\Procurement\Ticket\Item\ExtraService whereShow($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Eloquent\Procurement\Ticket\Item\ExtraService whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Eloquent\Procurement\Ticket\Item\ExtraService whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Eloquent\Procurement\Ticket\Item\ExtraService whereDeletedAt($value)
 * @mixin \Eloquent
 */
class ExtraService extends Model
{
    use SoftDeletes;

    protected $table = 'procurement_ticket_item_extra_services';

    protected $guarded = ['id'];
}
