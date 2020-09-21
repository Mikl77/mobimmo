<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'messages';

    protected $fillable = [

        'id',
        'sender_id',
        'receiver_id',
        'content',
        'message_status_id',
        'message_priority_id',
        'estate_id',
        'created_at',
        'updated_at'

    ];


}