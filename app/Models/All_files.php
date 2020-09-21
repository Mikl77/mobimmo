<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class All_files extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'all_files';

    protected $fillable = [

        'id',
        'file_type_id',
        'filename',
        'user_id',
        'estate_id',
        'created_at',
        'updated_at'

    ];


}