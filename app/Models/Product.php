<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $table = 'products';
    protected $fillable = ['lab_id', 'product_name', 'description'];

    public function lab()
    {
        return $this->belongsTo(Lab::class);
    }
}
