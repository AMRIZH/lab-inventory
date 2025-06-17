<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ItemUnit extends Model
{
    protected $table = 'item_units';
    protected $fillable = ['item_id', 'code', 'condition'];

    public function item()
    {
        return $this->belongsTo(Item::class);
    }
}
