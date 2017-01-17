<?php

namespace App\Eloquent\Procurement\Ticket\Item;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * App\Eloquent\Procurement\Ticket\Item\ExtraService
 *
 * @property int $id
 * @property int $item_id
 * @property string $name
 * @property int $price
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property-read \App\Eloquent\Procurement\Ticket\Item $item
 * @method static \Illuminate\Database\Query\Builder|\App\Eloquent\Procurement\Ticket\Item\ExtraService whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Eloquent\Procurement\Ticket\Item\ExtraService whereItemId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Eloquent\Procurement\Ticket\Item\ExtraService whereName($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Eloquent\Procurement\Ticket\Item\ExtraService wherePrice($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Eloquent\Procurement\Ticket\Item\ExtraService whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Eloquent\Procurement\Ticket\Item\ExtraService whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class ExtraService extends Model
{
    protected $table = 'procurement_ticket_item_extra_services';

    protected $guarded = ['id'];

    /**
     * @return BelongsTo
     */
    public function item(): BelongsTo
    {
        return $this->belongsTo('App\Eloquent\Procurement\Ticket\Item');
    }
}
