<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Violation extends Model
{
    protected $fillable = [
        'name',
        'first_offense',
        'second_offense',
        'third_offense',
        'penalty',
        'violation_category_id',
    ];

    public function category()
    {
        return $this->belongsTo(ViolationCategory::class, 'violation_category_id');
    }
}
