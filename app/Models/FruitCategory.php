<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class FruitCategory extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
    */
    protected $fillable = [
        'name',       
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

    public function items(): HasMany 
    {
        return $this->hasMany(
            FruitItem::class,
            'category_id'
        );
    }
}
