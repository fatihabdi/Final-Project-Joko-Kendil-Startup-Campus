<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Balance extends Model
{
    use HasFactory;
    protected $table = 'balances';
    protected $fillable = [
        'id',
        'user_id',
        'balance'
    ];
}
