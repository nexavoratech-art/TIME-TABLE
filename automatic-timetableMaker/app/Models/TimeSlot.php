<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TimeSlot extends Model
{
    use HasFactory;

    protected $table = 'time_slots';

    protected $primaryKey = 'slot_id';

    public $timestamps = false;

    protected $fillable = [
        'day_of_week',
        'start_time',
        'end_time',
    ];
}
