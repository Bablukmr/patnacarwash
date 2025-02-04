<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkAssignment extends Model
{
    use HasFactory;

    protected $fillable = [
        'booking_id',
        'employee_id',
        'assigned_by',
        'status',
        'location',
        'contact_number',
        'notes',
        'defects',
        'images'
    ];

    public function booking()
    {
        return $this->belongsTo(CarWashBooking::class);
    }

    public function employee()
    {
        return $this->belongsTo(User::class, 'employee_id');
    }

    public function assigner()
    {
        return $this->belongsTo(User::class, 'assigned_by');
    }

    public function dailyUpdates()
    {
        return $this->hasMany(DailyUpdate::class);
    }
    
}