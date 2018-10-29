<?php
/**
 * Created by PhpStorm.
 * User: r.a.freitas
 * Date: 29/10/2018
 * Time: 14:27
 */
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
require_once 'Controller/SolicitacaoController.php';

$app->group('/solicitacoes', function () {

    $this->get('', function (ServerRequestInterface $request, ResponseInterface $response, $args) {

        $data = $request->getQueryParams();
        $auth = new Authorization();
        $objJwt = $auth->verificarToken($request);

        if($objJwt['status'] != 200){
            return $response->withJson($objJwt, $objJwt['status']);
            die;
        }

        $solicController = new SolicitacaoController();
        $retorno = $solicController->getSolicitacoes();


        if ($retorno['status'] !== 200){
            return $response->withJson($retorno, $retorno['status']);
            die;
        }else{
            $auth = new Authorization();
            $jwt = $auth->gerarToken($objJwt['token']->data);
            $res = array(
                'status'        => 200,
                'message'       => "SUCCESS",
                'solicitacoes'  => $retorno['result'],
            );

            return $response->withHeader('Authorization', $jwt)
                            ->withJson($res, $res['status']);
            die;
        }

    });

});