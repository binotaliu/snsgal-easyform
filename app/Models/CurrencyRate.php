<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\CurrencyRate
 *
 * @property int $id
 * @property string $currency
 * @property float $rate
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CurrencyRate latestOf($code)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CurrencyRate newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CurrencyRate newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CurrencyRate query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CurrencyRate whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CurrencyRate whereCurrency($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CurrencyRate whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CurrencyRate whereRate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CurrencyRate whereUpdatedAt($value)
 * @mixin \Eloquent
 */
final class CurrencyRate extends Model
{
    protected $fillable = [
        'currency',
        'rate',
    ];

    public function scopeLatestOf(Builder $query, string $code)
    {
        return $query->where('currency', $code)->latest()->first();
    }
}
