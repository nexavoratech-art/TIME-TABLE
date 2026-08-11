<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Instructor extends Model
{
    use HasFactory;

    protected $table = 'instructors';
    protected $primaryKey = 'instr_id';
    public $timestamps = false;

    protected $fillable = [
        'instr_name',
        'dept_id',
    ];
}
