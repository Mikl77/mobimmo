<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class Legal_status extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'legal_status';

    protected $fillable = [

        'legal_status_id',
        'legal_status_designation',
        'created_at',
        'updated_at'

    ];


}