<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Student extends Model
{
    protected $fillable = [
        'Fname',
        'Mname',
        'Lname',
        'Address',
        'Email',
        'Contactno',
        'degree_id',
        'user_account_id'
    ];
    public function courses()
    {
        return $this->belongsToMany(Course::class, 'course__students');
    }

    public function degree(): BelongsTo
    {

        return $this->belongsTo(Degree::class,'degree_id');
    }

    public function UserAccount (){
        return $this->belongsTo(UserAccount::class, 'user_account_id');
    }
}
