<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function orderitems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }
    public function scopeCategoryId(Builder $query, string $categoryId): Builder
    {
        return $query->where('category_id', '=', $categoryId);
    }
    
}
