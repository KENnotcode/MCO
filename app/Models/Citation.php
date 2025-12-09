<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Citation extends Model
{
    protected $fillable = [
        'user_id',
        'violation_id',
        'offense',
        'offense_level',
        'date_committed',
        'status',
        'paid_at',
    ];

    protected $casts = [
        'date_committed' => 'date',
        'paid_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function violation()
    {
        return $this->belongsTo(Violation::class);
    }
}
