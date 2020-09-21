<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class Estate_Status extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'estate_status';

    protected $fillable = [

        'id',
        'estate_status_name',
        'created_at',
        'updated_at'

    ];


}