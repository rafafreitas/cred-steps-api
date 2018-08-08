<?php
/**
 * Created by PhpStorm.
 * User: Rafael Freitas
 * Date: 06/08/2018
 * Time: 22:54
 */

require_once 'Basics/Finalize.php';
require_once 'Controller/SendEmail.php';
require_once 'Connection/Conexao.php';
class FinalizeDAO
{
    public function insert(Finalize $finalize){
        $conn = \Database::conexao();

        $sql = "INSERT INTO cliente_finalize (cli_id, rg, cpf, compResidencia, contraCheque, carteiraTrabalho, impostoRenda)
                    VALUES ( ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);

        $sql2 = "DELETE from cliente_finalize WHERE cli_id = ?";
        $stmt2 = $conn->prepare($sql2);

        try {

            $stmt2->bindValue(1,$finalize->getCliId());
            $stmt2->execute();

            $stmt->bindValue(1,$finalize->getCliId());
            $stmt->bindValue(2,$finalize->getRg());
            $stmt->bindValue(3,$finalize->getCpf());
            $stmt->bindValue(4,$finalize->getCompResidencia());
            $stmt->bindValue(5,$finalize->getContraCheque());
            $stmt->bindValue(6,$finalize->getCarteiraTrabalho());
            $stmt->bindValue(7,$finalize->getImpostoRenda());
            $stmt->execute();

            $sendEmail = new SendEmail();
            $sendEmail->prepareEmail(false, $finalize->getCliId());

            return array(
                'status'    => 200,
                'message'   => "SUCCESS",
                'result'    => "Documentos cadastrados com sucesso!",
                'user_id'   => $finalize->getCliId()
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

    public function finalize($cli_id){
        $conn = \Database::conexao();

        $sql3 = "UPDATE clientes
                SET  cli_status  = 2
                WHERE cli_id = ?";
        $stmt3 = $conn->prepare($sql3);

        try {

            $stmt3->bindValue(1,$cli_id);
            $stmt3->execute();

            return array(
                'status'    => 200,
                'message'   => "SUCCESS",
                'result'    => "Status do usuário alterado!",
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