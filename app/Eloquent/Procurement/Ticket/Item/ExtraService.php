<?php

namespace App\Eloquent\Procurement\Ticket\Item;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ExtraService extends Model
{
    protected $table = 'procurement_ticket_item_extra_services';

    protected $guarded = ['id'];

    /**
     * @return BelongsTo
     */
    public function item(): BelongsTo
    {
        return $this->belongsTo('App\Eloquent\Procurement\Ticket\Item');
    }
}
