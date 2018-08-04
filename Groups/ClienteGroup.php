<?php
/**
 * Created by PhpStorm.
 * User: Rafael Freitas
 * Date: 01/08/2018
 * Time: 18:56
 */

require_once 'Basics/Cliente.php';
require_once 'Basics/Ocupacao.php';
require_once 'Basics/Motivo.php';
require_once 'Basics/Credito.php';
require_once 'Basics/Uteis.php';
require_once 'Controller/Authorization.php';
require_once 'Controller/ClienteController.php';
require_once 'Controller/MotivoController.php';

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

    $this->post('/cliente/motivos', function ($request, $response, $args) {

        $json = $request->getParsedBody();
        $authorization = new Authorization();
        $uteisClass = new Uteis();

        $check = $authorization->verificarToken($request);
        if ($check['status'] != 200) {
            return $response->withJson($check, $check['status']);
            die;
        }

        $dataFesta = $uteisClass->convertData($json["datepicker"], '/');
        $limiteCartao = $uteisClass->removeMask($json["limite"], 'money');

        $motivo = new Motivo();
        $motivo->setCliId($check["token"]->data);
        $motivo->setMotivoId($json["radios"]);
        $motivo->setTratamento($json["tratamento"]);
        $motivo->setDataFesta($dataFesta);

        $credito = new Credito();
        $credito->setCredId($json["checkbox"]);
        $credito->setCliId($check["token"]->data);
        $credito->setLimiteCartao($limiteCartao);

        $motivoController = new MotivoController();
        $retorno = $motivoController->insert($motivo, $credito);

        if ($retorno['status'] == 200){
            $retorno['token'] = $authorization->gerarToken($retorno['user_id']);
            unset($retorno['user_id']);
        }

        return $response->withJson($retorno, $retorno['status']);

    });

});