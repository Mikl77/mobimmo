<?php


namespace App\App;



use App\Models\Message;

class ExchangeManagerInterface
{

    //recupere les messages non lus de l utilisateur connecte
    public function getUserMessages($user_id,$message_status_id){
        return Message::where('receiver_id','=',$user_id)
                        ->where('message_status_id','=',$message_status_id)
                        ->Join('client','client.id','=','messages.sender_id')
                        ->get();
    }

}