<?php


namespace App\App\Filters\Estate;


use Illuminate\Database\Eloquent\Builder;

class SurfaceMax
{
    public function filter(Builder $builder, $value){

        if($value === false){
            return $builder;
        }
        return $builder->where('floor_space', '<=' , $value);
    }
}