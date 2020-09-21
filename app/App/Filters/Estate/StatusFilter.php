<?php


namespace App\App\Filters\Estate;


use App\App\Filters\FilterAbstract;
use App\Models\Estate_Status;
use Illuminate\Database\Eloquent\Builder;

class StatusFilter extends FilterAbstract
{

    public function mappings()
    {
       $statuses = Estate_Status::get();
       $mappings = [];
       foreach ($statuses as $key=> $value){
           $mappings[$value->id] = $value->estate_status_name;
       }
        return $mappings;
    }

    public function filter(Builder $builder, $key){

        $value =  $this->resolveFilterValue($key);

        if($value === false){
            return $builder;
        }
        return $builder->where('estate_status_id', $key);
    }


}