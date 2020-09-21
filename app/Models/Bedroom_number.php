<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class Bedroom_number extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'bedrooms_number';

    protected $fillable = [

        'id',
        'bedrooms_number',
        'created_at',
        'updated_at'

    ];


}