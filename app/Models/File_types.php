<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class File_types extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'file_types';

    protected $fillable = [

        'id',
        'file_type',
        'created_at',
        'updated_at'

    ];


}