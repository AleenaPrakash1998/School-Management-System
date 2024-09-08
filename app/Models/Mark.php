<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Mark extends Model
{
    use HasFactory;

    protected $table = 'marks';

    protected $fillable = ['student_id', 'homework_id', 'marks'];

    public function student(): BelongsTo
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    public function homework(): BelongsTo
    {
        return $this->belongsTo(Homework::class, 'homework_id');
    }
}
