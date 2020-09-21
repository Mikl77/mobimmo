<?php


namespace App\Controllers\User;


use App\App\RelationManagerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Views\Twig;

class UserController
{
    /**
     * @var Twig
     */
    private $view;
    /**
     * @var RelationManagerInterface
     */
    private $relationManager;

    public function __construct(Twig $view, RelationManagerInterface $relationManager)
    {
        $this->view = $view;
        $this->relationManager = $relationManager;
    }

    //Affichage page Locataires List et Co-Gestionnaires possibilite d'etendre encore a d autres listes
    public function getMyUsersList(ServerRequestInterface $request, ResponseInterface $response, $args){

        $my_relations_locataires = $this->relationManager->getMyRelations($args['relation_type']);

        $data=[];

        if($my_relations_locataires){
            $my_relations_locataires= json_decode($my_relations_locataires);
            foreach ($my_relations_locataires as $key=> $value){
                $result = $this->relationManager->getUserDetaisl($value);
                $data[$value]= $result;
            }
        }
        switch ($args['relation_type']){
            //Affichage des locataires\
            //TODO voir pour pagination et systeme de filtre locataires actif ou anciens
            case 1:
                return $this->view->render($response,'pages/users/contact/contact_list.twig',[
                    'current_page'=>'my_locataires_list',
                    'my_relations'=>$data,
                    'relation_type_id'=>$args['relation_type']
                ]);
                //Affichage des co-gestionnaires
            case 2:
                return $this->view->render($response,'pages/users/contact/contact_list.twig',[
                    'current_page'=>'my_co-gestionnaires_list',
                    'my_relations'=>$data,
                    'relation_type_id'=>$args['relation_type']
                ]);
        }
    }
}