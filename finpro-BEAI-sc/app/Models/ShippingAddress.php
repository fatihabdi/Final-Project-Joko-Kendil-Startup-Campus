<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShippingAddress extends Model
{
    use HasFactory;
    protected $table = 'shipping_address';
    protected $fillable = [
        'id',
        'user_id',
        'name',
        'phone_number',
        'address',
        'city'
    ];
}
