<?php
/**
 * Created by PhpStorm.
 * User: Rafel Freitas
 * Date: 03/08/2018
 * Time: 22:51
 */

class Uteis
{
    public function convertData($oldValue, $delimiter){

        // dd/mm/yyyy => yyyy-mm-dd
        // yyyy-mm-dd => dd/mm/yyyy

        $data = explode($delimiter,$oldValue);
        return $data[2]."-".$data[1]."-".$data[0];

    }

    public function getData(){
        date_default_timezone_set("America/Recife");
        return date('Y-m-d H:i:s');
    }

    public function removeMask($oldValue, $type){
        switch ($type){
            case 'cpf':
                $cpf = str_replace(".", "", $oldValue);
                $cpf = str_replace("-", "", $cpf);
                return $cpf;
                break;

            case 'telefone':
                $telefone = str_replace("(", "", $oldValue);
                $telefone = str_replace(")", "", $telefone);
                $telefone = str_replace(" ", "", $telefone);
                $telefone = str_replace("-", "", $telefone);
                return $telefone;
                break;

            case 'money':
                $money = str_replace("R$ ", "", $oldValue);
                $money = str_replace(".", "", $money);
                $money = str_replace(",", ".", $money);
                return $money;
                break;
        }
    }

    function base64_to_jpeg($base64_string, $output_file) {
        if(empty($base64_string)){
            return null;
        }
        $output_file = strtoupper(uniqid()) . $output_file;
        $image_parts = explode(";base64,", $base64_string);
        $image_type_aux = explode("image/", $image_parts[0]);
        $image_type = $image_type_aux[1];
        $image_base64 = base64_decode($image_parts[1]);
        $file = UPLOAD_CLI . $output_file;
        // mkdir('uploads/docs',0755);
        file_put_contents($file, $image_base64);
        return $output_file;
    }
}