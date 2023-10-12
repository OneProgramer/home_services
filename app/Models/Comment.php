<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Comment extends Model
{
    use HasFactory;

    protected $fillable = [
        'accept',
        'description',
        'price',
        'days',
        'address',
        'worker_id',
        'job_id',
    ];

    public function job(): BelongsTo
    {
        return $this->belongsTo(Job::class);
    }
}
