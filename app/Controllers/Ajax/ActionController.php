<?php


namespace App\Controllers\Ajax;


use App\Controllers\Base_controller;
use App\Models\All_files;
use App\Models\Client;
use App\Models\Estate;
use App\Models\FrPostalCode;
use App\Models\In_Charge;
use App\Models\Message;
use App\Models\User;
use App\Models\Users_in_relation;
use Exception;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Spipu\Html2Pdf\Html2Pdf;

class ActionController extends Base_controller
{
    public function getAction(ServerRequestInterface $request, ResponseInterface $response){

    }

    public function postAction(ServerRequestInterface $request, ResponseInterface $response){
        if(isset($_POST["action"])){
            //envoi du message page single property
            if($_POST["action"] == "info_message"){
                $estateId = Estate::where('estate_reference','=',$_POST["reference"])->first();
                $managerId = In_Charge::where('estate_id','=',$estateId->id)->first();

                Client::create([
                    'client_first_name'=>$_POST["fname"],
                    'client_last_name'=>$_POST["lname"],
                    'client_phone'=>$_POST["pnumber"],
                    'client_mail'=>$_POST["emailid"]
                ]);

                $lastid = Client::latest()->first();

                Message::create([
                    'sender_id'=>$lastid->id,
                    'receiver_id'=>$_POST["managerId"],
                    'content'=>$_POST["message"],
                    'estate_id'=>$estateId->id,
                    'message_status_id'=>1,
                    'message_priority_id'=>1

                ]);
            }
            if($_POST["action"] == "estate_search"){


                $params = $_POST['formdata'];
                unset($params['csrf_name']);
                unset($params['csrf_value']);
                $response_query = '?page=1';

                foreach ($params as $key=>$value){
                    if($value === '0' || $value === '' ){
                    }else{
                        $response_query = $response_query . '&' . $key . '='. $value;
                    }
                }
                echo $response_query;
            }

            //autocomplete champ code-postal
            if($_POST["action"] == "search_town"){
                if(isset($_POST["query"])){

                    $output = '';
                    if(is_numeric($_POST["query"])){
                        $query = FrPostalCode::where('postal_code','LIKE',$_POST['query'].'%')
                            ->limit(25)
                            ->groupBy('district_name')
                            ->get();
                    }else{
                        $query = FrPostalCode::where('libelle','LIKE',$_POST['query'].'%')
                            ->limit(25)
                            ->groupBy('postal_code')
                            ->get();
                    }
                    echo $query->toJson();
                }
            }

            //gestion file-input ajout d images Kartik
            if($_POST["action"] == "file-input"){
                if(empty($_FILES['input-fr'])){
                    echo json_encode(['error'=>'No files']);
                };

                foreach ($_FILES as $FILE){

                    $image_file_tmp = $FILE['tmp_name'][0];
                    $image_file = $FILE['name'];

                    // todo faire validation des fichiers

                    $filename = uniqid();
                    //picture resize par la fonction cree
                    //transfert vers dossier
                    try {
                        $definition = ['thumb', 'hd','carousel'];
                        foreach ($definition as $def) {
                            $destination_folder_thumb = 'uploads/media/pictures/' . $def . '/' . $filename . '_' . $def . '.jpg';
                            get_thumb_image($image_file_tmp, $destination_folder_thumb, $def);
                        }
                    } catch (Exception $e) {
                       echo json_encode(['error'=>$e->getMessage()]);
                    }
                    All_files::create([
                        'file_type_id'=>"1",
                        'filename'=>$filename,
                        'user_id'=>null,
                        'estate_id'=>$_POST['estate_id'],
                    ]);
                    echo json_encode(['nothing'=>true]);
                }
            }

            //chargement des photos apres le file-input
            if($_POST["action"] == "load_images"){
                $images_files = All_files::where('file_type_id','=',1)
                    ->where('estate_id','=',$_POST['estate_id'])
                    ->get();
                $output = "";
                if($images_files){
                    $output .= '<div class ="col-12 product-image-thumbs" >';
                    foreach ($images_files as $images_file){
                        $output .=  '<div class="product-image-thumb"><img src="/uploads/media/pictures/thumb/'. $images_file->filename .'_thumb.jpg" alt="Product Image"><a href=""><i class="fas fa-times-circle close" id="'.$images_file->filename.'"></i></a></></div>';
                    }
                    $output .='</div>';
                }
                echo $output;
            }

            //suppression de l image dans les 3 dossiers avec la fonction deleteImage
            if($_POST["action"] == "delete_image"){
                if($_POST["filename"]){
                    delete_image($_POST["filename"]);
                    $data_to_delete = All_files::where('filename','=',$_POST["filename"])->first();
                    $data_to_delete->delete();

                }
                //suppression des references a cette photo dans la table all_files

            }

            //mise a jour des information du bien
            if($_POST['action'] == "update_estate_data"){
                // TODO validation des donnees

                //Update de la base de donnee
                Estate::where('estate_id','=',$_POST['estate_id'])
                        ->update(['estate_name'=>$_POST['title']]);

            }

            //gestion main-file-update page SHOW_ESTATE Kartik
            if($_POST["action"] == "main-file-update"){
                $FILE = $_FILES['input-main_picture'];

                if(empty($FILE)){
                    echo json_encode(['error'=>'No files']);
                };
                    //recuperation de l image principale actuelle
                    $current_main_picture =  Estate::where('estate_id','=',$_POST["estate_id"])->first();
                    $current_main_picture = $current_main_picture->estate_main_picture_filename;

                    //la photo principale devient photo secondaire
                    All_files::create([
                        'file_type_id'=>"1",
                        'filename'=>$current_main_picture,
                        'user_id'=>null,
                        'estate_id'=>$_POST['estate_id'],
                    ]);
                    $image_file_tmp = $FILE['tmp_name'];

                    // todo faire validation des fichiers

                    $filename = uniqid();
                    //picture resize par la fonction cree
                    //transfert vers dossier
                    try {
                        $definition = ['thumb', 'hd','carousel'];
                        foreach ($definition as $def) {
                            $destination_folder_thumb = 'uploads/media/pictures/' . $def . '/' . $filename . '_' . $def . '.jpg';
                            get_thumb_image($image_file_tmp, $destination_folder_thumb, $def);
                        }
                    } catch (Exception $e) {
                        echo json_encode(['error'=>$e->getMessage()]);
                    }

                    Estate::where('estate_id','=',$_POST["estate_id"])
                        ->update(['estate_main_picture_filename'=> $filename]);

                    echo json_encode(['nothing'=>true]);
            }

            //chargement de la photo principale apres le file-input
            if($_POST["action"] == "load_main_image"){
                $image_file = Estate::where('estate_id','=',$_POST['estate_id'])
                    ->first();
                $output = "";
                if($image_file){
                    $output .= '<div class ="col-12" >';
                        $output .=  '<img src="/uploads/media/pictures/carousel/'. $image_file-> estate_main_picture_filename .'_carousel.jpg" class="product-image" alt="Product Image"></></div>';
                    $output .='</div>';
                }
                echo $output;
            }

            //recherche de l existence ou nom de l email du client
            if($_POST['action'] == "check_mail"){
               $mail_search = User::where('email','=',$_POST['mail_to_find'])
                                ->first();

               if($mail_search){
                 echo $mail_search->toJson();
               }
            }

            //ajout de l utilsateur dans les "relations" du gestionnaire
            if($_POST['action'] == "add_relation_between_users"){

                $user1 = $_POST['first_user_id'];
                $user2 = $_POST['second_user_id'];
                $relation_type = $_POST['relation_type_id'];
                settype($user1, "integer");
                settype($user2, "integer");
                settype($relation_type, "integer");

                //Ajout de la relation dans un sens
                AddRelationBetweenUsers($user1,$user2,$relation_type);
                // et de sa reciproque si applicable
                //TODO voir pour ameliorer la gestion des relations reciproques
                //la reciproque au locataire est gestionnaire
                $reciproque_relation_type = $relation_type;
                if($relation_type === 1){
                    AddRelationBetweenUsers($user2,$user1,2);
                }


            }

            //ajout du locataire dans la page contrat
            if($_POST['action'] == "add_locataire"){

                $user1 = $_POST['first_user_id'];
                settype($user1, "integer");
                $list = $_POST['locataires_list'];;
                if($list === ""){
                    $list = [];
                }else{
                    $list= json_decode($list);
                }
                array_push($list, $user1);
                echo json_encode($list);
            }

            //chargement des fiches locataires
            if($_POST["action"] == "load_locataires"){
                $output= "";
               $locataire_list = json_decode($_POST['locataire_list']);
               $output .= '<div class="card-body pb-0"><div class="row d-flex align-items-stretch">';
               foreach ($locataire_list as $locataire) {
                   $locataire_details = User::where('id', '=', $locataire)->first();
                   $output .= '<div class="col-12 col-sm-6 col-md-4 d-flex align-items-stretch">';
                   $output .= '<div class="card bg-light">';
                   $output .= '<div class="card-header text-muted border-bottom-0">';
                   $output .= '-' . $locataire_details->id . '- Blablabla';
                   $output .= '</div>';
                   $output .= '<div class="card-body pt-0"><div class="row"><div class="col-7">';
                   $output .= '<h2 class="lead"><b>';
                   $output .= $locataire_details->first_name . " " . $locataire_details->last_name;
                   $output .= '</b></h2><p class="text-muted text-sm"><b>About: </b> Web Designer / UX / Graphic Artist / Coffee Lover </p>
                                <ul class="ml-4 mb-0 fa-ul text-muted">
                                    <li class="small"><span class="fa-li"><i class="fas fa-lg fa-building"></i></span> Address: Demo Street 123, Demo City 04312, NJ</li>
                                    <li class="small"><span class="fa-li"><i class="fas fa-lg fa-phone"></i></span> Phone #: + 800 - 12 12 23 52</li>
                                </ul>
                            </div>
                            <div class="col-5 text-center">
                                <img src="/dist/img/user1-128x128.jpg" alt="" class="img-circle img-fluid">
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <div class="text-right">
                            <a href="#" class="btn btn-sm bg-teal">
                                <i class="fas fa-comments"></i>
                            </a>
                            <a href="#" class="btn btn-sm btn-primary">
                                <i class="fas fa-user"></i> View Profile
                            </a>
                        </div>
                    </div>
                </div>
            </div>';
               }
               $output .= '</div></div>';
               echo $output;
            }

            //creation nouveau contrat
            if ($_POST["action"] == "add_new_contract"){

                // descriptif des etapes de creation
                // - Recuperation et Validation des variables
                $variable = array(
                    "lessor_name" => "Penhoat Mickael",
                    "lessor_address" => "22 rue François Desportes, 77930 Chailly-en-bière, France",
                    "client" => array(
                        "1"=>array(
                            "client_name"=>"Stein Cécile",
                            "client_address"=>"22 rue François Desportes, 77930 Chailly-en-bière, France",
                            "client_phone"=>"06 73 59 70 70",
                            "client_pob"=>"Corbeil",
                            "client_dob"=>"16/12/1986"),
                        "2"=>array(
                            "client_name"=>"Alhanati Fabien",
                            "client_address"=>"17 route de Dampierre, Les choux, France",
                            "client_phone"=>"06 06 06 06 05",
                            "client_pob"=>"Montargis",
                            "client_dob"=>"21/10/1987")
                    ),
                    "rent_hc"=>"1035",
                    "charges"=>"65",
                    "month_total"=>"1100",
                    "security_deposit"=>"2070",
                    "specials"=>array(
                        "Q1"=>"Non",
                        "Q2"=>"Non",
                        "Q3"=>"Non",
                        "Q4"=>"Non"
                    ),
                    "ref_rent"=>"17.00",
                    "ref_rent_maj"=>"20.40",
                    "accomodation"=>array(
                        "designation"=>"Appartement avec cave et une place de parking en sous-sol.",
                        "number_room"=>"2",
                        "surface"=>"52",
                        "address"=>"91 Rue Victor RECOURAT, 94170 Le Perreux-sur-Marne, IDF, France",
                        "stage"=>"1",
                        "cave_number"=>"A48",
                        "parking_number"=>"A61",
                        "building_type"=>"Immeuble collectif",
                        "legal_status"=>"Copropriété",
                        "building_year"=>"Après 1990",
                        "heating"=>"Individuel",
                        "water_heating"=>"Individuelle",
                        "common_usage"=>array("Ascenseur","Interphone","Espaces_verts"),
                        "contract_length"=>"1",
                        "start_date"=>"01.08.2020",
                        "end_date"=>"31.07.2021",
                        "IRL"=>array(
                            "time"=>"2ème trimestre 2020",
                            "value"=>"130.57"
                        ),
                        "insurance"=>array(
                            "name"=>"MACIF",
                            "start_date"=>"30/07/2020",
                            "end_date"=>"31/03/2021"
                        )
                    ),
                    "warrantors"=>array(
                        "1"=>array(
                            "name"=>"Mr Olivier CHERET",
                            "address"=>"103 Promenade des Golfeurs, Bussy-Saint-Georges, 77600, France"
                        ),
                        "2"=>array(
                            "name"=>"Mr Hervé GUILLEMOT",
                            "address"=>"22 Rue de l'Église, Vexin-sur-Epte, 27510, France"
                        )
                    ),
                    "date"=>"01/08/2020"
                );
                // - Ajout en bdd
                // - Creation du pdf
                // - Archivage pdf
                // - Envoi des mails

                // -- Etape 1 -- Validation //
                //todo Validation des elements recus

                // -- Etape 2 -- Ajout en bdd //
                //todo Ajout bdd

                // -- Etape 3 -- Creation pdf //
                // En cours

                $path = $this->container->get('settings')['pdf_upload_directory'];

                $pdf = generatePdf($path,'my_doc1.pdf',$variable);



            }

        }
            return $response;
    }
}