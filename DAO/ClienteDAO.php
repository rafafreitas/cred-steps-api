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
class ClienteDAO
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

        $sql = "INSERT INTO clientes (cli_nome, cli_cpf, cli_telefone, cli_nascimento, cli_email, 
                                      cli_emprestimo, cli_parcelas, cli_status, cli_cadastro, tipo_id)
                    VALUES ( ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);

        $sql2 = "INSERT INTO cliente_ocupacao (cli_id, ocup_id, cli_estado, cli_cidade, cli_empresa)
                    VALUES (?, ?, ?, ?, ?)";
        $stmt2 = $conn->prepare($sql2);

        try {
            $data = date('Y-m-d H:i:s');

            $stmt->bindValue(1,$cliente->getNome());
            $stmt->bindValue(2,$cliente->getCpf());
            $stmt->bindValue(3,$cliente->getTelefone());
            $stmt->bindValue(4,$cliente->getNascimento());
            $stmt->bindValue(5,$cliente->getEmail());
            $stmt->bindValue(6,$cliente->getValorEmprestimo());
            $stmt->bindValue(7,$cliente->getParcelas());
            $stmt->bindValue(8,$cliente->getStatus());
            $stmt->bindValue(9,$data);
            $stmt->bindValue(10,$cliente->getTipoId());
            $stmt->execute();

            $last_id = $conn->lastInsertId();

            $stmt2->bindValue(1,$last_id);
            $stmt2->bindValue(2,$ocupacao->getOpcao());
            $stmt2->bindValue(3,$ocupacao->getEstado());
            $stmt2->bindValue(4,$ocupacao->getCidade());
            $stmt2->bindValue(5,$ocupacao->getEmpresa());
            $stmt2->execute();

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

        $sql2 = "UPDATE cliente_ocupacao
                SET  ocup_id  = ?,
                     cli_estado = ?,
                     cli_cidade = ?,
                     cli_empresa = ?
                WHERE cli_id = ?";
        $stmt2 = $conn->prepare($sql2);

        try {

            $stmt->bindValue(1,$cliente->getNome());
            $stmt->bindValue(2,$cliente->getCpf());
            $stmt->bindValue(3,$cliente->getTelefone());
            $stmt->bindValue(4,$cliente->getNascimento());
            $stmt->bindValue(5,$cliente->getEmail());
            $stmt->bindValue(6,$cliente->getValorEmprestimo());
            $stmt->bindValue(7,$cliente->getParcelas());
            $stmt->bindValue(8,$cliente->getId());
            $stmt->execute();

            $stmt2->bindValue(1,$ocupacao->getOpcao());
            $stmt2->bindValue(2,$ocupacao->getEstado());
            $stmt2->bindValue(3,$ocupacao->getCidade());
            $stmt2->bindValue(4,$ocupacao->getEmpresa());
            $stmt2->bindValue(5,$ocupacao->getCliId());
            $stmt2->execute();

            return array(
                'status'    => 200,
                'message'   => "SUCCESS",
                'result'    => "Cliente atualizado!",
                'user_id'   => $cliente->getId()
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