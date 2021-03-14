<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\SenderProfile
 *
 * @property int $id
 * @property int $user_id
 * @property string $name
 * @property string $phone
 * @property int $postcode
 * @property string $address
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $deleted_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SenderProfile newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SenderProfile newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SenderProfile query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SenderProfile whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SenderProfile whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SenderProfile whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SenderProfile whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SenderProfile whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SenderProfile wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SenderProfile wherePostcode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SenderProfile whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SenderProfile whereUserId($value)
 * @mixin \Eloquent
 */
class SenderProfile extends Model
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
