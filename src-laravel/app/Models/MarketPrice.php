<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MarketPrice extends Model
{
    protected $keyType ='string';
    public $incrementing = false;

    protected $fillable = [
        'id',
        'brand_id',
        'ref_number',
        'price',
        'model_name',
        'item_url',
        'image_url',
        'source',
        'item_condition'
    ];

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }
}
