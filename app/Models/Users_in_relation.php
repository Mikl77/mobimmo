<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class Users_in_relation extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'users_in_relation';

    protected $fillable = [

        'users_in_relation_id',
        'first_user_id',
        'users_in_contact_id',
        'relation_type_id',
        'relation_status_id',
        'created_by_user_id',
        'created_at',
        'updated_at'

    ];

}