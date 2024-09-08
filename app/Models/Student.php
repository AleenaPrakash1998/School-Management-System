<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Student extends Model
{
    use HasFactory;

    protected $table = 'students';

    protected $fillable = ['user_id', 'grade'];

    public function homeworks(): HasMany
    {
        return $this->hasMany(Homework::class, 'student_id');
    }

    public function marks(): HasMany
    {
        return $this->hasMany(Mark::class, 'student_id');
    }
}
