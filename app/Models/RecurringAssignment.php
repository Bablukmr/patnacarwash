<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RecurringAssignment extends Model
{
    protected $fillable = ['regular_client_id', 'employee_id', 'frequency', 'preferred_time'];

    public function regularClient()
    {
        return $this->belongsTo(RegularClient::class);
    }

    public function employee()
    {
        return $this->belongsTo(User::class, 'employee_id');
    }

    public function attendances()
    {
        return $this->hasMany(DailyAttendance::class, 'assignment_id');
    }
}
