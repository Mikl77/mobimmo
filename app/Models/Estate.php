<?php


namespace App\Models;


use App\App\Filters\Estate\EstateFilters;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Estate extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'estate';

    protected $fillable = [

        'estate_id',
        'estate_name',
        'estate_address',
        'estate_postal_code',
        'estate_town',
        'estate_town_id',
        'city_id',
        'estate_type_id',
        'floor_space',
        'current_rent',
        'current_price',
        'number_of_rooms',
        'balconies_space',
        'number_of_bedrooms',
        'number_of_bathrooms',
        'number_of_garages',
        'estate_parking_type_id',
        'field_space',
        'estate_description',
        'estate_status_id',
        'estate_location_id',
        'estate_main_picture_filename',
        'estate_reference',
        'estate_building_year_id',
        'estate_lat',
        'estate_lng',
        'created_at',
        'updated_at'

    ];

    //tentative de systeme de filtre des estates
    public function scopeFilter(Builder $builder, $request, array $filters = []){

        return (new EstateFilters($request))->add($filters)->filter($builder);
    }


}