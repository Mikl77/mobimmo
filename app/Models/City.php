<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'City';

    protected $fillable = [

        'id',
        'city_name',
        'country_id',
        'created_at',
        'updated_at'

    ];


}