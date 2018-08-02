<?php
/**
 * Created by PhpStorm.
 * User: rafael Freitas
 * Date: 01/08/2018
 * Time: 21:46
 */

require_once 'Controller/Authorization.php';
require_once 'DAO/BancosDAO.php';

$app->group('', function (){

    //List-All
    $this->get('/bancos', function ($request, $response, $args) {

        $bancosDAO = new BancosDAO();
        $retorno = $bancosDAO->getBancos();

        return $response->withJson($retorno, $retorno['status']);

    });

});