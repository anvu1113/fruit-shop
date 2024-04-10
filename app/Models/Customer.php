<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Customer extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
    */
    protected $fillable = [
        'name', 'phone_number', 'address'
    ];

    protected $sortable = [
        'created_at'
     ];
 
    protected $casts = [
         'created_at' => 'datetime:Y-m-d h:i A',
         'updated_at' => 'datetime:Y-m-d h:i A',
    ];
}
