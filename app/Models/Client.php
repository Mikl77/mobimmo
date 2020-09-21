<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'Client';

    protected $fillable = [

        'id',
        'client_first_name',
        'client_last_name',
        'client_phone',
        'client_mail',
        'created_at',
        'updated_at'

    ];


}