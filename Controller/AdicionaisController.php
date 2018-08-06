<?php
/**
 * Created by PhpStorm.
 * User: rafael Freitas
 * Date: 04/08/2018
 * Time: 21:57
 */

require_once 'Basics/EstadualMunicipal.php';
require_once 'Basics/Financeiro.php';
require_once 'Basics/Parentesco.php';
require_once 'DAO/AdicionaisDAO.php';
class AdicionaisController
{
    public function insert(EstadualMunicipal $estadualMunicipal, Financeiro $financeiro, $parentesco, $flag){

        if (empty($financeiro->getCliId())) {
            return array('status' => 500, 'message' => "ERROR", 'result' => 'Informe o ID do cliente!');
            die;
        }
        if ($financeiro->getSpc() == '') {
            return array('status' => 500, 'message' => "ERROR", 'result' => 'Informe se você está negativado!');
            die;
        }

        if ($financeiro->getEmprego() == '') {
            return array('status' => 500, 'message' => "ERROR", 'result' => 'Informe se tem carteira assinada!');
            die;
        }
        if (empty($financeiro->getRendaComprovada())) {
            return array('status' => 500, 'message' => "ERROR", 'result' => 'Informe se tem renda comprovada!');
            die;
        }
        if ($financeiro->getCheque() == '') {
            return array('status' => 500, 'message' => "ERROR", 'result' => 'Informe se possui cheque!');
            die;
        }
        if (empty($financeiro->getBankPossui())) {
            return array('status' => 500, 'message' => "ERROR", 'result' => 'Informe se possui conta no banco!');
            die;
        }

        if (empty($financeiro->getBankId() == "")) {
            $financeiro->setBankId(null);
        }

        $adicionaisDAO = new AdicionaisDAO();
        $retorno = $adicionaisDAO->verifyAdicionais($financeiro->getCliId());

        if($retorno['status'] == 200){
            return $adicionaisDAO->insert($estadualMunicipal, $financeiro, $parentesco, $flag);
        }else if($retorno['status'] == 401){
            return $adicionaisDAO->update($estadualMunicipal, $financeiro, $parentesco, $flag);
        }else{
            return $retorno;
        }
    }
}