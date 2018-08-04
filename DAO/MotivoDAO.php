<?php
/**
 * Created by PhpStorm.
 * User: Rafael Freitas
 * Date: 04/08/2018
 * Time: 10:57
 */

require_once 'Basics/Motivo.php';
require_once 'Basics/Credito.php';
require_once 'Connection/Conexao.php';
class MotivoDAO
{
    public function verifyMotivos($cli_id){

        $conn = \Database::conexao();
        $sql = "SELECT cli_motivo_id 
                FROM cliente_motivo
                WHERE cli_id = ?;";
        $stmt = $conn->prepare($sql);

        try {
            $stmt->bindValue(1,$cli_id);
            $stmt->execute();
            $count = $stmt->rowCount();
            $resultMotivos = $stmt->fetchAll(PDO::FETCH_OBJ);

            if ($count != 0) {
                return array(
                    'status'    => 401,
                    'message'   => "INFO",
                    'qtd'       => $count,
                    'result'    => $resultMotivos
                );
            }else{
                return array(
                    'status'    => 200,
                    'message'   => "INFO",
                    'qtd'       => 0,
                    'result'    => 'Este cliente pode adicionar novos motivos.'
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

    public function insert(Motivo $motivo, Credito $credito){
        $conn = \Database::conexao();

        $sql = "INSERT INTO cliente_motivo (cli_id, motivo_id, motivo_tratamento, data_festa)
                    VALUES ( ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);

        $sql2 = "INSERT INTO cliente_credito (cli_id, cred_id, limite_cartao)
                    VALUES (?, ?, ?)";
        $stmt2 = $conn->prepare($sql2);

        try {
            $stmt->bindValue(1,$motivo->getCliId());
            $stmt->bindValue(2,$motivo->getMotivoId());
            $stmt->bindValue(3,$motivo->getTratamento());
            $stmt->bindValue(4,$motivo->getDataFesta());
            $stmt->execute();


            foreach ($credito->getCredId() as $key => $value){
                $limite = ($value == 2) ? $credito->getLimiteCartao() : null ;
                $stmt2->bindValue(1,$credito->getCliId());
                $stmt2->bindValue(2,$value);
                $stmt2->bindValue(3,$limite);
                $stmt2->execute();
            }


            return array(
                'status'    => 200,
                'message'   => "SUCCESS",
                'result'    => "Motivos e Créditos cadastrados!",
                'user_id'   => $motivo->getCliId()
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

    public function update(Motivo $motivo, Credito $credito){
        $conn = \Database::conexao();

        $sql = "UPDATE cliente_motivo
                SET  motivo_id = ?,
                     motivo_tratamento = ?,
                     data_festa = ?
                WHERE cli_id = ?";
        $stmt = $conn->prepare($sql);

        $sql2 = "DELETE from cliente_credito WHERE cli_id = ?";
        $stmt2 = $conn->prepare($sql2);

        $sql3 = "INSERT INTO cliente_credito (cli_id, cred_id, limite_cartao)
                    VALUES (?, ?, ?)";
        $stmt3 = $conn->prepare($sql3);

        try {

            $stmt->bindValue(1,$motivo->getMotivoId());
            $stmt->bindValue(2,$motivo->getTratamento());
            $stmt->bindValue(3,$motivo->getDataFesta());
            $stmt->bindValue(4,$motivo->getCliId());
            $stmt->execute();

            $stmt2->bindValue(1,$credito->getCliId());
            $stmt2->execute();

            foreach ($credito->getCredId() as $key => $value){
                $limite = ($value == 2) ? $credito->getLimiteCartao() : null ;
                $stmt3->bindValue(1,$credito->getCliId());
                $stmt3->bindValue(2,$value);
                $stmt3->bindValue(3,$limite);
                $stmt3->execute();
            }

            return array(
                'status'    => 200,
                'message'   => "SUCCESS",
                'result'    => "Motivos e créditos atualizados!",
                'user_id'   => $motivo->getCliId()
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