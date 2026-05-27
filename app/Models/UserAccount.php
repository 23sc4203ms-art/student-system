<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;

class UserAccount extends Authenticatable
{

    protected $table = 'user_accounts';

    //
    protected $fillable = [
        'username',
        'email',
        'Password',
        'Role',
        'is_active',
        'is_temp_password',
        'remember_token',
    ];

    protected $hidden = [
        'Password',
    ];

    public function getAuthPassword()
    {
        return $this->Password;
    }

    public function student (){
        return $this->hasOne(Student::class, 'user_account_id');
    }

    public function teacher (){
        return $this->hasOne(Teacher::class, 'user_account_id');
    }


}

