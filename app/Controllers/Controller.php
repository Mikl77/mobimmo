<?php

namespace App\Controllers;

use App\Exceptions\ValidationException;
use Psr\Http\Message\ServerRequestInterface;
use Valitron\Validator;

class Controller
{

 public function validate(ServerRequestInterface $request,array $rules = []){

     $validator =  new Validator(
         $params = $request->getParsedBody()
     );

     $validator->mapFieldsRules($rules);

    if(!$validator->validate()){
       throw new ValidationException(
           $validator->errors(),
           $request->getServerParams()['HTTP_REFERER']
       );
    }

    return $params;

 }


}
