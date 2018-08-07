<?php
/**
 * Created by PhpStorm.
 * User: Rafael Freitas
 * Date: 06/08/2018
 * Time: 23:39
 */

require 'lib/vendor/autoload.php';
require_once 'Basics/Uteis.php';
require_once 'DAO/ClienteDAO.php';
require_once 'DAO/ClienteDAO.php';

use PHPMailer\PHPMailer\PHPMailer;
class SendEmail
{
    public function prepareEmail($all, $cli_id){
        $clienteDAO = new ClienteDAO();
        if ($all) { //
            $clientes = $clienteDAO->getClientes();
            foreach ($clientes as $key => $value){
                $this->sendNewEmail($value);
            }
            return array(
                'status'    => 200,
                'message'   => "SUCCESS",
                'result'    => 'E-mails enviados com sucesso!'
            );
        }else{
            $cliente = $clienteDAO->getCliente($cli_id);
            return $this->sendNewEmail($cliente);
        }
    }

    public function sendNewEmail($cliente){
        $uteis = new Uteis();
        $url = 'https://api.rafafreitas.com.br/uploads/docs';

        $dados = $cliente['result'][0];

        $parcelas = 'R$ '.number_format($dados['cli_emprestimo'], 2, ',', '.').
                    ' em '.$dados['cli_parcelas'].' Parcelas';

        if($dados['ocup_id'] == 5){
            $ocupacao = $dados['ocup_nome'].'('.$dados['cli_estado'].')';
        }elseif ($dados['ocup_id'] == 6){
            $ocupacao = $dados['ocup_nome'].'('.$dados['Nome'].'-'.$dados['cli_estado'].')';
        }elseif ($dados['ocup_id'] == 7){
            $ocupacao = $dados['ocup_nome'].'('.$dados['cli_empresa'].')';
        }else{
            $ocupacao = $dados['ocup_nome'];
        }

        if($dados['motivo_id'] == 1 || $dados['motivo_id'] == 2){
            $motivacao = $dados['motivo_nome'].'('.$dados['motivo_tratamento'].')';
        }elseif ($dados['motivo_id'] == 5){
            $motivacao = $dados['motivo_nome'].'('.$dados['dataFesta'].')';
        }else{
            $motivacao = $dados['motivo_nome'];
        }

        $spc = ($dados['spc'] == 0) ? 'Não' : 'Sim';

        $carteira = ($dados['emprego'] == 1) ? 'Sim, mais de 6 meses' :
                    ($dados['emprego'] == 2) ? 'Sim, menos de 6 meses' : "Não";

        $renda = ($dados['rendaComprovada'] == 1) ? 'Sim, contracheque' :
                 ($dados['rendaComprovada'] == 2) ? 'Sim, imposto de renda' :
                 ($dados['rendaComprovada'] == 3) ? 'Sim, extrato  bancário' : "Não";

        $chequeDev = ($dados['chequeDev'] == 0) ? 'Mas possuo alguns devolvidos nos últimos meses.' :
                     ($dados['chequeDev'] == 1) ? 'Mas não houve devolução de cheques' : "";

        $cheque = ($dados['cheque'] == 1) ? 'Sim.'.$chequeDev :
                  ($dados['cheque'] == 0) ? 'Não' : "";

        $tempoConta = ($dados['bank_tempo_conta'] == 1) ? 'Mais de 1 ano.' :
                      ($dados['bank_tempo_conta'] == 2) ? 'Menos de 1 ano' : "";

        $banco = ($dados['bank_possui'] == 1) ? 'Sim, conta corrente.'.'('.$tempoConta.') '.$dados['banco'].'<br/> Ag.'.$dados['bank_agencia'].' Conta.'.$dados['bank_conta'] :
                 ($dados['bank_possui'] == 2) ? 'Sim, conta poupança.'.'('.$tempoConta.') '.$dados['banco'].'<br/> Ag.'.$dados['bank_agencia'].' Conta.'.$dados['bank_conta'] : "Não.";


        if (!empty($cliente['result']['estadMuni'])) {
            $label = ($cliente['result']['estadMuni']['margemOption'] == 1) ? 'Valor da margem livre' :
                     ($cliente['result']['estadMuni']['margemOption'] == 2) ? 'Matrícula + senha.' :
                     ($cliente['result']['estadMuni']['margemOption'] == 3) ? 'Foto do contracheque' : '';

            $value = ($cliente['result']['estadMuni']['margemOption'] == 1) ? $cliente['result']['estadMuni']['margem'] :
                     ($cliente['result']['estadMuni']['margemOption'] == 2) ? 'Matrícula: '.$cliente['result']['estadMuni']['matricula'].'. Senha: '.$cliente['result']['estadMuni']['password'] :
                     ($cliente['result']['estadMuni']['margemOption'] == 3) ? '<a href="'.$url.'/'.$cliente['result']['estadMuni']['imageUrl'].'">Download</a>' : '';

            $margem =  '<tr>
                            <th style="border: 1px solid #000;width: 60px;">'.$label.'</th>
                            <td style="border: 1px solid #000;">'.$value.'</td>
                        </tr>';
        }else{
            $margem = '123';
        }

        $credito = "";
        foreach ($cliente['result']['credito'] as $key => $value){
            $limite = "";
            if ($value['cred_id'] == 2){
                $limite = '<br/>Limite R$ '.$value['limite_cartao'];
            }
            $aux =  '<tr>
                        <th colspan="2" style="border: 1px solid #000; text-align: center">'.$value['cred_nome'].$limite.'</th>
                     </tr>';
            $credito = $credito.$aux;
        }

        $parentesco = "";
        foreach ($cliente['result']['parentesco'] as $key => $value){

            $proximidade = ($value['grau'] == 1) ? 'Pai' :
                           ($value['grau'] == 2) ? 'Mãe' :
                           ($value['grau'] == 3) ? 'Conjuge' :
                           ($value['grau'] == 4) ? 'Outro'.$value['proximidade'] : '';

            if($value['ocupacao'] == 5){
                $ocupacao = $value['ocup_nome'].'('.$value['estado'].')';
            }elseif ($value['ocupacao'] == 6){
                $ocupacao = $value['ocup_nome'].'('.$value['Nome'].'-'.$value['estado'].')';
            }elseif ($dados['ocupacao'] == 7){
                $ocupacao = $value['ocup_nome'].'('.$value['empresa'].')';
            }else{
                $ocupacao = $value['ocup_nome'];
            }

            $grau =  '<p>'.($key+1).'º Indicação</p>
                     <tr>
                        <th style="border: 1px solid #000;width: 60px;">Grau</th>
                        <td style="border: 1px solid #000;">'.$proximidade.'</td>
                     </tr>
                     <tr>
                        <th style="border: 1px solid #000;width: 60px;">Nome</th>
                        <td style="border: 1px solid #000;">'.$value['nome'].'</td>
                     </tr>
                     <tr>
                        <th style="border: 1px solid #000;width: 60px;">CPF</th>
                        <td style="border: 1px solid #000;">'.$value['cpf'].'</td>
                     </tr>
                     <tr>
                        <th style="border: 1px solid #000;width: 60px;">Telefone</th>
                        <td style="border: 1px solid #000;">'.$value['telefone'].'</td>
                     </tr>
                     <tr>
                        <th style="border: 1px solid #000;width: 60px;">Ocupação</th>
                        <td style="border: 1px solid #000;">'.$ocupacao.'</td>
                     </tr>
                     
                     ';
            $parentesco = $parentesco.$grau;
        }

        $parentesco = (empty($parentesco)) ? 'Não fornecido.' : $parentesco ;


        $body = file_get_contents('Basics/Template_Email.html');
        $body = str_replace('%url%', $url, $body);

        //  Dados Pessoais
        $body = str_replace('%nome%', $dados['cli_nome'], $body);
        $body = str_replace('%cpf%', $uteis->mask($dados['cli_cpf'],'###.###.###-##') , $body);
        $body = str_replace('%telefone%', $dados['cli_telefone'], $body);
        $body = str_replace('%nascimento%', $dados['cliNascimento'], $body);
        $body = str_replace('%email%', $dados['cli_email'], $body);
        $body = str_replace('%emprestimo%', $parcelas, $body);
        $body = str_replace('%ocupacao%', $ocupacao, $body);
        $body = str_replace('%motivacao%', $motivacao, $body);

        // Dados Financeiros
        $body = str_replace('%margem%', $margem, $body);
        $body = str_replace('%spc%', $spc, $body);
        $body = str_replace('%carteira%', $carteira, $body);
        $body = str_replace('%renda%', $renda, $body);
        $body = str_replace('%cheque%', $cheque, $body);
        $body = str_replace('%banco%', $banco, $body);

        // Dados Crédito
        $body = str_replace('%comoCredito%', $credito, $body);

        // Dados Parentesco
        $body = str_replace('%parentesco%', $parentesco, $body);


        
        $mail->Send();

        return array(
            'status'    => 200,
            'message'   => "SUCCESS",
            'result'    => 'E-mails enviados com sucesso!'
        );
    }
}