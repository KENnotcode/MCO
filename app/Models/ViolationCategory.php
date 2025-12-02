<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ViolationCategory extends Model
{
    protected $fillable = ['name'];

    public function violations()
    {
        return $this->hasMany(Violation::class);
    }
}
