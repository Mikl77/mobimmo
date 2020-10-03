<?php


namespace App\Controllers\Contract;


use App\App\EstateManagerInterface;
use App\App\RelationManagerInterface;
use Cartalyst\Sentinel\Native\Facades\Sentinel;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Views\Twig;

class ContractController
{
    /**
     * @var Twig
     */
    private $view;
    /**
     * @var RelationManagerInterface
     */
    private $relationManager;
    /**
     * @var EstateManagerInterface
     */
    private $estateManager;

    public function __construct(Twig $view, RelationManagerInterface $relationManager, EstateManagerInterface $estateManager)
    {
        $this->view = $view;
        $this->relationManager = $relationManager;
        $this->estateManager = $estateManager;
    }

    //Affichage page gestion Contrats
    public function getContractMain(ServerRequestInterface $request, ResponseInterface $response){

                $estate_list = $this->estateManager->getAllManagedEstates(Sentinel::check()->id);



                return $this->view->render($response,'pages/contract/add_contract_main.twig',[
                    'current_page'=>'contract_main',
                    'estate_list'=>$estate_list,
                ]);
        }

        //Affichage du questionnaire pour etude de dossier
    public function getForm(ServerRequestInterface $request, ResponseInterface $response){

        return $this->view->render($response,'pages/contract/form_main.twig',[
            'current_page'=>'form_main',
        ]);
    }
}