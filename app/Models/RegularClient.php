<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class RegularClient extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'start_date', 'duration_months'];

    // Ensure start_date is treated as a Carbon date instance
    protected $casts = [
        'start_date' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function assignments()
    {
        return $this->hasMany(RecurringAssignment::class);
    }
}
