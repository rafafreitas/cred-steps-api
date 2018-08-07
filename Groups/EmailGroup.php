<?php
/**
 * Created by PhpStorm.
 * User: Rafael Freitas
 * Date: 07/08/2018
 * Time: 01:37
 */

require_once 'Controller/SendEmail.php';

$app->group('', function (){

    //List-All
    $this->get('/verifyEmails', function ($request, $response, $args) {

        $sendEmail = new SendEmail();
        $retorno = $sendEmail->prepareEmail(true, null);

        return $response->withJson($retorno, $retorno['status']);

    });

});