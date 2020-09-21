<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class Bathroom_number extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'bathrooms_number';

    protected $fillable = [

        'id',
        'bathrooms_number',
        'created_at',
        'updated_at'

    ];


}