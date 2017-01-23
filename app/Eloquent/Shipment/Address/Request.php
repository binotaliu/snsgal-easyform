<?php

namespace App\Eloquent\Shipment\Address;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Eloquent\Shipment\Address\Request
 *
 * @mixin \Eloquent
 * @property int $id
 * @property string $title
 * @property string $description
 * @property string $token
 * @property string $address_type
 * @property \Carbon\Carbon $expired_at
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property \Carbon\Carbon $deleted_at
 * @method static \Illuminate\Database\Query\Builder|\App\Eloquent\Shipment\Address\Request whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Eloquent\Shipment\Address\Request whereTitle($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Eloquent\Shipment\Address\Request whereDescription($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Eloquent\Shipment\Address\Request whereToken($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Eloquent\Shipment\Address\Request whereAddressType($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Eloquent\Shipment\Address\Request whereExpiredAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Eloquent\Shipment\Address\Request whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Eloquent\Shipment\Address\Request whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Eloquent\Shipment\Address\Request whereDeletedAt($value)
 * @property bool $responded
 * @method static \Illuminate\Database\Query\Builder|\App\Eloquent\Shipment\Address\Request whereResponded($value)
 * @property int $exported
 * @method static \Illuminate\Database\Query\Builder|\App\Eloquent\Shipment\Address\Request whereExported($value)
 * @property-read \App\Eloquent\Shipment\Address\Cvs $cvs_address
 * @property-read \App\Eloquent\Shipment\Address\Standard $standard_address
 * @property bool $archived
 * @method static \Illuminate\Database\Query\Builder|\App\Eloquent\Shipment\Address\Request whereArchived($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Eloquent\Shipment\Address\Request archived($archived = true)
 * @property string $shipment_ticket_id
 * @property int $shipment_status
 * @method static \Illuminate\Database\Query\Builder|\App\Eloquent\Shipment\Address\Request whereShipmentTicketId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Eloquent\Shipment\Address\Request whereShipmentStatus($value)
 * @property string $shipment_validation
 * @method static \Illuminate\Database\Query\Builder|\App\Eloquent\Shipment\Address\Request whereShipmentValidation($value)
 */
class Request extends Model
{
    use SoftDeletes;

    /**
     * Table name.
     *
     * @var string
     */
    protected $table = 'address_requests';

    /**
     * Define date columns
     *
     * @var array
     */
    protected $dates = ['expired_at', 'deleted_at'];

    /**
     * Make columns un-assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];

    /**
     * Enable timestamps.
     *
     * @var bool
     */
    public $timestamps = true;

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function cvs_address()
    {
        return $this->hasOne('App\Eloquent\Shipment\Address\Cvs');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function standard_address()
    {
        return $this->hasOne('App\Eloquent\Shipment\Address\Standard');
    }

    /**
     * @param Builder $query
     * @param bool $archived
     * @return Builder
     */
    public function scopeArchived(Builder $query, bool $archived = true): Builder
    {
        return $query->where('archived', $archived);
    }
}
