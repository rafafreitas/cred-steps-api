<?php
/**
 * Created by PhpStorm.
 * User: Rafael Freitas
 * Date: 01/08/2018
 * Time: 23:06
 */

require_once 'Connection/Conexao.php';
class EnderecosDAO
{
    public function getEstados(){

        $conn = \Database::conexao();
        $sql = "SELECT Id, CodigoUf, Nome, Uf, Regiao
                FROM estados;";
        $stmt = $conn->prepare($sql);

        try {
            $stmt->execute();
            $count = $stmt->rowCount();
            $resultEstados = $stmt->fetchAll(PDO::FETCH_OBJ);

            if ($count != 0) {
                return array(
                    'status'    => 200,
                    'message'   => "INFO",
                    'qtd'       => $count,
                    'result'    => $resultEstados
                );
            }else{
                return array(
                    'status'    => 200,
                    'message'   => "INFO",
                    'qtd'       => 0,
                    'result'    => 'Você não possui estados cadastrados!'
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

    public function getCidades($uf){

        $conn = \Database::conexao();
        $sql = "SELECT DISTINCT Id, Codigo, Nome from cidades WHERE Uf = ? ORDER BY Nome ASC";
        $stmt = $conn->prepare($sql);

        try {
            $stmt->bindValue(1,$uf, PDO::PARAM_STR);
            $stmt->execute();
            $count = $stmt->rowCount();
            $resultCidades = $stmt->fetchAll(PDO::FETCH_OBJ);

            if ($count != 0) {
                return array(
                    'status'    => 200,
                    'message'   => "INFO",
                    'qtd'       => $count,
                    'result'    => $resultCidades
                );
            }else{
                return array(
                    'status'    => 200,
                    'message'   => "INFO",
                    'qtd'       => 0,
                    'result'    => 'Você não possui cidades cadastradas!'
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