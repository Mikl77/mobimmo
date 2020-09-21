<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class Relation_status extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'relation_status';

    protected $fillable = [

        'relation_status_id',
        'relation_status_desc',
        'created_at',
        'updated_at'

    ];


}