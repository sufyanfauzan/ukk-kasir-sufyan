<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SaleDetail extends Model
{
    use HasFactory;
    protected $table = 'sale_details';
    protected $primaryKey = 'id';
    protected $fillable = [
        'sale_id',
        'product_id',
        'product_qty',
        'product_price',
        'total_price',
    ];
}
