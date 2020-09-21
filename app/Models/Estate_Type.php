<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class Estate_Type extends Model
{
    protected $table = 'estate_type';

    protected $fillable = [
        'id',
        'type_name',
        'created_at',
        'updated_at'
    ];

}