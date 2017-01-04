<?php

namespace App\Eloquent\User;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Eloquent\User\RequestProfile
 *
 * @property int $id
 * @property int $user
 * @property string $name
 * @property string $phone
 * @property int $postcode
 * @property string $address
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property string $deleted_at
 * @method static \Illuminate\Database\Query\Builder|\App\Eloquent\User\RequestProfile whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Eloquent\User\RequestProfile whereUser($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Eloquent\User\RequestProfile whereName($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Eloquent\User\RequestProfile wherePhone($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Eloquent\User\RequestProfile wherePostcode($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Eloquent\User\RequestProfile whereAddress($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Eloquent\User\RequestProfile whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Eloquent\User\RequestProfile whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Eloquent\User\RequestProfile whereDeletedAt($value)
 * @mixin \Eloquent
 * @property int $user_id
 * @method static \Illuminate\Database\Query\Builder|\App\Eloquent\User\RequestProfile whereUserId($value)
 */
class RequestProfile extends Model
{
    /**
     * The name of the table
     * @var string
     */
    protected $table = 'sender_profiles';

    /**
     * Fillable columns
     * @var String
     */
    protected $fillable = [
        'user_id',
        'name',
        'phone',
        'postcode',
        'address'
    ];
}
