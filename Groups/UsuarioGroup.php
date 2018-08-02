<?php
/**
 * Created by PhpStorm.
 * User: Rafael Freitas
 * Date: 01/08/2018
 * Time: 18:56
 */

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

require_once 'Controller/Authorization.php';
require_once 'Controller/UserController.php';

$app->group('', function (){

    //List-All
    $this->get('/users', function ($request, $response, $args) {

//        $auth = new Authorization();
//        $check = $auth->verificarToken($request);
//
//        if ($check['status'] != 200) {
//            return $response->withJson($check, $check['status']);
//            die;
//        }

        $userController = new UserController();
        $retorno = $userController->getUsers();

        return $response->withJson($retorno, $retorno['status']);

    });

});