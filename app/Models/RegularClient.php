<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RegularClient extends Model
{
    protected $fillable = ['user_id', 'start_date', 'duration_months'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function assignments()
    {
        return $this->hasMany(RecurringAssignment::class);
    }
}
