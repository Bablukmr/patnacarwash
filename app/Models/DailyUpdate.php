<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DailyUpdate extends Model
{
    use HasFactory;

    protected $fillable = [
        'work_assignment_id',
        'date',
        'attendance',
        'cleaning_status',
        'daily_notes',
        'defects_found',
        'images'
    ];

    public function workAssignment()
    {
        return $this->belongsTo(WorkAssignment::class);
    }
}