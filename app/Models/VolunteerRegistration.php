<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VolunteerRegistration extends Model
{
    protected $guarded = [];

    public function task()
    {
        return $this->belongsTo(VolunteerTask::class, 'volunteer_task_id');
    }
}
