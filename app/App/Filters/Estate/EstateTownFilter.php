<?php


namespace App\App\Filters\Estate;


use App\App\Filters\FilterAbstract;
use App\Models\Estate_Status;
use Illuminate\Database\Eloquent\Builder;

class EstateTownFilter extends FilterAbstract
{
    public function filter(Builder $builder, $value){

        if($value === false){
            return $builder;
        }
        return $builder->where('estate_town_id','=', $value);
    }

}