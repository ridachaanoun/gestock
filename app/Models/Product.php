<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'category_id',
        'supplier_id',
        'quantity',
        'price',
        'user_id',
        'image'

    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function inventories()
    {
        return $this->belongsToMany(Inventory::class);
    }
        // Accessor to get the full URL of the image
        public function getImageUrlAttribute()
        {
            return $this->image ? Storage::url($this->image) : null;
        }
}

