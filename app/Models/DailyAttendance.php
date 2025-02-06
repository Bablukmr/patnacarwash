<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DailyAttendance extends Model
{
    protected $fillable = ['assignment_id', 'date', 'status', 'notes'];

    public function assignment()
    {
        return $this->belongsTo(RecurringAssignment::class);
    }
}
