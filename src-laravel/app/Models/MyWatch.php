<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MyWatch extends Model
{
    use SoftDeletes; // マイグレーションで softDeletes() を入れたので、これも追加します

    // ULID設定
    protected $keyType = 'string';
    public $incrementing = false;

    // 保存を許可するカラム
    protected $fillable = [
        'id',
        'brand_id',
        'model_name',
        'reference_number',
        'serial_number',
        'purchase_price',
        'purchase_date',
        'image_path',
        'raw_image_url',
    ];
}
