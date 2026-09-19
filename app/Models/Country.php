<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Country extends Model
{
    use SoftDeletes;

    /**
         * The table associated with the model.
         *
         * @var string
     */
    protected $table = 'countries';

    protected $attributes = [
        'name',
        'code',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    protected $casts = [
        'name' => 'string',
        'code' => 'string',
        'created_by' => 'integer',
        'updated_by' => 'integer',
        'deleted_by' => 'integer',
    ];

    
    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }

    public function updatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by', 'id');
    }

    public function deletedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'deleted_by', 'id');
    }

    public function regions(): HasMany
    {
        return $this->hasMany(Region::class, 'country_id', 'id');
    }
}
