<?php

namespace App\Eloquent\Procurement\Ticket;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Eloquent\Procurement\Ticket\Item
 *
 * @property int $id
 * @property int $ticket_id
 * @property string $title
 * @property float $price
 * @property string $note
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property string $deleted_at
 * @property-read \App\Eloquent\Procurement\Ticket $ticket
 * @method static \Illuminate\Database\Query\Builder|\App\Eloquent\Procurement\Ticket\Item whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Eloquent\Procurement\Ticket\Item whereTicketId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Eloquent\Procurement\Ticket\Item whereTitle($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Eloquent\Procurement\Ticket\Item wherePrice($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Eloquent\Procurement\Ticket\Item whereNote($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Eloquent\Procurement\Ticket\Item whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Eloquent\Procurement\Ticket\Item whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Eloquent\Procurement\Ticket\Item whereDeletedAt($value)
 * @mixin \Eloquent
 * @property string $url
 * @method static \Illuminate\Database\Query\Builder|\App\Eloquent\Procurement\Ticket\Item whereUrl($value)
 * @property int $status
 * @method static \Illuminate\Database\Query\Builder|\App\Eloquent\Procurement\Ticket\Item whereStatus($value)
 * @property-read \App\Eloquent\Procurement\Item\Category $category
 * @property int $category_id
 * @method static \Illuminate\Database\Query\Builder|\App\Eloquent\Procurement\Ticket\Item whereCategoryId($value)
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Eloquent\Procurement\Ticket\Item\ExtraService[] $extraServices
 */
class Item extends Model
{
    use SoftDeletes;

    /**
     * @var string
     */
    protected $table = 'procurement_ticket_items';

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

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function category()
    {
        return $this->belongsTo('App\Eloquent\Procurement\Item\Category', 'category_id', 'id');
    }

    public function extraServices()
    {
        return $this->hasMany('App\Eloquent\Procurement\Ticket\Item\ExtraService');
    }
}
