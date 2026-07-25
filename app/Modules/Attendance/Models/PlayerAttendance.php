<?php

namespace App\Modules\Attendance\Models;

use App\Modules\Admin\Models\Player;
use Illuminate\Database\Eloquent\Model;

class PlayerAttendance extends Model
{
    protected $fillable = [
        'training_id',
        'player_id',
        'status',
        'description',
        'attended_at',
    ];

    protected $casts = [
        'attended_at' => 'datetime',
    ];

    public function training()
    {
        return $this->belongsTo(Training::class);
    }

    public function player()
    {
        return $this->belongsTo(Player::class);
    }
}
