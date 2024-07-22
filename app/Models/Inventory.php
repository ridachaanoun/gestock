<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inventory extends Model
{
    use HasFactory;

    protected $fillable = [
        'location',
        'capacity',
        'current_stock',
    ];

    public function products()
    {
        return $this->belongsToMany(Product::class);
    }
}

