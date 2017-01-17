<?php

namespace App\Eloquent\Currency;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Eloquent\Currency\Rate
 *
 * @property int $id
 * @property string $currency
 * @property float $rate
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @method static \Illuminate\Database\Query\Builder|\App\Eloquent\Currency\Rate whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Eloquent\Currency\Rate whereCurrency($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Eloquent\Currency\Rate whereRate($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Eloquent\Currency\Rate whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Eloquent\Currency\Rate whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Rate extends Model
{
    /**
     * @var string
     */
    protected $table = 'currency_rates';

    /**
     * @var string
     */
    protected $guarded = 'id';
}
