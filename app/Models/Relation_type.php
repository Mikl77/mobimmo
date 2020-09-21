<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class Relation_type extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'relation_type';

    protected $fillable = [

        'relation_type_id',
        'relation_type_desc',
        'created_at',
        'updated_at'

    ];


}