<?php

namespace App\Eloquent\Procurement\Ticket\Item;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Eloquent\Procurement\Ticket\Item\Category
 *
 * @property int $id
 * @property string $name
 * @property float $value
 * @property int $lower
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property string $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Eloquent\Procurement\Ticket\Item[] $items
 * @method static \Illuminate\Database\Query\Builder|\App\Eloquent\Procurement\Ticket\Item\Category whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Eloquent\Procurement\Ticket\Item\Category whereName($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Eloquent\Procurement\Ticket\Item\Category whereValue($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Eloquent\Procurement\Ticket\Item\Category whereLower($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Eloquent\Procurement\Ticket\Item\Category whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Eloquent\Procurement\Ticket\Item\Category whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Eloquent\Procurement\Ticket\Item\Category whereDeletedAt($value)
 * @mixin \Eloquent
 */
class Category extends Model
{
    protected $table = 'procurement_ticket_item_categories';

    protected $guarded = ['id'];

    public function items()
    {
        return $this->hasMany('App\Eloquent\Procurement\Ticket\Item');
    }
}
