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

    public function getCliente($cli_id){

        $conn = \Database::conexao();
        $sql = "SELECT cli.cli_id, 
                       cli.cli_nome, 
                       cli.cli_indicacao, 
                       cli.cli_cpf, 
                       cli.cli_telefone, 
                       DATE_FORMAT(cli.cli_nascimento, '%d/%m/%Y') as cliNascimento,
                       DATE_FORMAT(cli.cli_cadastro, '%d/%m/%Y') as cliCadastro,
                       cli.cli_email, 
                       cli.cli_emprestimo, 
                       cli.cli_parcelas, 
                       
                       occl.ocup_id,
                       occl.cli_estado,
                       occl.cli_cidade,
                       occl.cli_empresa,
                       
                       ocup.ocup_nome,
                       cid.Nome,
                       
                       mo.motivo_id,
                       mot.motivo_nome,
                       mo.motivo_tratamento,
                       DATE_FORMAT(mo.data_festa, '%d/%m/%Y') as dataFesta, 
                       
                       fi.spc,
                       fi.cheque,
                       fi.chequeDev,
                       fi.emprego,
                       fi.rendaComprovada,
                       fi.bank_possui,
                       fi.bank_id,
                       ban.banco,
                       fi.bank_tempo_conta,
                       fi.bank_agencia,
                       fi.bank_conta
                       
                       FROM clientes cli
                       LEFT JOIN cliente_ocupacao occl
                       ON cli.cli_id = occl.cli_id
                       
                       LEFT JOIN cliente_motivo mo
                       ON cli.cli_id = mo.cli_id
                       
                       LEFT JOIN cliente_financeiro fi
                       ON cli.cli_id = fi.cli_id
                       
                       LEFT JOIN ocupacao ocup
                       ON occl.ocup_id = ocup.ocup_id
                       
                       LEFT JOIN cidades cid
                       ON occl.cli_cidade = cid.Id
                       
                       LEFT JOIN motivos mot
                       ON mo.motivo_id = mot.motivo_id
                       
                       LEFT JOIN bancos ban
                       ON fi.bank_id = ban.banco_id
                       
                       WHERE cli.cli_id = ?;";
        $stmt = $conn->prepare($sql);

        $sql2 = "SELECT cre.cred_id,
                        con.cred_nome,
                        cre.limite_cartao
                        
                        FROM cliente_credito cre
                        LEFT JOIN conseguir_credito con
                        ON cre.cred_id = con.cred_id
                        
                        WHERE cre.cli_id = ?;";
        $stmt2 = $conn->prepare($sql2);

        $sql3 = "SELECT margemOption, margem, matricula, password, imageName, imageUrl
                FROM cliente_estadual_municipal WHERE cli_id = ?;";
        $stmt3 = $conn->prepare($sql3);

        $sql4 = "SELECT rg, cpf, compResidencia, contraCheque, carteiraTrabalho, impostoRenda
                 FROM cliente_finalize WHERE cli_id = ?;";
        $stmt4 = $conn->prepare($sql4);

        $sql5 = "SELECT par.grau, 
                        par.proximidade, 
                        par.nome, 
                        par.cpf, 
                        par.telefone, 
                        DATE_FORMAT(par.nascimento, '%d/%m/%Y') as cliNascimento,
                       
                        par.ocupacao,
                        ocup.ocup_nome,
                        par.estado,
                        cid.Nome,
                        par.empresa 
                       
                       FROM cliente_parentesco par
                       
                       LEFT JOIN ocupacao ocup
                       ON par.ocupacao = ocup.ocup_id
                       
                       LEFT JOIN cidades cid
                       ON par.cidade = cid.Id
                       
                       
                       WHERE par.cli_id = ?;";
        $stmt5 = $conn->prepare($sql5);

        try {

            $stmt->bindValue(1,$cli_id);
            $stmt->execute();

            $stmt2->bindValue(1,$cli_id);
            $stmt2->execute();

            $stmt3->bindValue(1,$cli_id);
            $stmt3->execute();

            $stmt4->bindValue(1,$cli_id);
            $stmt4->execute();

            $stmt5->bindValue(1,$cli_id);
            $stmt5->execute();

            $resultCliente = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $resultCredito = $stmt2->fetchAll(PDO::FETCH_ASSOC);
            $resultEstMuni = $stmt3->fetchAll(PDO::FETCH_ASSOC);
            $resultFinalize = $stmt4->fetchAll(PDO::FETCH_ASSOC);
            $resultParentesco = $stmt5->fetchAll(PDO::FETCH_ASSOC);

            $resultCliente[0]['credito'] = $resultCredito;
            $resultCliente[0]['estadMuni'] = $resultEstMuni[0];
            $resultCliente[0]['finalize'] = $resultFinalize[0];
            $resultCliente[0]['parentesco'] = $resultParentesco;

            return array(
                'status'    => 200,
                'message'   => "INFO",
                'result'    => $resultCliente[0]
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

    public function getClientes(){
        $data = date('Y-m-d H:i:s');
        $conn = \Database::conexao();
        $sql = "SELECT cli.cli_id, 
                       cli.cli_nome, 
                       cli.cli_indicacao, 
                       cli.cli_cpf, 
                       cli.cli_telefone, 
                       DATE_FORMAT(cli.cli_nascimento, '%d/%m/%Y') as cliNascimento,
                       DATE_FORMAT(cli.cli_cadastro, '%d/%m/%Y') as cliCadastro,
                       cli.cli_email, 
                       cli.cli_emprestimo, 
                       cli.cli_parcelas, 
                       
                       occl.ocup_id,
                       occl.cli_estado,
                       occl.cli_cidade,
                       occl.cli_empresa,
                       
                       ocup.ocup_nome,
                       cid.Nome,
                       
                       mo.motivo_id,
                       mot.motivo_nome,
                       mo.motivo_tratamento,
                       DATE_FORMAT(mo.data_festa, '%d/%m/%Y') as dataFesta, 
                       
                       fi.spc,
                       fi.cheque,
                       fi.chequeDev,
                       fi.emprego,
                       fi.rendaComprovada,
                       fi.bank_possui,
                       fi.bank_id,
                       fi.bank_tempo_conta,
                       fi.bank_agencia,
                       fi.bank_conta
                       
                       FROM clientes cli
                       LEFT JOIN cliente_ocupacao occl
                       ON cli.cli_id = occl.cli_id
                       
                       LEFT JOIN cliente_motivo mo
                       ON cli.cli_id = mo.cli_id
                       
                       LEFT JOIN cliente_financeiro fi
                       ON cli.cli_id = fi.cli_id
                       
                       LEFT JOIN ocupacao ocup
                       ON occl.ocup_id = ocup.ocup_id
                       
                       LEFT JOIN cidades cid
                       ON occl.cli_cidade = cid.Id
                       
                       LEFT JOIN motivos mot
                       ON mo.motivo_id = mot.motivo_id
                       
                       WHERE cli.cli_status = 1 
                       AND cli.cli_cadastro < date_sub('".$data."', interval 15 minute);";
        $stmt = $conn->prepare($sql);

        $sql2 = "SELECT cre.cred_id,
                        con.cred_nome,
                        cre.limite_cartao
                        
                        FROM cliente_credito cre
                        LEFT JOIN conseguir_credito con
                        ON cre.cred_id = con.cred_id
                        
                        WHERE cre.cli_id = ?;";
        $stmt2 = $conn->prepare($sql2);

        $sql3 = "SELECT margemOption, margem, matricula, password, imageName, imageUrl
                FROM cliente_estadual_municipal WHERE cli_id = ?;";
        $stmt3 = $conn->prepare($sql3);

        $sql4 = "SELECT rg, cpf, compResidencia, contraCheque, carteiraTrabalho, impostoRenda
                 FROM cliente_finalize WHERE cli_id = ?;";
        $stmt4 = $conn->prepare($sql4);

        $sql5 = "SELECT par.grau, 
                        par.proximidade, 
                        par.nome, 
                        par.cpf, 
                        par.telefone, 
                        DATE_FORMAT(par.nascimento, '%d/%m/%Y') as cliNascimento,
                       
                        par.ocupacao,
                        ocup.ocup_nome,
                        par.estado,
                        cid.Nome,
                        par.empresa 
                       
                       FROM cliente_parentesco par
                       
                       LEFT JOIN ocupacao ocup
                       ON par.ocupacao = ocup.ocup_id
                       
                       LEFT JOIN cidades cid
                       ON par.cidade = cid.Id
                       
                       
                       WHERE par.cli_id = ?;";
        $stmt5 = $conn->prepare($sql5);

        try {

            $stmt->execute();
            $resultCliente = $stmt->fetchAll(PDO::FETCH_ASSOC);

            foreach ($resultCliente as $key => $value) {
                $stmt2->bindValue(1,$value['cli_id']);
                $stmt2->execute();

                $stmt3->bindValue(1,$value['cli_id']);
                $stmt3->execute();

                $stmt4->bindValue(1,$value['cli_id']);
                $stmt4->execute();

                $stmt5->bindValue(1,$value['cli_id']);
                $stmt5->execute();

                $resultCredito = $stmt2->fetchAll(PDO::FETCH_ASSOC);
                $resultEstMuni = $stmt3->fetchAll(PDO::FETCH_ASSOC);
                $resultFinalize = $stmt4->fetchAll(PDO::FETCH_ASSOC);
                $resultParentesco = $stmt5->fetchAll(PDO::FETCH_ASSOC);

                $resultCliente[$key]['credito'] = $resultCredito;
                $resultCliente[$key]['estadMuni'] = $resultEstMuni[0];
                $resultCliente[$key]['finalize'] = $resultFinalize[0];
                $resultCliente[$key]['parentesco'] = $resultParentesco;

            }

            return array(
                'status'    => 200,
                'message'   => "INFO",
                'result'    => $resultCliente
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

    public function insert(Cliente $cliente, Ocupacao $ocupacao){
        $conn = \Database::conexao();

        $sql = "INSERT INTO clientes (cli_nome, cli_cpf, cli_telefone, cli_nascimento, cli_email, 
                                      cli_emprestimo, cli_parcelas, cli_status, cli_cadastro, tipo_id, cli_indicacao)
                    VALUES ( ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
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
            $stmt->bindValue(11,$cliente->getIndicacao());
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

        $data = date('Y-m-d H:i:s');

        $sql = "UPDATE clientes
                SET  cli_nome  = ?,
                     cli_cpf = ?,
                     cli_telefone = ?,
                     cli_nascimento = ?,
                     cli_email = ?,
                     cli_emprestimo = ?,
                     cli_parcelas = ?,
                     cli_cadastro = ?, 
                     cli_indicacao = ?
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
            $stmt->bindValue(8,$data);
            $stmt->bindValue(9,$cliente->getIndicacao());
            $stmt->bindValue(10,$cliente->getId());
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