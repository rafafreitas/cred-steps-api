<?php
/**
 * Created by PhpStorm.
 * User: Rafael Freitas
 * Date: 01/08/2018
 * Time: 18:56
 */

require_once 'Controller/Authorization.php';
require_once 'Controller/UserController.php';

$app->group('user', function (){

    //List-All
    $this->get('/', function ($request, $response, $args) {

        $auth = new Authorization();
        $check = $auth->verificarToken($request);

        if ($check['status'] != 200) {
            return $response->withJson($auth, $auth['status']);
            die;
        }

        $userController = new UserController();
        $retorno = $userController->getUsers();

        return $response->withJson($retorno, $retorno['status']);

    });

});