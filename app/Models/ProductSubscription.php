<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductSubscription extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'product_id', 'grind_size', 'weight', 'week', 'month', 'status','price','quantity'
    ];
    
    
    public function user()
{
    return $this->belongsTo(User::class, 'user_id');
}

public function product()
{
    return $this->belongsTo(Product::class, 'product_id');
}
public function address()
{
    return $this->belongsTo(Address::class, 'address_id');
}
}
