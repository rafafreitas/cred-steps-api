<?php
/**
 * Created by PhpStorm.
 * User: Rafael Freitas
 * Date: 04/08/2018
 * Time: 21:59
 */

require_once 'Basics/EstadualMunicipal.php';
require_once 'Basics/Financeiro.php';
require_once 'Basics/Parentesco.php';
require_once 'Connection/Conexao.php';
class AdicionaisDAO
{
    public function verifyAdicionais($cli_id){

        $conn = \Database::conexao();
        $sql = "SELECT cli_financeiro_id 
                FROM cliente_financeiro
                WHERE cli_id = ?;";
        $stmt = $conn->prepare($sql);

        try {
            $stmt->bindValue(1,$cli_id);
            $stmt->execute();
            $count = $stmt->rowCount();
            $resultFinanceiro = $stmt->fetchAll(PDO::FETCH_OBJ);

            if ($count != 0) {
                return array(
                    'status'    => 401,
                    'message'   => "INFO",
                    'qtd'       => $count,
                    'result'    => $resultFinanceiro
                );
            }else{
                return array(
                    'status'    => 200,
                    'message'   => "INFO",
                    'qtd'       => 0,
                    'result'    => 'Este cliente pode adicionar novos dados.'
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

    public function insert(EstadualMunicipal $estadualMunicipal, Financeiro $financeiro, $parentesco, $flag){
        $conn = \Database::conexao();

        $sql = "INSERT INTO cliente_financeiro (cli_id, spc, cheque, chequeDev, emprego, rendaComprovada, 
                            bank_possui, bank_id, bank_tempo_conta, bank_agencia, bank_conta)
                    VALUES ( ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);

        $sql2 = "INSERT INTO cliente_parentesco (cli_id, grau, proximidade, nome, 
                             cpf, telefone, nascimento, ocupacao, estado, cidade, empresa)
                    VALUES ( ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt2 = $conn->prepare($sql2);

        $sql3 = "INSERT INTO cliente_estadual_municipal (cli_id, margemOption, margem, 
                             matricula, password, imageName, imageUrl, imageFile)
                    VALUES ( ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt3 = $conn->prepare($sql3);

        try {
            $stmt->bindValue(1,$financeiro->getCliId());
            $stmt->bindValue(2,$financeiro->getSpc());
            $stmt->bindValue(3,$financeiro->getCheque());
            $stmt->bindValue(4,$financeiro->getChequeDev());
            $stmt->bindValue(5,$financeiro->getEmprego());
            $stmt->bindValue(6,$financeiro->getRendaComprovada());
            $stmt->bindValue(7,$financeiro->getBankPossui());
            $stmt->bindValue(8,$financeiro->getBankId());
            $stmt->bindValue(9,$financeiro->getBankTempoConta());
            $stmt->bindValue(10,$financeiro->getBankAgencia());
            $stmt->bindValue(11,$financeiro->getBankConta());
            $stmt->execute();

            foreach ($parentesco as $key => $value) {

                $stmt2->bindValue(1,$financeiro->getCliId());
                $stmt2->bindValue(2,$value['grau']);
                $stmt2->bindValue(3,$value['proximidade']);
                $stmt2->bindValue(4,$value['nome']);
                $stmt2->bindValue(5,$value['cpf']);
                $stmt2->bindValue(6,$value['telefone']);
                $stmt2->bindValue(7,$value['nascimento']);
                $stmt2->bindValue(8,$value['ocupacao']);
                $stmt2->bindValue(9,$value['estado']);
                $stmt2->bindValue(10,$value['cidade']);
                $stmt2->bindValue(11,$value['empresa']);
                $stmt2->execute();

            }

            if($flag){
                $stmt3->bindValue(1,$financeiro->getCliId());
                $stmt3->bindValue(2,$estadualMunicipal->getMargemInfo());
                $stmt3->bindValue(3,$estadualMunicipal->getMargemValor());
                $stmt3->bindValue(4,$estadualMunicipal->getMatricula());
                $stmt3->bindValue(5,$estadualMunicipal->getPassword());
                $stmt3->bindValue(6,$estadualMunicipal->getImageName());
                $stmt3->bindValue(7,$estadualMunicipal->getImageUrl());
                $stmt3->bindValue(8,$estadualMunicipal->getImageFile());
                $stmt3->execute();
            }

            return array(
                'status'    => 200,
                'message'   => "SUCCESS",
                'result'    => "Dados adicionais cadastrados!",
                'user_id'   => $financeiro->getCliId()
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

    public function update(EstadualMunicipal $estadualMunicipal, Financeiro $financeiro, $parentesco, $flag){
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