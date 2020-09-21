<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class Rooms_number extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'rooms_number';

    protected $fillable = [

        'id',
        'rooms_number',
        'created_at',
        'updated_at'

    ];


}