<?php


namespace App\Controllers\Dashboard;


use App\App\EstateManagerInterface;
use App\Controllers\Controller;
use App\Controllers\Geocoder\Geocoder;
use App\Models\Bathroom_number;
use App\Models\Bedroom_number;
use App\Models\Building_year;
use App\Models\City;
use App\Models\Estate;
use App\Models\Estate_manager;
use App\Models\Estate_Status;
use App\Models\Estate_Type;
use App\Models\Featured;
use App\Models\FrPostalCode;
use App\Models\In_Charge;
use App\Models\Parking_type;
use App\Models\Rooms_number;
use Cartalyst\Sentinel\Native\Facades\Sentinel;
use DateTime;
use Exception;
use Geocoder\Provider\GoogleMaps\GoogleMaps;
use Geocoder\Provider\Nominatim\Nominatim;
use Geocoder\StatefulGeocoder;
use Http\Adapter\Guzzle6\Client;
use Illuminate\Database\Eloquent\Builder;
use Intervention\Image\ImageManager;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use RuntimeException;
use Slim\Flash\Messages;
use Slim\Interfaces\RouteParserInterface;
use Slim\Views\Twig;
use Twig\TokenParser\DoTokenParser;

class EstateController extends Controller
{
    protected $view;
    /**
     * @var Messages
     */
    protected $flash;
    /**
     * @var RouteParserInterface
     */
    private $routeParser;

    /**
     * @var EstateManagerInterface
     */
    private $estate;

    public function __construct(Twig $view, Messages $flash,RouteParserInterface $routeParser,EstateManagerInterface $estate)
    {
        $this->view = $view;
        $this->flash = $flash;
        $this->routeParser = $routeParser;
        $this->estate = $estate;
    }

    // affichage de la page de creation d un nouveau bien
    public function getAddEstate(ServerRequestInterface $request,ResponseInterface $response)
    {

        //recuperation de l'ensemble des éléments pour charger la page
        $estate_statuses = Estate_Status::all();
        $estate_types = Estate_Type::all();
        $featured = Featured::all();
        $bedrooms = Bedroom_number::all();
        $bathrooms = Bathroom_number::all();
        $build_years = Building_year::all();
        $rooms = Rooms_number::all();
        $parking_type = Parking_type::all();

        return $this->view->render($response,'pages/dashboard/estate/add_estate_admin.twig',[
            "current_page"=>'add_estate',
            "estate_statuses" =>$estate_statuses,
            "estate_types"=>$estate_types,
            "featured"=>$featured,
            "bedrooms"=>$bedrooms,
            "bathrooms"=>$bathrooms,
            "build_years"=>$build_years,
            "rooms"=>$rooms,
            "parking_types"=>$parking_type
        ]);
    }

