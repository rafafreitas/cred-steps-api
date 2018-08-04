<?php
/**
 * Created by PhpStorm.
 * User: Rafael Freitas
 * Date: 04/08/2018
 * Time: 10:58
 */

require_once 'Basics/Motivo.php';
require_once 'Basics/Credito.php';
require_once 'DAO/MotivoDAO.php';
class MotivoController
{
    public function insert(Motivo $motivo, Credito $credito){

        if (empty($motivo->getCliId())) {
            return array('status' => 500, 'message' => "ERROR", 'result' => 'Informe o ID do cliente(Obj->Motivo)!');
            die;
        }
        if (empty($motivo->getMotivoId())) {
            return array('status' => 500, 'message' => "ERROR", 'result' => 'Informe o motivo do crédito!');
            die;
        }

        if (empty($credito->getCliId())) {
            return array('status' => 500, 'message' => "ERROR", 'result' => 'Informe o ID do cliente(Obj->Crédito)!');
            die;
        }

        if (empty($credito->getCredId())) {
            return array('status' => 500, 'message' => "ERROR", 'result' => 'Informe como pretende conseguir o crédito!');
            die;
        }

        $motivoDAO = new MotivoDAO();
        $retorno = $motivoDAO->verifyMotivos($motivo->getCliId());

        if($retorno['status'] == 200){
            return $motivoDAO->insert($motivo, $credito);
        }else if($retorno['status'] == 401){
            return $motivoDAO->update($motivo, $credito);
        }else{
            return $retorno;
        }
    }
}