<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class Order_estate extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'order_estate';

    protected $fillable = [

        'id',
        'designation',
        'query_value',
        'order_description',
        'created_at',
        'updated_at'

    ];


}