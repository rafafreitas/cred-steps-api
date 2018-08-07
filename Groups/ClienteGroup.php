<?php
/**
 * Created by PhpStorm.
 * User: Rafael Freitas
 * Date: 01/08/2018
 * Time: 18:56
 */

require_once 'Basics/Cliente.php';
require_once 'Basics/Credito.php';
require_once 'Basics/EstadualMunicipal.php';
require_once 'Basics/Financeiro.php';
require_once 'Basics/Finalize.php';
require_once 'Basics/Motivo.php';
require_once 'Basics/Ocupacao.php';
require_once 'Basics/Parentesco.php';
require_once 'Basics/Uteis.php';
require_once 'Controller/Authorization.php';
require_once 'Controller/AdicionaisController.php';
require_once 'Controller/ClienteController.php';
require_once 'Controller/FinalizeController.php';
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
        $cliente->setIndicacao($json["indicacao"]);
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

    $this->post('/cliente/adicionais', function ($request, $response, $args) {

        $json = $request->getParsedBody();
        $authorization = new Authorization();
        $uteisClass = new Uteis();

        $check = $authorization->verificarToken($request);
        if ($check['status'] != 200) {
            return $response->withJson($check, $check['status']);
            die;
        }

        $financeiro = new Financeiro();
        $financeiro->setCliId($check["token"]->data);
        $financeiro->setSpc($json['geral']['financeiras']["spc"]);
        $financeiro->setCheque($json['geral']['financeiras']["cheque"]);
        $financeiro->setChequeDev($json['geral']['financeiras']["chequeDev"]);
        $financeiro->setEmprego($json['geral']['financeiras']["emprego"]);
        $financeiro->setRendaComprovada($json['geral']['financeiras']["rendaComprovada"]);
        $financeiro->setBankPossui($json['geral']['financeiras']["banck"]["possui"]);
        $financeiro->setBankId($json['geral']['financeiras']["banck"]["banco"]);
        $financeiro->setBankTempoConta($json['geral']['financeiras']["banck"]["tempoConta"]);
        $financeiro->setBankAgencia($json['geral']['financeiras']["banck"]["agencia"]);
        $financeiro->setBankConta($json['geral']['financeiras']["banck"]["conta"]);

        $arrayParentesco = $json['geral']['parentescos'];
        foreach ($arrayParentesco as $key => $value) {
            if (empty(array_filter($arrayParentesco[$key]['ocupacao']))){
                unset($arrayParentesco[$key]['ocupacao']);
                $arrayParentesco[$key]['ocupacao'] = "";
            }
            if (empty(array_filter($arrayParentesco[$key]))){
                unset($arrayParentesco[$key]);
            }
        }
        foreach ($arrayParentesco as $key => $value) {

            if (!empty($arrayParentesco[$key]['nascimento'])){
                $nascimento = $uteisClass->convertData($arrayParentesco[$key]['nascimento'], '/');
                $arrayParentesco[$key]['nascimento'] = $nascimento;
            }

            if (!empty($arrayParentesco[$key]['nascimento'])){
                $telefone = $uteisClass->removeMask($arrayParentesco[$key]['telefone'], 'telefone');
                $arrayParentesco[$key]['telefone'] = $telefone;
            }

            if (!empty($arrayParentesco[$key]['nascimento'])){
                $cpf = $uteisClass->removeMask($arrayParentesco[$key]['cpf'], 'cpf');
                $arrayParentesco[$key]['cpf'] = $cpf;
            }

        }

        $estadualMunicipal = new EstadualMunicipal();
        $flag = null;
        if ($json['ocupacao'] == 5) $flag = 'estadual';
        if ($json['ocupacao'] == 6) $flag = 'municipal';

        if($json['ocupacao'] == 5 || $json['ocupacao'] == 6){
            $margem = $uteisClass->removeMask($json[$flag]['margem'], 'money');
            $estadualMunicipal->setMargemInfo($json[$flag]['margemRadio']);
            $estadualMunicipal->setMargemValor($margem);
            $estadualMunicipal->setMatricula($json[$flag]['matricula']);
            $estadualMunicipal->setPassword($json[$flag]['password']);
            $estadualMunicipal->setImageName($json[$flag]['file']['imageName']);
            $img = $uteisClass->base64_to_jpeg($json[$flag]['file']['imageUrl'], $json[$flag]['file']['imageName']);
            $estadualMunicipal->setImageUrl($img);
            $estadualMunicipal->setImageFile(null);
        }

        $adicionaisController = new AdicionaisController();
        $retorno = $adicionaisController->insert($estadualMunicipal, $financeiro, $arrayParentesco, $flag);

        if ($retorno['status'] == 200){
            $retorno['token'] = $authorization->gerarToken($retorno['user_id']);
            unset($retorno['user_id']);
        }

        return $response->withJson($retorno, $retorno['status']);

    });

    $this->post('/cliente/finalize', function ($request, $response, $args) {
        $json = $request->getParsedBody();
        $authorization = new Authorization();
        $finalize = new Finalize();
        $uteisClass = new Uteis();

        $check = $authorization->verificarToken($request);
        if ($check['status'] != 200) {
            return $response->withJson($check, $check['status']);
            die;
        }

        $finalize->setCliId($check["token"]->data);
        $finalize->setRg($uteisClass->base64_to_jpeg($json['rg']['imageUrl'], $json['rg']['imageName']));
        $finalize->setCpf($uteisClass->base64_to_jpeg($json['cpf']['imageUrl'], $json['cpf']['imageName']));
        $finalize->setCompResidencia($uteisClass->base64_to_jpeg($json['comprovante']['imageUrl'], $json['comprovante']['imageName']));
        $finalize->setContraCheque($uteisClass->base64_to_jpeg($json['contraCheque']['imageUrl'], $json['contraCheque']['imageName']));
        $finalize->setCarteiraTrabalho($uteisClass->base64_to_jpeg($json['ctps']['imageUrl'], $json['ctps']['imageName']));
        $finalize->setImpostoRenda($uteisClass->base64_to_jpeg($json['imposto']['imageUrl'], $json['imposto']['imageName']));

        $finalizeController = new FinalizeController();
        $retorno = $finalizeController->insert($finalize);

        if ($retorno['status'] == 200){
            $retorno['token'] = $authorization->gerarToken($retorno['user_id']);
            unset($retorno['user_id']);
        }

        return $response->withJson($retorno, $retorno['status']);

    });
});