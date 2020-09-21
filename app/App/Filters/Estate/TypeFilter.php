<?php


namespace App\App\Filters\Estate;


use App\App\Filters\FilterAbstract;

use App\Models\Estate_Type;
use Illuminate\Database\Eloquent\Builder;

class TypeFilter extends FilterAbstract
{
    public function  mappings()
    {
        $types = Estate_Type::get();
        $mappings = [];
        foreach ($types as $key=> $value){
            $mappings[$value->id] = $value->estate_type_name;
        }
        return $mappings;
    }

    public function filter(Builder $builder, $key)
    {
        $value = $this->resolveFilterValue($key);
        if($value === false){
            return $builder;
        }
        return $builder->where('estate_type_id', $key);
    }
}