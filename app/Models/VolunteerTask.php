<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VolunteerTask extends Model
{
    public function registrations()
    {
        return $this->hasMany(VolunteerRegistration::class);
    }

    public function isFull()
    {
        return $this->registrations()->count() >= $this->capacity;
    }

    public function isFinished()
    {
        $endDate = $this->end_date ?: $this->task_date;
        return \Carbon\Carbon::parse($endDate)->isPast();
    }
}
