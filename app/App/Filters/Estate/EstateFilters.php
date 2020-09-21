<?php


namespace App\App\Filters\Estate;


use App\App\Filters\Estate\Ordering\PricesOrder;
use App\App\Filters\FiltersAbstract;
use Illuminate\Database\Eloquent\Builder;
use Psr\Http\Message\ServerRequestInterface;


class EstateFilters extends FiltersAbstract
{

    protected $filters = [
        'estate_status'=>StatusFilter::class,
        'estate_type'=>TypeFilter::class,
        'estate_town'=>EstateTownFilter::class,
        'surfaceMin'=>SurfaceMin::class,
        'surfaceMax'=>SurfaceMax::class,
        'priceMin'=>PriceMin::class,
        'priceMax'=>PriceMax::class,
        'estateOrder'=>PricesOrder::class
    ];

}