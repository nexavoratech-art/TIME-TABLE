<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class Department extends Model
{
    use HasFactory;

    protected $table = 'department';

    protected $primaryKey = 'dept_id';

    public $timestamps = false;

    protected $fillable = [
        'dept_id',
        'dept_code',
        'dept_name',
        'is_active',
    ];

    protected function casts(): array
    {
        return ['is_active' => 'boolean'];
    }

    public function programs(): HasMany
    {
        return $this->hasMany(Program::class, 'dept_id', 'dept_id');
    }

    public function instructors(): HasMany
    {
        return $this->hasMany(Instructor::class, 'dept_id', 'dept_id');
    }

    public function courses(): HasManyThrough
    {
        return $this->hasManyThrough(Course::class, Program::class, 'dept_id', 'program_id', 'dept_id', 'program_id');
    }
}
