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
        }
    }


}