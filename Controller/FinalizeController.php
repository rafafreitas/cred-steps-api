<?php
/**
 * Created by PhpStorm.
 * User: Rafael Freitas
 * Date: 06/08/2018
 * Time: 22:46
 */
require_once 'Basics/Finalize.php';
require_once 'DAO/FinalizeDAO.php';
class FinalizeController
{
    public function insert(Finalize $finalize){

        if (empty($finalize->getRg())) {
            return array('status' => 500, 'message' => "ERROR", 'result' => 'Informe seu RG!');
            die;
        }
        if (empty($finalize->getCpf())) {
            return array('status' => 500, 'message' => "ERROR", 'result' => 'Informe seu CPF!');
            die;
        }
        if (empty($finalize->getCompResidencia())) {
            return array('status' => 500, 'message' => "ERROR", 'result' => 'Informe o comprovante de residência!');
            die;
        }

        $finalizeDAO = new FinalizeDAO();
        return $finalizeDAO->insert($finalize);
    }
}