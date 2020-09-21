<?php


namespace App\App\Filters;


use App\Models\Order_estate;
use Illuminate\Database\Eloquent\Builder;

abstract class FilterAbstract
{
    abstract public function filter(Builder $builder, $value);

    public function mappings(){
        return [];
    }

    protected function resolveFilterValue($key){
        return array_key_exists($key,$this->mappings());
    }

    public function orderMappings(){

        $orders = Order_estate::get();
        $mappings = [];
        foreach ($orders as $key=> $value){
            $mappings[$value->designation] = $value->query_value;
        }
        return $mappings;
    }

    protected function resolveOrderDirection($direction){
        return array_key_exists($direction,$this->orderMappings());
    }

}