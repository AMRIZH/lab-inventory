<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    protected $table = 'items';
    protected $fillable = ['lab_id', 'name', 'description'];

    public function lab()
    {
        return $this->belongsTo(Lab::class);
    }

    public function itemUnits()
    {
        return $this->hasMany(ItemUnit::class);
    }
}
