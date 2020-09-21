<?php


namespace App\App;


use App\Models\User;
use App\Models\Users_in_relation;
use Cartalyst\Sentinel\Native\Facades\Sentinel;

class RelationManagerInterface
{
    //recupere les donnees des utilisateurs en relation suivant le type de relation envoye
    // par exemple $relation_type = 2 si on cherche les locataires geres
    public function getMyRelations($relation_type){
        $users_in_relation = Users_in_relation::where('first_user_id','=', Sentinel::check()->id)
            ->where('relation_type_id','=',$relation_type)
            ->first();
        if($users_in_relation){
            return $users_in_relation->users_in_contact_id;
        }
        return null;

    }

    // recupere les infos de l utilisateur et retourne l objet
    public function getUserDetaisl($id){
       return User::where('id','=', $id)
            ->first();
    }



}