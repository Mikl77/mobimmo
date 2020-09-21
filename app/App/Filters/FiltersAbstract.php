<?php


namespace App\App\Filters;


use Illuminate\Database\Eloquent\Builder;
use Psr\Http\Message\ServerRequestInterface;

abstract class FiltersAbstract
{

    /**
     * @var ServerRequestInterface
     */
    protected $request;

    protected $filters = [];

    public function __construct(ServerRequestInterface $request)
    {
        $this->request = $request;
    }

    public function filter(Builder $builder){

        foreach ($this->getFilters() as $filter => $value) {
            $this->resolveFilter($filter)->filter($builder,$value);
        }
        return $builder;
    }

    protected function resolveFilter($filter){

           return new $this->filters[$filter];
    }

    protected function getFilters(){
        return $this->filterFilters($this->filters);
    }

    protected function filterFilters($filters){
        $filter_array=[];
        $params =$this->request->getQueryParams();
       foreach ($params as $filter => $value){
           if(in_array($filter, array_keys($filters))){
               $filter_array[$filter] = $value;
           }
       }
        return $filter_array;

    }
    public function add(array $filters){
        $this->filters = array_merge($this->filters, $filters);
        return $this;
    }
}