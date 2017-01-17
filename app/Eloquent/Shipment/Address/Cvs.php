<?php

namespace App\Eloquent\Shipment\Address;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Eloquent\Shipment\Address\Cvs
 *
 * @property-read \App\Eloquent\Shipment\Address\Request $request
 * @mixin \Eloquent
 * @property int $id
 * @property int $request_id
 * @property string $receiver
 * @property string $phone
 * @property string $vendor
 * @property int $store
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property string $deleted_at
 * @method static \Illuminate\Database\Query\Builder|\App\Eloquent\Shipment\Address\Cvs whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Eloquent\Shipment\Address\Cvs whereRequestId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Eloquent\Shipment\Address\Cvs whereReceiver($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Eloquent\Shipment\Address\Cvs wherePhone($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Eloquent\Shipment\Address\Cvs whereVendor($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Eloquent\Shipment\Address\Cvs whereStore($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Eloquent\Shipment\Address\Cvs whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Eloquent\Shipment\Address\Cvs whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Eloquent\Shipment\Address\Cvs whereDeletedAt($value)
 */
class Cvs extends Model
{
    use SoftDeletes;

    /**
     * Table name.
     *
     * @var string
     */
    protected $table = 'addresses_cvs';

    /**
     * Columns that can not be modified.
     *
     * @var array
     */
    protected $guarded = ['id'];

    /**
     * Enable timestamp columns.
     *
     * @var bool
     */
    public $timestamps = true;

    /**
     * Get the address request.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function request()
    {
        return $this->belongsTo('App\Eloquent\Shipment\Address\Request');
    }
}
