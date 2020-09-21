<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class Building_year extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'building_year';

    protected $fillable = [

        'id',
        'building_year_name',
        'created_at',
        'updated_at'

    ];


}