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

        $sql = "INSERT INTO cliente_financeiro (cli_id, spc, cheque, chequeDev, emprego, rendaComprovada, rendaValor,
                            bank_possui, bank_id, bank_tempo_conta, bank_agencia, bank_conta, bank_bb_possui, 
                            bank_bb_agencia, bank_bb_conta)
                    VALUES ( ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
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
            $stmt->bindValue(7,$financeiro->getRendaValor());
            $stmt->bindValue(8,$financeiro->getBankPossui());
            $stmt->bindValue(9,$financeiro->getBankId());
            $stmt->bindValue(10,$financeiro->getBankTempoConta());
            $stmt->bindValue(11,$financeiro->getBankAgencia());
            $stmt->bindValue(12,$financeiro->getBankConta());
            $stmt->bindValue(13,$financeiro->getBankBbPossui());
            $stmt->bindValue(14,$financeiro->getBankBbAgencia());
            $stmt->bindValue(15,$financeiro->getBankBbConta());
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

        $sql = "UPDATE cliente_financeiro
                SET  spc = ?,
                     cheque = ?,
                     chequeDev = ?,
                     emprego = ?,
                     rendaComprovada = ?,
                     rendaValor = ?,
                     bank_possui = ?,
                     bank_id = ?,
                     bank_tempo_conta = ?,
                     bank_agencia = ?,
                     bank_conta = ?,
                     bank_bb_possui = ?,
                     bank_bb_agencia = ?,
                     bank_bb_conta = ?
                WHERE cli_id = ?";
        $stmt = $conn->prepare($sql);

        $sql2 = "DELETE from cliente_parentesco WHERE cli_id = ?";
        $stmt2 = $conn->prepare($sql2);

        $sql3 = "DELETE from cliente_estadual_municipal WHERE cli_id = ?";
        $stmt3 = $conn->prepare($sql3);

        $sql4 = "INSERT INTO cliente_parentesco (cli_id, grau, proximidade, nome, 
                             cpf, telefone, nascimento, ocupacao, estado, cidade, empresa)
                    VALUES ( ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt4 = $conn->prepare($sql4);

        $sql5 = "INSERT INTO cliente_estadual_municipal (cli_id, margemOption, margem, 
                             matricula, password, imageName, imageUrl, imageFile)
                    VALUES ( ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt5 = $conn->prepare($sql5);

        try {

            $stmt->bindValue(1,$financeiro->getSpc());
            $stmt->bindValue(2,$financeiro->getCheque());
            $stmt->bindValue(3,$financeiro->getChequeDev());
            $stmt->bindValue(4,$financeiro->getEmprego());
            $stmt->bindValue(5,$financeiro->getRendaComprovada());
            $stmt->bindValue(6,$financeiro->getRendaValor());
            $stmt->bindValue(7,$financeiro->getBankPossui());
            $stmt->bindValue(8,$financeiro->getBankId());
            $stmt->bindValue(9,$financeiro->getBankTempoConta());
            $stmt->bindValue(10,$financeiro->getBankAgencia());
            $stmt->bindValue(11,$financeiro->getBankConta());
            $stmt->bindValue(12,$financeiro->getBankBbPossui());
            $stmt->bindValue(13,$financeiro->getBankBbAgencia());
            $stmt->bindValue(14,$financeiro->getBankBbConta());
            $stmt->bindValue(15,$financeiro->getCliId());
            $stmt->execute();

            $stmt2->bindValue(1,$financeiro->getCliId());
            $stmt2->execute();

            $stmt3->bindValue(1,$financeiro->getCliId());
            $stmt3->execute();

            foreach ($parentesco as $key => $value) {

                $stmt4->bindValue(1,$financeiro->getCliId());
                $stmt4->bindValue(2,$value['grau']);
                $stmt4->bindValue(3,$value['proximidade']);
                $stmt4->bindValue(4,$value['nome']);
                $stmt4->bindValue(5,$value['cpf']);
                $stmt4->bindValue(6,$value['telefone']);
                $stmt4->bindValue(7,$value['nascimento']);
                $stmt4->bindValue(8,$value['ocupacao']);
                $stmt4->bindValue(9,$value['estado']);
                $stmt4->bindValue(10,$value['cidade']);
                $stmt4->bindValue(11,$value['empresa']);
                $stmt4->execute();

            }

            if($flag){
                $stmt5->bindValue(1,$financeiro->getCliId());
                $stmt5->bindValue(2,$estadualMunicipal->getMargemInfo());
                $stmt5->bindValue(3,$estadualMunicipal->getMargemValor());
                $stmt5->bindValue(4,$estadualMunicipal->getMatricula());
                $stmt5->bindValue(5,$estadualMunicipal->getPassword());
                $stmt5->bindValue(6,$estadualMunicipal->getImageName());
                $stmt5->bindValue(7,$estadualMunicipal->getImageUrl());
                $stmt5->bindValue(8,$estadualMunicipal->getImageFile());
                $stmt5->execute();
            }

            return array(
                'status'    => 200,
                'message'   => "SUCCESS",
                'result'    => "Dados adicionais atualizados!",
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
}