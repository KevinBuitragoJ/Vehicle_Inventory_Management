<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vehicle extends Model
{
    #use HasFactory;
    
    protected $fillable = [
        'make',
        'license_plate',
        'color',
        'model',
        'purchase_date',
        'accident_report',
    ];
    
    protected $casts = [
        'purchase_date' => 'date',
        'accident_report' => 'boolean',
    ];
}
