<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class Parking_type extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'parking_type';

    protected $fillable = [

        'id',
        'parking_type_name',
        'created_at',
        'updated_at'

    ];


}