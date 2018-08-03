<?php
/**
 * Created by PhpStorm.
 * User: Rafael Freitas
 * Date: 01/08/2018
 * Time: 18:56
 */

require_once 'Basics/Cliente.php';
require_once 'Basics/Ocupacao.php';
require_once 'Controller/Authorization.php';
require_once 'Controller/ClienteController.php';

$app->group('', function (){

    //List-All
    $this->get('/users', function ($request, $response, $args) {

        $auth = new Authorization();
        $check = $auth->verificarToken($request);

        if ($check['status'] != 200) {
            return $response->withJson($check, $check['status']);
            die;
        }

        $clienteController = new ClienteController();
        $retorno = $clienteController->getUsers();

        return $response->withJson($retorno, $retorno['status']);

    });

    $this->post('/users', function ($request, $response, $args) {

        $json = $request->getParsedBody();

        $cliente = new Cliente();
        $cliente->setValorEmprestimo($json["money"]);
        $cliente->setParcelas($json["parcela"]);
        $cliente->setNome($json["nome"]);
        $cliente->setTelefone($json["telefone"]);
        $cliente->setCpf($json["cpf"]);
        $cliente->setEmail($json["email"]);
        $cliente->setNascimento($json["nascimento"]);
        $cliente->setStatus(1);

        $ocupacao = new Ocupacao();
        $ocupacao->setOpcao($json["ocupacao"]);
        $ocupacao->setEstado($json["estado"]);
        $ocupacao->setCidade($json["cidade"]);
        $ocupacao->setEmpresa($json["empresa"]);

        $clienteController = new ClienteController();
        $retorno = $clienteController->insert($cliente, $ocupacao);

        return $response->withJson($retorno, $retorno['status']);

    });

});