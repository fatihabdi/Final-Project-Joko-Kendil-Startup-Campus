<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;
    protected $table = 'orders';
    protected $fillable = [
        'id',
        'users_id',
        'shipping_address_id',
        'status',
        'shipping_method',
        'shipping_price',
        'total'
    ];
}
