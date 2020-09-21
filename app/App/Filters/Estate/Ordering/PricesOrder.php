<?php


namespace App\App\Filters\Estate\Ordering;


use App\App\Filters\FilterAbstract;
use Illuminate\Database\Eloquent\Builder;

class PricesOrder extends FilterAbstract
{
    public function filter(Builder $builder, $direction){

        $value =  $this->resolveOrderDirection($direction);

        if($value === false){
            return $builder;
        }

        if ($direction === 'date_asc'){
            return $builder->orderBy('updated_at', 'asc');
        }elseif ($direction === 'date_desc'){
            return $builder->orderBy('updated_at', 'desc');
        }else{
            return $builder->orderBy('current_price', $direction);
        }

    }
}