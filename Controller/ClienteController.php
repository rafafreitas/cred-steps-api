<?php
/**
 * Created by PhpStorm.
 * Cliente: Rafael Freitas
 * Date: 01/08/2018
 * Time: 19:42
 */

require_once 'Basics/Cliente.php';
require_once 'Basics/Ocupacao.php';
require_once 'DAO/ClienteDAO.php';
class ClienteController
{
    public function getUsers(){
        $userDAO = new ClienteDAO();
        return $userDAO->getUsers();
    }

    public function insert(Cliente $cliente, Ocupacao $ocupacao){

        if (empty($cliente->getValorEmprestimo())) {
            return array('status' => 500, 'message' => "ERROR", 'result' => 'Informe o valor do empréstimo!');
            die;
        }
        if (empty($cliente->getParcelas())) {
            return array('status' => 500, 'message' => "ERROR", 'result' => 'Informe o valor das parcelas!');
            die;
        }
        if (empty($cliente->getNome())) {
            return array('status' => 500, 'message' => "ERROR", 'result' => 'Informe o nome!');
            die;
        }
        if (empty($cliente->getTelefone())) {
            return array('status' => 500, 'message' => "ERROR", 'result' => 'Informe o telefone!');
            die;
        }
        if (empty($cliente->getCpf())) {
            return array('status' => 500, 'message' => "ERROR", 'result' => 'Informe o CPF!');
            die;
        }
        if (empty($cliente->getNascimento())) {
            return array('status' => 500, 'message' => "ERROR", 'result' => 'Informe a data de nascimento!');
            die;
        }

        if (empty($ocupacao->getOpcao())) {
            return array('status' => 500, 'message' => "ERROR", 'result' => 'Informe a ocupação!');
            die;
        }

        $clienteDAO = new UserDAO();
        return $clienteDAO->insert($cliente, $ocupacao);
    }
}