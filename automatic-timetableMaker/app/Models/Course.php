<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasFactory;

    protected $table = 'courses';
    protected $primaryKey = 'course_code';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    protected $fillable = ['course_code', 'course_name', 'hours_per_week', 'program_id', 'instr_id', 'year_of_study'];

    public function instructor()
    {
        return $this->belongsTo(Instructor::class, 'instr_id', 'instr_id');
    }
}
