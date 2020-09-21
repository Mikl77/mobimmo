<?php


namespace App\Controllers\Pages;


use App\App\EstateManagerInterface;
use App\Models\Bathroom_number;
use App\Models\Bedroom_number;
use App\Models\Estate;
use App\Models\Estate_Status;
use App\Models\Estate_Type;
use App\Models\Order_estate;
use App\Models\Rooms_number;
use http\Env\Request;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Views\Twig;

class PropertyListController
{

    protected $view;
    /**
     * @var EstateManagerInterface
     */
    private $estate;

    public function __construct(Twig $view, EstateManagerInterface $estate)
    {
        $this->view = $view;
        $this->estate = $estate;
    }

    public function getPropertyList(ServerRequestInterface $request,ResponseInterface $response)
    {
            $statuses = Estate_Status::get();
            $types = Estate_Type::get();
            $rooms = Rooms_number::get();
            $bedrooms = Bedroom_number::get();
            $bathrooms = Bathroom_number::get();
            $orders = Order_estate::get();

            $estates = Estate::filter($request)->paginate(20)->appends($request->getQueryParams());

            return $this->view->render($response,'pages/property_list/property_grid_with_side_bar.twig',[
                'estates'=>$estates,
                'statuses'=>$statuses,
                'types'=>$types,
                'rooms'=>$rooms,
                'bedrooms'=>$bedrooms,
                'bathrooms'=>$bathrooms,
                'total_number'=>$this->estate->getAllEstate(),
                'orders'=>$orders
        ]);
    }



}