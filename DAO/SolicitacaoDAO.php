<?php
/**
 * Created by PhpStorm.
 * User: r.a.freitas
 * Date: 29/10/2018
 * Time: 14:42
 */
require_once 'Connection/Conexao.php';
require_once 'Basics/Uteis.php';
class SolicitacaoDAO
{
    public function getSolicitacoes(){
        $data = date('Y-m-d H:i:s');
        $conn = \Database::conexao();
        $sql = "SELECT cli.cli_id, 
                       cli.cli_nome, 
                       cli.cli_indicacao, 
                       cli.cli_origem, 
                       cli.cli_cpf, 
                       cli.cli_telefone, 
                       DATE_FORMAT(cli.cli_nascimento, '%d/%m/%Y') as cliNascimento,
                       DATE_FORMAT(cli.cli_cadastro, '%d/%m/%Y') as cliCadastro,
                       cli.cli_email, 
                       cli.cli_emprestimo, 
                       cli.cli_parcelas, 
                       cli.cli_valor_parcela, 
                       
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
                       fi.bank_conta,

                       fi.bank_bb_possui,
                       fi.bank_bb_agencia,
                       fi.bank_bb_conta
                       
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
                       ON fi.bank_id = ban.cod 
                       
                       WHERE cli.cli_cadastro < date_sub('".$data."', interval 15 minute)";

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

            $origem = array(
                'form1' => array(),
                'form2' => array(),
                'app' => array()
            );

            $uteis = new Uteis();

            foreach ($resultCliente as $key => $value){
                // Set Masks
                $value['cli_cpf'] = $uteis->mask($value['cli_cpf'],'###.###.###-##');
                $value['cli_telefone'] = $uteis->mask($value['cli_telefone'],'(##) #####-####');


                switch ($value['cli_origem']) {
                    case 1:
                        array_push($origem['form1'], $value);
                      break;
                    case 2:
                        array_push($origem['form2'], $value);
                        break;
                    case 3:
                        array_push($origem['app'], $value);
                        break;
                  }
            }

            return array(
                'status'    => 200,
                'message'   => "INFO",
                'result'    => $origem
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