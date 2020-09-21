<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class FrPostalCode extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'fr_postal_code';

    protected $fillable = [

        'id',
        'district_name',
        'postal_code',
        'libelle',
        'lat',
        'lng',
        'created_at',
        'updated_at'

    ];


}