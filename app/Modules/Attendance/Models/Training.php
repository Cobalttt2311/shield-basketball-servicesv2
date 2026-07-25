<?php

namespace App\Modules\Attendance\Models;

use App\Modules\Admin\Models\Coach;
use App\Modules\Admin\Models\Group;
use Illuminate\Database\Eloquent\Model;

class Training extends Model
{
    protected $fillable = [
        'title',
        'date',
        'group_id',
        'start_time',
        'end_time',
        'is_finalized',
        'coach_attendance_status',
        'recorded_by_coach_id',
    ];

    protected $casts = [
        'is_finalized' => 'boolean',
    ];

    public function group()
    {
        return $this->belongsTo(Group::class);
    }

    public function recordedBy()
    {
        return $this->belongsTo(Coach::class, 'recorded_by_coach_id');
    }

    public function playerAttendances()
    {
        return $this->hasMany(PlayerAttendance::class);
    }
}
