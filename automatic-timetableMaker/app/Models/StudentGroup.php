<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentGroup extends Model
{
    use HasFactory;

    protected $table = 'student_groups';
    protected $primaryKey = 'group_id';
    public $timestamps = false;

    protected $fillable = ['group_name', 'student_count', 'program_id', 'year_of_study'];
}
