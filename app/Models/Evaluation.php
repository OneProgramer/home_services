<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Evaluation extends Model
{
    use HasFactory;
    protected $fillable = ['assess','stars','job_id'];

    public function job(): BelongsTo
    {
        return $this->belongsTo(Job::class);
    }
}
