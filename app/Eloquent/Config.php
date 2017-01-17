<?php

namespace App\Eloquent;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Eloquent\Config
 *
 * @property string $key
 * @property string $value
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @method static \Illuminate\Database\Query\Builder|\App\Eloquent\Config whereKey($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Eloquent\Config whereValue($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Eloquent\Config whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Eloquent\Config whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Config extends Model
{
    /**
     * @var string
     */
    protected $table = 'configs';

    /**
     * @var array
     */
    protected $fillable = ['key', 'value'];

    /**
     * @var bool
     */
    public $incrementing = false;
}
