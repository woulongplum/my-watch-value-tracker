<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MarketSnapshot extends Model
{
    protected $keyType = 'string';
    public $incrementing = false;

    // 一括保存を許可するカラム
    protected $fillable = [
        'id', 
        'my_watch_id', 
        'jp_market_average', 
        'usd_jpy_rate', 
        'fetched_at'
    ];
}
