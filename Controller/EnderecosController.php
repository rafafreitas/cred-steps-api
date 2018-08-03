<?php
/**
 * Created by PhpStorm.
 * User: Rafael Freitas
 * Date: 01/08/2018
 * Time: 22:56
 */

require_once 'DAO/ClienteDAO.php';
class EnderecosController
{
    public function getCidades($uf){

        if ( empty($uf) ){
            return array('status' => 500, 'message' => "ERROR", 'result' => 'Informe a UF do estado!');
            die;
        }
        $enderecoDAO = new EnderecosDAO();
        return $enderecoDAO->getCidades($uf);

    }
}