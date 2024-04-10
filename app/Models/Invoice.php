<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Invoice extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
    */
    protected $fillable = [
        'customer_id'   
    ];

    protected $sortable = [
        'created_at'
     ];
 
    protected $casts = [
         'created_at' => 'datetime:Y-m-d h:i A',
         'updated_at' => 'datetime:Y-m-d h:i A',
    ];

    protected $appends = [
        'total_price_formatted'
    ];


    public function getTotalPriceFormattedAttribute()
    {   
       
        $totalPrice = $this->invoiceDetail->sum('total_price');      
        return  $totalPrice > 0 ? (float)$totalPrice : 0;
    }

    public function scopeFilter(Builder $query, array $filters): Builder
    {     

        $query->when(
          $filters['customer_id'] ?? false,
          fn ($query, $value) => $query->where('customer_id','=', $value)          
        ); 
              
        return $query->orderBy('created_at', 'desc');
    }


    public function invoiceDetail(): HasMany 
    {
        return $this->hasMany(
            InvoiceDetail::class,
            'invoice_id'
        );
    }
}
