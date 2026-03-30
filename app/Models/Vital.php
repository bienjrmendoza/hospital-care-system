<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vital extends Model
{
    protected $fillable = [
        'user_id',
        'date',
        'blood_pressure',
        'heart_rate',
        'temperature',
        'respiratory_rate',
        'oxygen_saturation',
        'weight',
        'height',
        'bmi',
        'notes',
        'initial_assessment',
        'diagnostic',
        'medication',
        'treatment',
        'diet',
        'remarks',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}