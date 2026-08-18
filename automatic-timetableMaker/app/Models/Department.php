<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    use HasFactory;

    protected $table = 'department';
    protected $primaryKey = 'dept_id';
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'dept_id',
        'dept_name',
    ];
}
