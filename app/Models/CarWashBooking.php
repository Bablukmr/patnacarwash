<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CarWashBooking extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'email',
        'phone',
        'car_model',
        'service_type',
        'preferred_date',
        'preferred_time',
        'address',
        'employee_id' // Ensure this exists in your table
    ];

    // Relationship to the assigned employee
    public function employee()
    {
        return $this->belongsTo(User::class, 'employee_id');
    }

    // Relationship to the work assignment
    public function workAssignment()
    {
        return $this->hasOne(WorkAssignment::class, 'booking_id');
    }
}
