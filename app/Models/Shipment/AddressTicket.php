<?php

namespace App\Models\Shipment;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Models\Shipment\AddressTicket
 *
 * @property int $id
 * @property string $title
 * @property string $description
 * @property string $token
 * @property string $address_type
 * @property array $address
 * @property string $receiver_name
 * @property string $receiver_phone
 * @property int $responded
 * @property int|null $exported
 * @property string|null $shipment_ticket_id
 * @property string|null $shipment_validation
 * @property int|null $shipment_status
 * @property int $archived
 * @property string|null $responded_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Shipment\AddressTicket archived($archived = true)
 * @method static bool|null forceDelete()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Shipment\AddressTicket newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Shipment\AddressTicket newQuery()
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Shipment\AddressTicket onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Shipment\AddressTicket query()
 * @method static bool|null restore()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Shipment\AddressTicket whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Shipment\AddressTicket whereAddressType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Shipment\AddressTicket whereArchived($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Shipment\AddressTicket whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Shipment\AddressTicket whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Shipment\AddressTicket whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Shipment\AddressTicket whereExported($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Shipment\AddressTicket whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Shipment\AddressTicket whereReceiverName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Shipment\AddressTicket whereReceiverPhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Shipment\AddressTicket whereResponded($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Shipment\AddressTicket whereRespondedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Shipment\AddressTicket whereShipmentStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Shipment\AddressTicket whereShipmentTicketId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Shipment\AddressTicket whereShipmentValidation($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Shipment\AddressTicket whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Shipment\AddressTicket whereToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Shipment\AddressTicket whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Shipment\AddressTicket withTrashed()
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Shipment\AddressTicket withoutTrashed()
 * @mixin \Eloquent
 */
class AddressTicket extends Model
{
    use SoftDeletes;

    /**
     * Table name.
     *
     * @var string
     */
    protected $table = 'address_tickets';

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

    protected $casts = [
        'address' => 'json',
    ];

    /**
     * Enable timestamps.
     *
     * @var bool
     */
    public $timestamps = true;

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
