<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Lab extends Model
{
    protected $table = 'labs';
    protected $fillable = ['name', 'location'];

    public function items()
    {
        return $this->hasMany(Item::class);
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }
}
