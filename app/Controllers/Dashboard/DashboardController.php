<?php

namespace App\Controllers\Dashboard;

use App\App\EstateManagerInterface;
use App\App\ExchangeManagerInterface;
use App\Models\Estate;
use Cartalyst\Sentinel\Native\Facades\Sentinel;
use Illuminate\Pagination\LengthAwarePaginator;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Views\Twig;

class DashboardController{

    protected $view;

    protected $estate;
    /**
     * @var ExchangeManagerInterface
     */
    private $exchange;

    public function __construct(Twig $view, EstateManagerInterface $estate, ExchangeManagerInterface $exchange)
    {
        $this->view = $view;
        $this->estate = $estate;
        $this->exchange = $exchange;
    }

    public function __invoke(ServerRequestInterface $request,ResponseInterface $response)
    {

        return $this->view->render($response,'pages/dashboard/starter.twig',[
                'estates'=>$this->estate->getAllManagedEstates(Sentinel::check()->id),
                'messages'=>$this->exchange->getUserMessages(Sentinel::check()->id, 1),
                'current_page'=>'dashboard'
        ]);
    }
}
