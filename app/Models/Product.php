<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use SoftDeletes;

    /**
         * The table associated with the model.
         *
         * @var string
     */
    protected $table = 'products';

    protected $fillable = [
        'name',
        'description',
        'price',
        'quantity',
        'code',
        'visible_in_store',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    protected $casts = [
        'name' => 'string',
        'description' => 'string',
        'price' => 'decimal',
        'quantity' => 'integer',
        'code' => 'string',
        'visible_in_store' => 'boolean',
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

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class, 'products_tags', 'product_id', 'tag_id');
    }

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class, 'categories_products', 'product_id', 'category_id');
    }
}
