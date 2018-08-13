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
require_once 'DAO/FinalizeDAO.php';

use PHPMailer\PHPMailer\PHPMailer;
class SendEmail
{
    public function prepareEmail($all, $cli_id){
        $clienteDAO = new ClienteDAO();
        if ($all) { //
            $clientes = $clienteDAO->getClientes();
            foreach ($clientes['result'] as $key => $value){
                $this->sendNewEmail($value);
            }
            return array(
                'status'    => 200,
                'message'   => "SUCCESS",
                'result'    => 'E-mails enviados com sucesso!'
            );
        }else{
            $cliente = $clienteDAO->getCliente($cli_id);
            return $this->sendNewEmail($cliente['result']);
        }
    }

    public function sendNewEmail($cliente){
        $uteis = new Uteis();
        $url = 'https://api.rafafreitas.com.br/uploads/docs';

        $parcelas = 'R$ '.number_format($cliente['cli_emprestimo'], 2, ',', '.').
                    ' em '.$cliente['cli_parcelas'].' Parcelas';


        if($cliente['cli_origem'] == 1){
            $origem = '1° Formulário';
        }elseif ($cliente['cli_origem'] == 2){
            $origem = '2° Formulário';
        }else{
            $origem = $cliente['cli_origem'];
        }

        if($cliente['ocup_id'] == 5){
            $ocupacao = $cliente['ocup_nome'].'('.$cliente['cli_estado'].')';
        }elseif ($cliente['ocup_id'] == 6){
            $ocupacao = $cliente['ocup_nome'].'('.$cliente['Nome'].'-'.$cliente['cli_estado'].')';
        }elseif ($cliente['ocup_id'] == 7){
            $ocupacao = $cliente['ocup_nome'].'('.$cliente['cli_empresa'].')';
        }else{
            $ocupacao = $cliente['ocup_nome'];
        }

        if($cliente['motivo_id'] == 1 || $cliente['motivo_id'] == 2){
            $motivacao = $cliente['motivo_nome'].'('.$cliente['motivo_tratamento'].')';
        }elseif ($cliente['motivo_id'] == 5){
            $motivacao = $cliente['motivo_nome'].'('.$cliente['dataFesta'].')';
        }elseif(is_null($cliente['motivo_id'])|| empty($cliente['motivo_id'])){
            $motivacao = "Não fornecido.";
        }else{
            $motivacao = $cliente['motivo_nome'];
        }

        if(is_null($cliente['spc'])){
            $spc = "Não fornecido.";
        }elseif ($cliente['spc'] == 0){
            $spc = 'Não.';
        }elseif ($cliente['spc'] == 1){
            $spc = 'Sim';
        }else{
            $spc = 'Não fornecedido.';
        }

        if ($cliente['emprego'] == 1){
            $carteira = 'Sim, mais de 6 meses';
        }elseif ($cliente['emprego'] == 2){
            $carteira = 'Sim, menos de 6 meses';
        }elseif ($cliente['emprego'] == 3){
            $carteira = 'Não';
        }else{
            $carteira = 'Não fornecido.';
        }

        if ($cliente['rendaComprovada'] == 1){
            $renda = 'Sim, contracheque.';
        }elseif ($cliente['rendaComprovada'] == 2){
            $renda = 'Sim, imposto de renda';
        }elseif ($cliente['rendaComprovada'] == 3){
            $renda = 'Sim, extrato  bancário';
        }elseif ($cliente['rendaComprovada'] == 4){
            $renda = 'Não';
        }else{
            $renda = 'Não fornecido.';
        }

        if ($cliente['chequeDev'] == 0){
            $chequeDev = 'Mas não houve devolução de cheques';
        }elseif ($cliente['chequeDev'] == 1){
            $chequeDev = 'Mas possuo alguns devolvidos nos últimos meses.';
        }else{
            $chequeDev = '';
        }

        if(is_null($cliente['cheque'])){
            $cheque = "Não fornecido.";
        }elseif ($cliente['cheque'] == 1){
            $cheque = 'Sim.'.$chequeDev;
        }elseif ($cliente['cheque'] == 0){
            $cheque = 'Não';
        }else{
            $cheque = 'Não fornecedido.';
        }

        if ($cliente['bank_tempo_conta'] == 1){
            $tempoConta = 'Mais de 1 ano.';
        }elseif ($cliente['bank_tempo_conta'] == 2){
            $tempoConta = 'Menos de 1 ano';
        }else{
            $tempoConta = "Não fornecido.";
        }

        if ($cliente['bank_possui'] == 1){
            $banco = 'Sim, conta corrente.'.'('.$tempoConta.') '.$cliente['banco'].'<br/> Ag.'.$cliente['bank_agencia'].'<br/>Conta.'.$cliente['bank_conta'];
        }elseif ($cliente['bank_possui'] == 2){
            $banco = 'Sim, conta poupança.'.'('.$tempoConta.') '.$cliente['banco'].'<br/> Ag.'.$cliente['bank_agencia'].' Conta.'.$cliente['bank_conta'];
        }elseif ($cliente['bank_possui'] == 3){
            $banco = 'Não';
        }else{
            $banco = "Não fornecido.";
        }

        if (!empty($cliente['estadMuni'])) {

            if($cliente['estadMuni']['margemOption'] == 1){
                $label = 'Valor da margem livre';
                $value = 'R$ '.number_format($cliente['estadMuni']['margem'], 2, ',', '.');
            }elseif ($cliente['estadMuni']['margemOption'] == 2){
                $label = 'Matrícula + senha.';
                $value = 'Matrícula: '.$cliente['estadMuni']['matricula'].'.<br/> Senha: '.$cliente['estadMuni']['password'];
            }elseif ($cliente['estadMuni']['margemOption'] == 3){
                $label = 'Foto do contracheque';
                $value = '<a href="'.$url.'/'.$cliente['estadMuni']['imageUrl'].'">Download</a>';
            }else{
                $label = '';
                $value = '';
            }

            $margem =  '<tr>
                            <th style="border: 1px solid #000;width: 60px;">'.$label.'</th>
                            <td style="border: 1px solid #000;">'.$value.'</td>
                        </tr>';
        }else{
            $margem = '';
        }

        $credito = "";
        foreach ($cliente['credito'] as $key => $value){
            $limite = "";
            if ($value['cred_id'] == 2){
                $limite = '<br/>Limite R$ '.$value['limite_cartao'];
            }
            $aux =  '<tr>
                        <th colspan="2" style="border: 1px solid #000; text-align: center">'.$value['cred_nome'].$limite.'</th>
                     </tr>';
            $credito = $credito.$aux;
        }
        $credito = (empty($credito)) ? 'Não fornecido.' : $credito ;

        $parentesco = "";
        foreach ($cliente['parentesco'] as $key => $value){

            $proximidade = ($value['grau'] == 1) ? 'Pai' :
                           ($value['grau'] == 2) ? 'Mãe' :
                           ($value['grau'] == 3) ? 'Conjuge' :
                           ($value['grau'] == 4) ? 'Outro'.$value['proximidade'] : '';

            if($value['ocupacao'] == 5){
                $ocupacaoP = $value['ocup_nome'].'('.$value['estado'].')';
            }elseif ($value['ocupacao'] == 6){
                $ocupacaoP = $value['ocup_nome'].'('.$value['Nome'].'-'.$value['estado'].')';
            }elseif ($value['ocupacao'] == 7){
                $ocupacaoP = $value['ocup_nome'].'('.$value['empresa'].')';
            }else{
                $ocupacaoP = $value['ocup_nome'];
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
                        <td style="border: 1px solid #000;">'.$ocupacaoP.'</td>
                     </tr>
                     
                     ';
            $parentesco = $parentesco.$grau;
        }
        $parentesco = (empty($parentesco)) ? 'Não fornecido.' : $parentesco ;


        $doc_rg =(!is_null($cliente['finalize']['rg'])) ?
            '<a href="'.$url.'/'.$cliente['finalize']['rg'].'">Download</a>' : 'Não forncecido.' ;
        $doc_cpf =(!is_null($cliente['finalize']['cpf'])) ?
            '<a href="'.$url.'/'.$cliente['finalize']['cpf'].'">Download</a>' : 'Não forncecido.' ;
        $doc_comp =(!is_null($cliente['finalize']['compResidencia'])) ?
            '<a href="'.$url.'/'.$cliente['finalize']['compResidencia'].'">Download</a>' : 'Não forncecido.' ;
        $doc_cont =(!is_null($cliente['finalize']['contraCheque'])) ?
            '<a href="'.$url.'/'.$cliente['finalize']['contraCheque'].'">Download</a>' : 'Não forncecido.' ;
        $doc_cart =(!is_null($cliente['finalize']['carteiraTrabalho'])) ?
            '<a href="'.$url.'/'.$cliente['finalize']['carteiraTrabalho'].'">Download</a>' : 'Não forncecido.' ;
        $doc_imp =(!is_null($cliente['finalize']['impostoRenda'])) ?
            '<a href="'.$url.'/'.$cliente['finalize']['impostoRenda'].'">Download</a>' : 'Não forncecido.' ;

        $body = file_get_contents('Basics/Template_Email.html');
        $body = str_replace('%url%', $url, $body);

        //  Dados Pessoais
        $body = str_replace('%nome%', $cliente['cli_nome'], $body);
        $body = str_replace('%cpf%', $uteis->mask($cliente['cli_cpf'],'###.###.###-##') , $body);
        $body = str_replace('%telefone%', $cliente['cli_telefone'], $body);
        $body = str_replace('%nascimento%', $cliente['cliNascimento'], $body);
        $body = str_replace('%email%', $cliente['cli_email'], $body);
        $body = str_replace('%emprestimo%', $parcelas, $body);
        $body = str_replace('%ocupacao%', $ocupacao, $body);
        $body = str_replace('%motivacao%', $motivacao, $body);
        $body = str_replace('%indicacao%', $cliente['cli_indicacao'], $body);
        $body = str_replace('%origem%', $origem, $body);

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

        // Dados Documentos
        $body = str_replace('%docRg%', $doc_rg, $body);
        $body = str_replace('%docCpf%', $doc_cpf, $body);
        $body = str_replace('%docComp%', $doc_comp, $body);
        $body = str_replace('%docContra%', $doc_cont, $body);
        $body = str_replace('%docCarteira%', $doc_cart, $body);
        $body = str_replace('%docImposto%', $doc_imp, $body);

        $mail = new PHPMailer(true);
        $mail->CharSet = 'UTF-8';
        $mail->IsSMTP();
        $mail->Host = "mx1..com.br";
        $mail->SMTPAuth = true;
        $mail->Port = 587;
        $mail->Username = '';
        $mail->Password = '';
        $mail->From = "";
        $mail->FromName = "";
        $mail->AddAddress('rafael.vasconcelos@outlook.com', 'Contato');
        $mail->Subject  = "";
        $mail->MsgHTML($body);
        $mail->IsHTML(true);
        $mail->Send();

        if($mail){
            $finalizeDAO = new FinalizeDAO();
            $finalizeDAO->finalize($cliente['cli_id']);
        }

        return array(
            'status'    => 200,
            'message'   => "SUCCESS",
            'result'    => 'E-mails enviados com sucesso!',
            'Mail'      => $mail
        );
    }
}