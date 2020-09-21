<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class Estate_manager extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'estate_manager_menu';

    protected $fillable = [

        'id',
        'title',
        'description',
        'link',
        'image',
        'created_at',
        'updated_at'

    ];


}