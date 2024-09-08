<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Teacher extends Model
{
    use HasFactory;

    protected $table = 'teachers';

    protected $fillable = ['user_id', 'qualification'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
