<?php

namespace App\Eloquent\Procurement\Item;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Eloquent\Procurement\Item\ExtraService
 *
 * @property int $id
 * @property string $name
 * @property int $price
 * @property bool $show
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property string $deleted_at
 * @method static \Illuminate\Database\Query\Builder|\App\Eloquent\Procurement\Item\ExtraService whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Eloquent\Procurement\Item\ExtraService whereName($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Eloquent\Procurement\Item\ExtraService wherePrice($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Eloquent\Procurement\Item\ExtraService whereShow($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Eloquent\Procurement\Item\ExtraService whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Eloquent\Procurement\Item\ExtraService whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Eloquent\Procurement\Item\ExtraService whereDeletedAt($value)
 * @mixin \Eloquent
 */
class ExtraService extends Model
{
    use SoftDeletes;

    protected $table = 'procurement_item_extra_services';

    protected $guarded = ['id'];
}
