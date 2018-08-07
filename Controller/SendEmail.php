<?php
/**
 * Created by PhpStorm.
 * User: Rafael Freitas
 * Date: 06/08/2018
 * Time: 23:39
 */

require_once 'DAO/ClienteDAO.php';
class SendEmail
{
    public function prepareEmail($all, $cli_id){
        $clienteDAO = new ClienteDAO();
        if ($all) { //

        }else{
            $cliente = $clienteDAO->getClientes($cli_id);
            return $this->sendEmail($cliente);
        }

    }

    public function sendEmail($cliente){

    }
}