<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InstructorAvailability extends Model
{
    use HasFactory;

    protected $table = 'instructor_availabilities';
    protected $primaryKey = 'avail_id';
    public $timestamps = false;

    protected $fillable = [
        'instr_id',
        'slot_id',
        'is_available',
    ];
}
