<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class Building_type extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'building_type';

    protected $fillable = [

        'buiding_type_id',
        'building_type_designation',
        'created_at',
        'updated_at'

    ];


}