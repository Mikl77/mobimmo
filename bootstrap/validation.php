<?php

use App\Models\User;
use Cartalyst\Sentinel\Native\Facades\Sentinel;
use Valitron\Validator;

Validator::addRule('emailIsUnique',function ($field, $value, $params, $fields){

    $user = User::where('email', $value)
        ->where('email', '!=', Sentinel::check()->email ?? null)
        ->first();
    if($user){
        return false;
    }
    return true;
}, ' déjà utilisé');


Validator::addRule('currentPassword',function ($field, $value, $params, $fields){
   return Sentinel::getUserRepository()->validateCredentials(
       Sentinel::check(),
       ['password'=>$value]
   );
}, 'est faux');