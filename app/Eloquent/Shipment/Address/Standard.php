<?php

namespace App\Eloquent\Shipment\Address;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Eloquent\Shipment\Address\Standard
 *
 * @property-read \App\Eloquent\Shipment\Address\Request $request
 * @mixin \Eloquent
 * @property int $id
 * @property int $request_id
 * @property string $receiver
 * @property int $postcode
 * @property string $county
 * @property string $city
 * @property string $address1
 * @property string $address2
 * @property string $phone
 * @property int $time
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property string $deleted_at
 * @method static \Illuminate\Database\Query\Builder|\App\Eloquent\Shipment\Address\Standard whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Eloquent\Shipment\Address\Standard whereRequestId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Eloquent\Shipment\Address\Standard whereReceiver($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Eloquent\Shipment\Address\Standard wherePostcode($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Eloquent\Shipment\Address\Standard whereCounty($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Eloquent\Shipment\Address\Standard whereCity($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Eloquent\Shipment\Address\Standard whereAddress1($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Eloquent\Shipment\Address\Standard whereAddress2($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Eloquent\Shipment\Address\Standard wherePhone($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Eloquent\Shipment\Address\Standard whereTime($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Eloquent\Shipment\Address\Standard whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Eloquent\Shipment\Address\Standard whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Eloquent\Shipment\Address\Standard whereDeletedAt($value)
 */
class Standard extends Model
{
    use SoftDeletes;

    /**
     * Table name.
     *
     * @var string
     */
    protected $table = 'addresses_standard';

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
