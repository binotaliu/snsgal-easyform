<?php

namespace App\Eloquent\Procurement\Item;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Eloquent\Procurement\Item
 *
 * @property int $id
 * @property string $name
 * @property float $value
 * @property int $lower
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property string $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Eloquent\Procurement\Ticket\Item[] $items
 * @method static \Illuminate\Database\Query\Builder|\App\Eloquent\Procurement\Item\Category whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Eloquent\Procurement\Item\Category whereName($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Eloquent\Procurement\Item\Category whereValue($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Eloquent\Procurement\Item\Category whereLower($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Eloquent\Procurement\Item\Category whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Eloquent\Procurement\Item\Category whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Eloquent\Procurement\Item\Category whereDeletedAt($value)
 * @mixin \Eloquent
 */
class Category extends Model
{
    protected $table = 'procurement_item_categories';

    protected $guarded = ['id'];

    public function items()
    {
        return $this->hasMany('App\Eloquent\Procurement\Ticket\Item');
    }
}
