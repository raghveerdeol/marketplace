<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Address extends Model
{
    use SoftDeletes;

    /**
         * The table associated with the model.
         *
         * @var string
     */
    protected $table = 'addresses';

    protected $attributes = [
        'address',
        'cap',
        'district',
        'notes',
        'city_id',
        'user_id',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    protected $casts = [
        'address' => 'string',
        'cap' => 'string',
        'district' => 'string',
        'notes' => 'string',
        'city_id' => 'integer',
        'user_id' => 'integer',
        'region_id' => 'integer',
        'created_by' => 'integer',
        'updated_by' => 'integer',
        'deleted_by' => 'integer',
    ];
}
