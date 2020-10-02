<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class Irl extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'irl';

    protected $fillable = [

        'irl_id',
        'irl_time',
        'irl_value',
        'created_at',
        'updated_at'

    ];


}