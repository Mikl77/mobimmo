<?php

namespace App\Models;

use Cartalyst\Sentinel\Users\EloquentUser;

class User extends EloquentUser
{
    public function fullName(){

        return trim($this->first_name . ' ' . $this->last_name);
    }

    public function publicName(){
        return trim($this->first_name) . ' ' . substr($this->last_name,0,1);
    }



}
