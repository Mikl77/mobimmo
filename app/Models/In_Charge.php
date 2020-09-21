<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class In_Charge extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'in_charge';

    protected $fillable = [

        'id',
        'estate_id',
        'user_id',
        'date_from',
        'date_to',
        'created_at',
        'updated_at'

    ];


}