<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class FruitItem extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
    */
    protected $fillable = [
        'name', 'category_id', 'price', 'unit'     
    ];

    protected $sortable = [
        'created_at'
     ];
 
    protected $casts = [
         'created_at' => 'datetime:Y-m-d h:i A',
         'updated_at' => 'datetime:Y-m-d h:i A',
    ];

    public function scopeFilter(Builder $query, array $filters): Builder
    {     

        $query->when(
          $filters['name'] ?? false,
          fn ($query, $value) => $query->where('name','like', '%'.$value.'%')          
        ); 
              
        return $query->orderBy('created_at', 'desc');
    }


    public function category(): BelongsTo
    {
        return $this->belongsTo(FruitCategory::class, 'category_id');
    }
}
