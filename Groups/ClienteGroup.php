<?php
/**
 * Created by PhpStorm.
 * User: Rafael Freitas
 * Date: 01/08/2018
 * Time: 18:56
 */

require_once 'Basics/Cliente.php';
require_once 'Basics/Ocupacao.php';
require_once 'Basics/Uteis.php';
require_once 'Controller/Authorization.php';
require_once 'Controller/ClienteController.php';

$app->group('', function (){

    //List-All
    $this->get('/clientes', function ($request, $response, $args) {

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

    $this->post('/cliente', function ($request, $response, $args) {

        $json = $request->getParsedBody();

        $uteisClass = new Uteis();
        $authorization = new Authorization();

        $nascimento = $uteisClass->convertData($json["nascimento"], '/');
        $telefone = $uteisClass->removeMask($json["telefone"], 'telefone');
        $cpf = $uteisClass->removeMask($json["cpf"], 'cpf');

        $cliente = new Cliente();
        $cliente->setValorEmprestimo($json["money"]);
        $cliente->setParcelas($json["parcela"]);
        $cliente->setNome($json["nome"]);
        $cliente->setTelefone($telefone);
        $cliente->setCpf($cpf);
        $cliente->setEmail($json["email"]);
        $cliente->setNascimento($nascimento);
        $cliente->setStatus(1);
        $cliente->setTipoId(1);

        $ocupacao = new Ocupacao();
        $ocupacao->setOpcao($json["ocupacao"]["opcao"]);
        $ocupacao->setEstado($json["ocupacao"]["estado"]);
        $ocupacao->setCidade($json["ocupacao"]["cidade"]);
        $ocupacao->setEmpresa($json["ocupacao"]["empresa"]);

        $clienteController = new ClienteController();
        $retorno = $clienteController->insert($cliente, $ocupacao);

        if ($retorno['status'] == 200){
            $retorno['token'] = $authorization->gerarToken($retorno['user_id']);
            unset($retorno['user_id']);
        }

        return $response->withJson($retorno, $retorno['status']);

    });

});