    //creation d un nouveau bien
    public function postAddEstate(ServerRequestInterface $request, ResponseInterface $response){
                //validation du Formulaire
//        $data = $this->validate($request, [
//            'designation' => ['required'],
//            'adresse'=>['required'],
//            'ownername'=>['required']
//        ]);

        /// gestion de la partie image si le champ main_picture est renseigne -> OK

        $image_file_tmp = $_FILES['main_picture']['tmp_name'];
        $image_file = $_FILES['main_picture']['name'];

        if($_FILES['main_picture']['error'] === 0) {

            $filename = uniqid();

            $allowed_image_extension = array(
                "png",
                "jpg",
                "jpeg",
                "bmp"
            );
            //picture validation
            // Get image file extension
            $file_extension = pathinfo($image_file, PATHINFO_EXTENSION);

            if (!in_array($file_extension, $allowed_image_extension)) {
                $this->flash->addMessage('status', 'Les extensions autorisées sont : Jpeg, Png, Bmp');
                return $response->withHeader(
                    'Location', $this->routeParser->urlFor('add_estate')
                );
                //Verification du poids de la photo
            } else if (($_FILES["main_picture"]["size"] > 5000000)) {
                $this->flash->addMessage('status', 'La taille maximum est de 5Mo');
                return $response->withHeader(
                    'Location', $this->routeParser->urlFor('add_estate')
                );
            }
            //picture resize par la fonction cree
            //transfert vers dossier

            try {
                $definition = ['thumb', 'hd','carousel'];
                foreach ($definition as $def) {
                    $destination_folder_thumb = 'uploads/media/pictures/' . $def . '/' . $filename . '_' . $def . '.jpg';
                    get_thumb_image($image_file_tmp, $destination_folder_thumb, $def);
                }
            } catch (Exception $e) {
                $this->flash->addMessage('status', $e->getMessage());
                return $response->withHeader(
                    'Location', $this->routeParser->urlFor('add_estate')
                );
            }
            }else{
            switch ($_FILES['main_picture']['error']) {
                //l 'envoi d'une photo n'est pas obligatoire
                case UPLOAD_ERR_OK:
                case UPLOAD_ERR_NO_FILE:
                    break;

                case UPLOAD_ERR_INI_SIZE:
                case UPLOAD_ERR_FORM_SIZE:
                    $this->flash->addMessage('failed', 'La taille maximum est de 5Mo');
                    return $response->withHeader(
                        'Location', $this->routeParser->urlFor('add_estate')
                    );
                default:
                    $this->flash->addMessage('failed', 'Une erreur s\'est produite dans le traitement de l\'image');
                    return $response->withHeader(
                        'Location', $this->routeParser->urlFor('add_estate')
                    );
            }
        }

        //et enregistrement bdd
        try{
           //Creation de la reference de ce bien
            $estate_reference = reference_generator(4);

            //Colcul de la latitude et de la longitude de l adresse du bien
            $address = $_POST['address-input'] ." ". $_POST['postal_code'] . $_POST['town'];
            $address = preg_replace('/\s+/', '+', $address);
            $geocode = new Geocoder($address);
            $data =  $geocode->Geocode();

            if($data === false){
                $town = FrPostalCode::where(strtoupper('district_name'),'LIKE',$_POST['town'])->first();
                var_dump($_POST['town']);
                var_dump($town);
                if($town){
                    $data['lat'] = $town->lat;
                    $data['lng']= $town->lng;
                }else{
                    $data['lat'] = "";
                    $data['lng']= "";
                }
            }
            //Ajout dans la Table Estate
            //todo manque Price,years build, features,proprietaire
            Estate::create([
                'estate_name' =>$_POST['designation'],
                'estate_address'=>$_POST['address-input'],
                'estate_postal_code'=>$_POST['postal_code'],
                'estate_town'=>$_POST['town'],
                'estate_town_id' =>$_POST['estate_town_id'],
                'estate_type_id'=>$_POST['estate_type'],
                'floor_space'=>$_POST['area'],
                'number_of_rooms'=>$_POST['rooms'],
                'balconies_space'=>$_POST['balcony'],
                'number_of_bedrooms'=>$_POST['bedrooms'],
                'number_of_bathrooms'=>$_POST['bathrooms'],
                'number_of_garages'=>Null,
                'parking_type_id'=>$_POST['parking_type'],
                'pets_allowed'=>Null,
                'estate_description'=>$_POST['description'],
                'estate_status_id'=>$_POST['estate_status'],
                'estate_location_id'=>Null,
                'estate_main_picture_filename'=>$filename,
                'estate_reference'=>$estate_reference,
                'estate_lat'=>$data['lat'],
                'estate_lng'=>$data['lng']
            ]);

            //Ajout de l utilisateur dans la table In_charge

        }catch (Exception $exception){
            //gestion de la suppression de l image=> Si une erreur intervient dans l'insertion en bdd
            delete_image($filename);
            $this->flash->addMessage('status', $exception->getMessage());
            return $response->withHeader(
                'Location', $this->routeParser->urlFor('add_estate')
            );
        }

        try {
            In_Charge::create([
                'estate_id'=>Estate::latest()->first()->estate_id,
                'user_id'=>Sentinel::check()->id,
                'date_from'=>new DateTime()
            ]);

        }catch (Exception $exception){
            //gestion de la suppression de l image=> Si une erreur intervient dans l'insertion en bdd
            delete_image($filename);
            $this->flash->addMessage('status', $exception->getMessage());
            return $response->withHeader(
                'Location', $this->routeParser->urlFor('add_estate')
            );
        }

        $this->flash->addMessage('success', 'Le bien a été crée');
        return $response->withHeader(
            'Location', $this->routeParser->urlFor('dashboard')
        );
    }

    //affichage de la page de consultation / modification d un bien existant
    public function getShowEstate(ServerRequestInterface $request, ResponseInterface $response, $args){

        $estate = $this->estate->getSingleEstate($args);
        $estate_pictures = $this->estate->getAllPicturesEstate($args);
        //recuperation de l'ensemble des éléments pour charger la page
        $estate_statuses = Estate_Status::all();
        $estate_types = Estate_Type::all();
        $featured = Featured::all();
        $bedrooms = Bedroom_number::all();
        $bathrooms = Bathroom_number::all();
        $build_years = Building_year::all();
        $rooms = Rooms_number::all();
        $parking_type = Parking_type::all();

        return $this->view->render($response,'pages/dashboard/estate/show_estate.twig',[
            'estate'=>$estate,
            'pictures'=>$estate_pictures,
            "estate_statuses" =>$estate_statuses,
            "estate_types"=>$estate_types,
            "featured"=>$featured,
            "bedrooms"=>$bedrooms,
            "bathrooms"=>$bathrooms,
            "build_years"=>$build_years,
            "rooms"=>$rooms,
            "parking_types"=>$parking_type
        ]);
    }

    public function getEstateUserList(ServerRequestInterface $request, ResponseInterface $response){
        $estates = $this->estate->getAllManagedEstates(Sentinel::check()->id);

        return $this->view->render($response,'pages/dashboard/estate/estate_user_list.twig',[
            'estates'=>$estates,
            'current_page'=>'estate_user_list'
        ]);
    }

}