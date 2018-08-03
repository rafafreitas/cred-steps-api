<?php
/**
 * Created by PhpStorm.
 * User: Rafael Freitas
 * Date: 01/08/2018
 * Time: 19:43
 */

require_once "Basics/Cliente.php";
require_once 'Basics/Ocupacao.php';
require_once 'Connection/Conexao.php';
class ClientesDAO
{
    public function verifyCliente($cpf){

        $conn = \Database::conexao();
        $sql = "SELECT cli_id 
                FROM clientes
                WHERE cli_cpf = ? 
                AND cli_status = 1;";
        $stmt = $conn->prepare($sql);

        try {
            $stmt->bindValue(1,$cpf);
            $stmt->execute();
            $count = $stmt->rowCount();
            $resultCliente = $stmt->fetchAll(PDO::FETCH_OBJ);

            if ($count != 0) {
                return array(
                    'status'    => 401,
                    'message'   => "INFO",
                    'qtd'       => $count,
                    'result'    => $resultCliente
                );
            }else{
                return array(
                    'status'    => 200,
                    'message'   => "INFO",
                    'qtd'       => 0,
                    'result'    => 'Este cliente pode fazer uma nova solicitação.'
                );
            }

        } catch (PDOException $ex) {
            return array(
                'status'    => 500,
                'message'   => "ERROR",
                'result'    => 'Erro na execução da instrução!',
                'CODE'      => $ex->getCode(),
                'Exception' => $ex->getMessage(),
            );
        }

    }

    public function getClientes(){

        $conn = \Database::conexao();
        $sql = "SELECT cli_id, cli_nome, cli_cpf, cli_telefone, cli_data, cli_status, 
                       cli_cadastro, tipo_id 
                FROM clientes;";
        $stmt = $conn->prepare($sql);

        try {
            $stmt->execute();
            $count = $stmt->rowCount();
            $resultCliente = $stmt->fetchAll(PDO::FETCH_OBJ);

            if ($count != 0) {
                return array(
                    'status'    => 200,
                    'message'   => "INFO",
                    'qtd'       => $count,
                    'result'    => $resultCliente
                );
            }else{
                return array(
                    'status'    => 200,
                    'message'   => "INFO",
                    'qtd'       => 0,
                    'result'    => 'Você não possui usuários!'
                );
            }

        } catch (PDOException $ex) {
            return array(
                'status'    => 500,
                'message'   => "ERROR",
                'result'    => 'Erro na execução da instrução!',
                'CODE'      => $ex->getCode(),
                'Exception' => $ex->getMessage(),
            );
        }

    }

    public function insert(Cliente $cliente, Ocupacao $ocupacao){
        $conn = \Database::conexao();

        $sql = "INSERT INTO clientes (cli_nome, cli_cpf, cli_telefone, cli_nascimento, cli_email
                                      cli_emprestimo, cli_parcelas, cli_status, cli_cadastro, tipo_id)
                    VALUES ( ?, ?, ?, ?, ?, ?, ?, ?, NOW(), ?)";
        $stmt = $conn->prepare($sql);

        try {

            $stmt->bindValue(1,$cliente->getNome());
            $stmt->bindValue(2,$cliente->getCpf());
            $stmt->bindValue(3,$cliente->getTelefone());
            $stmt->bindValue(4,$cliente->getNascimento());
            $stmt->bindValue(5,$cliente->getEmail());
            $stmt->bindValue(6,$cliente->getValorEmprestimo());
            $stmt->bindValue(7,$cliente->getParcelas());
            $stmt->bindValue(8,$cliente->getStatus());
            $stmt->bindValue(9,$cliente->getTipoId());
            $stmt->execute();

            $last_id = $conn->lastInsertId();

            return array(
                'status'    => 200,
                'message'   => "SUCCESS",
                'result'    => "Cliente cadastrado!",
                'user_id'   => $last_id
            );

        } catch (PDOException $ex) {
            return array(
                'status'    => 500,
                'message'   => "ERROR",
                'result'    => 'Erro na execução da instrução!',
                'CODE'      => $ex->getCode(),
                'Exception' => $ex->getMessage(),
            );
        }
    }

    public function update(Cliente $cliente, Ocupacao $ocupacao){
        $conn = \Database::conexao();

        $sql = "UPDATE clientes
                SET  cli_nome  = ?,
                     cli_cpf = ?,
                     cli_telefone = ?,
                     cli_nascimento = ?,
                     cli_email = ?,
                     cli_emprestimo = ?,
                     cli_parcelas = ?
                WHERE cli_id = ?";
        $stmt = $conn->prepare($sql);

        try {

            $stmt->bindValue(1,$cliente->getNome());
            $stmt->bindValue(2,$cliente->getCpf());
            $stmt->bindValue(3,$cliente->getTelefone());
            $stmt->bindValue(4,$cliente->getNascimento());
            $stmt->bindValue(5,$cliente->getEmail());
            $stmt->bindValue(6,$cliente->getValorEmprestimo());
            $stmt->bindValue(7,$cliente->getParcelas());
            $stmt->execute();


            return array(
                'status'    => 200,
                'message'   => "SUCCESS",
                'result'    => "Cliente atualizado!"
            );

        } catch (PDOException $ex) {
            return array(
                'status'    => 500,
                'message'   => "ERROR",
                'result'    => 'Erro na execução da instrução!',
                'CODE'      => $ex->getCode(),
                'Exception' => $ex->getMessage(),
            );
        }
    }
}