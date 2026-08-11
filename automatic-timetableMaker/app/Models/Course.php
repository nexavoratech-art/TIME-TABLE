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
    public $timestamps = false;

    protected $fillable = [
        'course_code',
        'course_name',
        'hours_per_week',
        'program_id',
    ];
}
