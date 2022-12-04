<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductImages extends Model
{
    use HasFactory;
    protected $table = 'product_image';
    protected $fillable = [
        'id',
        'product_id',
        'image_title',
        'image_file'
    ];
}
