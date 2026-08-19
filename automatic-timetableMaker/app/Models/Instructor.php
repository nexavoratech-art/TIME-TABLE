<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class, 'dept_id', 'dept_id');
    }
}
