<?php


namespace App\Controllers\Pages;


use App\App\EstateManagerInterface;
use App\Controllers\Controller;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Views\Twig;

class SinglePropertyController extends Controller
{

    protected $view;

    protected $estate;

    /**
     * SinglePropertyController constructor.
     * @param Twig $view
     * @param EstateManagerInterface $estate
     */
    public function __construct(Twig $view, EstateManagerInterface $estate)
    {
        $this->view = $view;

        $this->estate = $estate;
    }

    public function getSingleProperty(ServerRequestInterface $request,ResponseInterface $response, $args)
    {
        $estate = $this->estate->getSingleEstate($args['ref']);
        $manager = $this->estate->getWhoManage($args['ref']);
        $carousel_pictures = $this->estate->getAllPicturesEstate($args['ref']);

        return $this->view->render($response,'pages/single_property/single_property.twig',[
            'estate'=>$estate,
            'manager'=>$manager,
            'carousel_pictures'=>$carousel_pictures
        ]);
    }

}