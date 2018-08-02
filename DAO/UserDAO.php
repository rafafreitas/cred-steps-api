<?php
/**
 * Created by PhpStorm.
 * User: Rafael Freitas
 * Date: 01/08/2018
 * Time: 19:43
 */

require_once "Basics/User.php";
require_once 'Connection/Conexao.php';
class UserDAO
{
    public function getUsers(){

        $conn = \Database::conexao();
        $sql = "SELECT user_id, user_nome, user_cpf, user_telefone, user_data, user_status, 
                       user_cadastro, tipo_id 
                FROM usuarios;";
        $stmt = $conn->prepare($sql);

        try {
            $stmt->execute();
            $count = $stmt->rowCount();
            $resultUsuario = $stmt->fetchAll(PDO::FETCH_OBJ);

            if ($count != 0) {
                return array(
                    'status'    => 200,
                    'message'   => "INFO",
                    'qtd'       => $count,
                    'result'    => $resultUsuario
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
}