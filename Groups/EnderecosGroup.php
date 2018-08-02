<?php
/**
 * Created by PhpStorm.
 * User: Rafael Freitas
 * Date: 01/08/2018
 * Time: 22:55
 */

require_once 'Controller/Authorization.php';
require_once 'Controller/EnderecosController.php';
require_once 'DAO/EnderecosDAO.php';

$app->group('', function (){

    $this->get('/estados', function ($request, $response, $args) {

        $enderecoDAO = new EnderecosDAO();
        $retorno = $enderecoDAO->getEstados();

        return $response->withJson($retorno, $retorno['status']);

    });

    $this->get('/cidades/{uf}', function ($request, $response, $args) {

        $enderecosController = new EnderecosController();
        $retorno = $enderecosController->getCidades($args['uf']);

        return $response->withJson($retorno, $retorno['status']);

    });

});