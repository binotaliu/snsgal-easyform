<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Models\AddressTicket
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
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AddressTicket archived($archived = true)
 * @method static bool|null forceDelete()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AddressTicket newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AddressTicket newQuery()
 * @method static \Illuminate\Database\Query\Builder|\App\Models\AddressTicket onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AddressTicket query()
 * @method static bool|null restore()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AddressTicket whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AddressTicket whereAddressType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AddressTicket whereArchived($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AddressTicket whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AddressTicket whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AddressTicket whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AddressTicket whereExported($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AddressTicket whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AddressTicket whereReceiverName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AddressTicket whereReceiverPhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AddressTicket whereResponded($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AddressTicket whereRespondedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AddressTicket whereShipmentStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AddressTicket whereShipmentTicketId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AddressTicket whereShipmentValidation($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AddressTicket whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AddressTicket whereToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AddressTicket whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\AddressTicket withTrashed()
 * @method static \Illuminate\Database\Query\Builder|\App\Models\AddressTicket withoutTrashed()
 * @mixin \Eloquent
 */
class AddressTicket extends Model
{
    use SoftDeletes;
    use HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'address' => 'json',
    ];

    protected $dates = ['expired_at', 'deleted_at'];

    public function scopeArchived(Builder $query, bool $archived = true): Builder
    {
        return $query->where('archived', $archived);
    }
}
