<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class Featured extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'featured';

    protected $fillable = [

        'id',
        'featured_name',
        'created_at',
        'updated_at'

    ];


}