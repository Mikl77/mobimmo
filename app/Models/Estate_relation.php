<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class Estate_relation extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'estate_relation';

    protected $fillable = [

        'estate_relation_id',
        'estate_id',
        'user_list_id',
        'relation_type_id',
        'relation_status_id',
        'created_by_user_id',
        'created_at',
        'updated_at'

    ];

}