<?php
/**
 * Created by PhpStorm.
 * User: r.a.freitas
 * Date: 29/10/2018
 * Time: 14:31
 */

require_once 'DAO/SolicitacaoDAO.php';

class SolicitacaoController
{
    public function getSolicitacoes(){
        $solicitacaoDAO = new SolicitacaoDAO();
        return $solicitacaoDAO->getSolicitacoes();
    }
}