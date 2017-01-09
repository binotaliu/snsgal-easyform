<?php

namespace App\Eloquent\Procurement;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Eloquent\Procurement\Ticket
 *
 * @property int $id
 * @property int $status
 * @property string $token
 * @property string $name
 * @property string $email
 * @property string $contact
 * @property string $note
 * @property float $rate
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property string $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Eloquent\Procurement\Ticket\Item[] $items
 * @method static \Illuminate\Database\Query\Builder|\App\Eloquent\Procurement\Ticket whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Eloquent\Procurement\Ticket whereStatus($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Eloquent\Procurement\Ticket whereToken($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Eloquent\Procurement\Ticket whereName($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Eloquent\Procurement\Ticket whereEmail($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Eloquent\Procurement\Ticket whereContact($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Eloquent\Procurement\Ticket whereNote($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Eloquent\Procurement\Ticket whereRate($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Eloquent\Procurement\Ticket whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Eloquent\Procurement\Ticket whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Eloquent\Procurement\Ticket whereDeletedAt($value)
 * @mixin \Eloquent
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Eloquent\Procurement\Ticket\JapanShipment[] $japanShipments
 */
class Ticket extends Model
{
    use SoftDeletes;

    /**
     * @var string
     */
    protected $table = 'procurement_tickets';

    /**
     * @var array
     */
    protected $guarded = ['id'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function items()
    {
        return $this->hasMany('App\Eloquent\Procurement\Ticket\Item');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function japanShipments()
    {
        return $this->hasMany('App\Eloquent\Procurement\Ticket\JapanShipment');
    }
}
