<?php

use App\Models\Estate;
use App\Models\Users_in_relation;
use Intervention\Image\ImageManager;
use Spipu\Html2Pdf\Html2Pdf;

function array_only($array, $keys){
    return array_intersect_key($array, array_flip($keys));

}


// fonction qui permet de reduire et sauvegarder les photos des biens

function get_thumb_image($input_path, $output_path, $definition_settings){
        if($definition_settings === 'thumb'){
            $width = '300';
            $height = '225';
        }elseif($definition_settings === 'hd'){
            $width = '1000';
            $height = '750';
        }else{
            $width = '750';
            $height = '500';
        }

    $image = new ImageManager((array('driver' => 'imagick')));
        $image
            ->make($input_path)
            ->orientate()
            ->fit($width,$height)
            ->save($output_path,90);
        return $image;
}

function delete_image($filename){
    $folders = ['carousel','hd','thumb'];
    $default_path = '/uploads/media/pictures';
    var_dump($filename);
    foreach ($folders as $folder){
        $file = 'uploads/media/pictures/' . $folder . '/' . $filename . '_' . $folder . '.jpg';;
        if(file_exists($file)){
            unlink($file);
        };
    }

    return true;
}
//genere les references des biens et verifie que celle ci est unique
function reference_generator(Int $length){

    $key = random_bytes($length);
    if(count(Estate::where('estate_reference','=',$key)->get()) !== 0){
            do{
                $key = random_bytes($length);
            }while(
                count(Estate::where('estate_reference','=',$key)->get()) !== 0
            );
    };
        //le retour est en binaire
        return bin2hex($key);

}

/**
 * @param int $user1
 * @param int $user2
 * @param int $relation_type
 */
function AddRelationBetweenUsers($user1, $user2, $relation_type)
{

    //verification de l existence ou non d une relation
    $check_existing_relation = Users_in_relation::where('first_user_id', '=', $user1)
        ->where('relation_type_id', '=', $relation_type)->first();

    // si cet utilisateur existe dans la table
    if ($check_existing_relation) {

        if (!in_array($user2, json_decode($check_existing_relation->users_in_contact_id))) {
            //mise a jour de la relation entre les utilisateurs dans la table users_in_relation
            $array_id = json_decode($check_existing_relation->users_in_contact_id);
            try {
                array_push($array_id, $user2);
                Users_in_relation::where('first_user_id', '=', $user1)
                    ->where('relation_type_id', '=', $relation_type)
                    ->update(['users_in_contact_id' => $array_id,
                    ]);
            } catch (Exception $e) {
                echo $e->getMessage();
            }
            // TODO envoi d un email pour prevenir l utilisateur et lui laisser la possibilite de bloquer
        } else {
            // Utilisateur deja dans la liste
            echo 'Cet Utilisateur est déjà dans vos contacts';
        }
    } else {
        // si cet utilisateur n a pas de relation correspondant... on cree la liste
        $array_id = [];
        array_push($array_id, $user2);
        Users_in_relation::create([
            'first_user_id' => $user1,
            'users_in_contact_id' => json_encode($array_id),
            'relation_type_id' => $relation_type,
            'relation_status_id' => 1,
            'created_by_user_id' => $user1,
        ]);
    }
}
    /**
     * generateur de pdf
     * @param string $template
     * @param array $variable
     * @param string $path
     * @param string $name
     */
    function generatePdf($path, $name,$variable){


        ob_start();
        include dirname($path,3).'/pdf_templates/contracts/type1.php';

        $content = ob_get_clean();
        $html2pdf = new Html2Pdf('P', 'A4', 'fr');
        $html2pdf->writeHTML($content);

        return  $html2pdf->output( $path . $name, 'F');

}


