<?php

namespace App\Eloquent\Address;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

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
        return $this->belongsTo('App\Eloquent\Address\Request');
    }
}
