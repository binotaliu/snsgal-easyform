<?php

namespace App\Eloquent\Address;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

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
}
