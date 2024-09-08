<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Homework extends Model
{
    use HasFactory;

    protected $table = 'homeworks';

    protected $fillable = ['student_id', 'title', 'description', 'due_date'];

    public function student(): BelongsTo
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    public function marks(): HasMany
    {
        return $this->hasMany(Mark::class);
    }

    public function studentHomeworks(): HasMany
    {
        return $this->hasMany(StudentHomework::class);
    }

}
