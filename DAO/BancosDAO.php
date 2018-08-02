<?php
/**
 * Created by PhpStorm.
 * User: r.a.freitas
 * Date: 01/08/2018
 * Time: 21:52
 */

require_once "Basics/Bancos.php";
require_once 'Connection/Conexao.php';
class BancosDAO
{
    public function getBancos(){

        $conn = \Database::conexao();
        $sql = "SELECT banco_id, cod, banco 
                FROM bancos;";
        $stmt = $conn->prepare($sql);

        try {
            $stmt->execute();
            $count = $stmt->rowCount();
            $resultBancos = $stmt->fetchAll(PDO::FETCH_OBJ);

            if ($count != 0) {
                return array(
                    'status'    => 200,
                    'message'   => "INFO",
                    'qtd'       => $count,
                    'result'    => $resultBancos
                );
            }else{
                return array(
                    'status'    => 200,
                    'message'   => "INFO",
                    'qtd'       => 0,
                    'result'    => 'Você não possui bancos cadastrados!'
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