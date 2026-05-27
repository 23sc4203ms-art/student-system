<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Degree extends Model
{
    protected $fillable = [
        'name',
        'description',
    ];

    public function students(): HasMany
    {
        return $this->hasMany(Student::class, 'degree_id');
    }
}
