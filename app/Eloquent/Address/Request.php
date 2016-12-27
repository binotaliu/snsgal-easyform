<?php

namespace App\Eloquent\Address;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Eloquent\Address\Request
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
 * @method static \Illuminate\Database\Query\Builder|\App\Eloquent\Address\Request whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Eloquent\Address\Request whereTitle($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Eloquent\Address\Request whereDescription($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Eloquent\Address\Request whereToken($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Eloquent\Address\Request whereAddressType($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Eloquent\Address\Request whereExpiredAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Eloquent\Address\Request whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Eloquent\Address\Request whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Eloquent\Address\Request whereDeletedAt($value)
 * @property bool $responded
 * @method static \Illuminate\Database\Query\Builder|\App\Eloquent\Address\Request whereResponded($value)
 * @property int $exported
 * @method static \Illuminate\Database\Query\Builder|\App\Eloquent\Address\Request whereExported($value)
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
     * Get the address
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function address() {
        switch ($this->address_type) {
            case 'cvs':
                return $this->hasOne('App\Eloquent\Address\Cvs');
                break;
            case 'standard':
                return $this->hasOne('App\Eloquent\Address\Standard');
                break;
        }
    }
}
