<?php


namespace App\Controllers\Ajax;


use App\Controllers\Base_controller;
use App\Models\All_files;
use App\Models\Client;
use App\Models\Estate;
use App\Models\FrPostalCode;
use App\Models\In_Charge;
use App\Models\Irl;
use App\Models\Message;
use App\Models\User;
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

                // - Creation du pdf

                // - Ajout en bdd

                // -- Etape 1 -- Validation //
                //$variable est la variable globale pour completer le contrat
                $variable = [];
                //todo Validation des elements recus

                $estate = Estate::where('estate_id','=',$_POST['estate_id'])
                    ->Leftjoin('building_type','estate_building_type_id','=','building_type.building_type_id')
                    ->Leftjoin('legal_status','estate_legal_status_id','=','legal_status.legal_status_id')
                    ->Leftjoin('building_year','estate_building_year_id','=','building_year.id')
                    ->first();

                $irl = Irl::latest()->first();

                //cas du proprietaire bailleur
                $owner = User::where('id',"=",$estate->estate_owner_id)->first();
                //todo cas du bailleur non proprietaire

                $client_list =  [];
                foreach ($_POST['loca_list'] as $client){
                    $user_desc = User::where('id','=',$client)->first();
                    $date= new \DateTime($user_desc->user_dob);
                    $details = [];
                    $details['client_name'] = $user_desc->gender . ' ' .$user_desc->last_name . ' ' . $user_desc->first_name;
                    $details['client_address'] = $user_desc->user_address_street . ', '. $user_desc->user_address_cp . ' ' . $user_desc->user_address_town . ', ' . $user_desc->user_country;
                    $details['client_phone'] = $user_desc->user_main_phone; //todo formattage du telephone
                    $details['client_pob'] = $user_desc->user_pob;
                    $details['client_dob'] = $date->format('d-m-Y');
                    array_push($client_list,$details);
                }

                // -- Etape 2 -- Creation pdf //

                //Creation de la variable globale contenant toutes les informations pour le contrat

                 $variable = array(
                                    "lessor_name" => $owner->gender . ' ' .$owner->last_name . ' ' . $owner->first_name,
                                    "lessor_address" => $owner->user_address_street . ', '. $owner->user_address_cp . ' ' . $owner->user_address_town . ', ' . $owner->user_country,
                                    "client" => $client_list,
                                    "rent_hc"=>$estate->current_rent,
                                    "charges"=>$estate->current_charges,
                                    "month_total"=>$estate->current_rent + $estate->current_charges,
                                    "security_deposit"=>2*$estate->current_rent,
                                    "specials"=>array(
                                        "Q1"=>"Non",
                                        "Q2"=>"Non",
                                        "Q3"=>"Non",
                                        "Q4"=>"Non"
                                    ),
                                    "ref_rent"=>"17.00",
                                    "ref_rent_maj"=>"20.40",
                                    "accomodation"=>array(
                                        "designation"=>$estate->estate_description,
                                        "number_room"=>$estate->number_of_rooms,
                                        "surface"=>$estate->floor_space,
                                        "address"=>$estate->estate_address . ' ' . $estate->estate_postal_code . ' ' . $estate->estate_town,
                                        "stage"=>$estate->estate_stage,
                                        "cave_number"=>$estate->estate_cave_number,
                                        "parking_number"=>$estate->estate_parking_number,
                                        "building_type"=>$estate->building_type_designation,
                                        "legal_status"=>$estate->legal_status_designation,
                                        "building_year"=>$estate->building_year_name,
                                        "heating"=>"Individuel",
                                        "water_heating"=>"Individuelle",
                                        "common_usage"=>array("Ascenseur","Interphone","Espaces_verts"),
                                        "contract_length"=>"1",
                                        "start_date"=>"01.08.2020",
                                        "end_date"=>"31.07.2021",
                                        "IRL"=>array(
                                            "time"=>$irl->irl_time,
                                            "value"=>$irl->irl_value
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

                $path = $this->container->get('settings')['pdf_upload_directory'];

                $pdf = generatePdf($path,'my_doc1.pdf',$variable);

                // -- Etape 2 -- Ajout en bdd //
                //todo Ajout bdd

                // - Archivage pdf
                // - Envoi des mails

            }

        }
            return $response;
    }
}