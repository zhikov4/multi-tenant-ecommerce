<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    // Pastikan bener-bener bersih, gak ada $connection di sini!
    protected $fillable = ['name', 'description', 'stock', 'price', 'status'];
}