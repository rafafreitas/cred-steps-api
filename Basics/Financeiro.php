<?php
/**
 * Created by PhpStorm.
 * User: Rafael Freitas
 * Date: 04/08/2018
 * Time: 19:39
 */

class Financeiro
{
    private $id;
    private $cli_id;
    private $spc;
    private $cheque;
    private $chequeDev;
    private $emprego;
    private $rendaComprovada;
    private $rendaValor;
    private $bank_possui;
    private $bank_id;
    private $bank_tempo_conta;
    private $bank_agencia;
    private $bank_conta;
    private $bank_bb_possui;
    private $bank_bb_agencia;
    private $bank_bb_conta;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getCliId()
    {
        return $this->cli_id;
    }

    /**
     * @param mixed $cli_id
     */
    public function setCliId($cli_id)
    {
        $this->cli_id = $cli_id;
    }

    /**
     * @return mixed
     */
    public function getSpc()
    {
        return $this->spc;
    }

    /**
     * @param mixed $spc
     */
    public function setSpc($spc)
    {
        $this->spc = $spc;
    }

    /**
     * @return mixed
     */
    public function getCheque()
    {
        return $this->cheque;
    }

    /**
     * @param mixed $cheque
     */
    public function setCheque($cheque)
    {
        $this->cheque = $cheque;
    }

    /**
     * @return mixed
     */
    public function getChequeDev()
    {
        return $this->chequeDev;
    }

    /**
     * @param mixed $chequeDev
     */
    public function setChequeDev($chequeDev)
    {
        $this->chequeDev = $chequeDev;
    }

    /**
     * @return mixed
     */
    public function getEmprego()
    {
        return $this->emprego;
    }

    /**
     * @param mixed $emprego
     */
    public function setEmprego($emprego)
    {
        $this->emprego = $emprego;
    }

    /**
     * @return mixed
     */
    public function getRendaComprovada()
    {
        return $this->rendaComprovada;
    }

    /**
     * @param mixed $rendaComprovada
     */
    public function setRendaComprovada($rendaComprovada)
    {
        $this->rendaComprovada = $rendaComprovada;
    }

    /**
     * @return mixed
     */
    public function getRendaValor()
    {
        return $this->rendaValor;
    }

    /**
     * @param mixed $rendaValor
     */
    public function setRendaValor($rendaValor)
    {
        $this->rendaValor = $rendaValor;
    }

    /**
     * @return mixed
     */
    public function getBankPossui()
    {
        return $this->bank_possui;
    }

    /**
     * @param mixed $bank_possui
     */
    public function setBankPossui($bank_possui)
    {
        $this->bank_possui = $bank_possui;
    }

    /**
     * @return mixed
     */
    public function getBankId()
    {
        return $this->bank_id;
    }

    /**
     * @param mixed $bank_id
     */
    public function setBankId($bank_id)
    {
        $this->bank_id = $bank_id;
    }

    /**
     * @return mixed
     */
    public function getBankTempoConta()
    {
        return $this->bank_tempo_conta;
    }

    /**
     * @param mixed $bank_tempo_conta
     */
    public function setBankTempoConta($bank_tempo_conta)
    {
        $this->bank_tempo_conta = $bank_tempo_conta;
    }

    /**
     * @return mixed
     */
    public function getBankAgencia()
    {
        return $this->bank_agencia;
    }

    /**
     * @param mixed $bank_agencia
     */
    public function setBankAgencia($bank_agencia)
    {
        $this->bank_agencia = $bank_agencia;
    }

    /**
     * @return mixed
     */
    public function getBankConta()
    {
        return $this->bank_conta;
    }

    /**
     * @param mixed $bank_conta
     */
    public function setBankConta($bank_conta)
    {
        $this->bank_conta = $bank_conta;
    }

    /**
     * @return mixed
     */
    public function getBankBbPossui()
    {
        return $this->bank_bb_possui;
    }

    /**
     * @param mixed $bank_bb_possui
     */
    public function setBankBbPossui($bank_bb_possui)
    {
        $this->bank_bb_possui = $bank_bb_possui;
    }

    /**
     * @return mixed
     */
    public function getBankBbAgencia()
    {
        return $this->bank_bb_agencia;
    }

    /**
     * @param mixed $bank_bb_agencia
     */
    public function setBankBbAgencia($bank_bb_agencia)
    {
        $this->bank_bb_agencia = $bank_bb_agencia;
    }

    /**
     * @return mixed
     */
    public function getBankBbConta()
    {
        return $this->bank_bb_conta;
    }

    /**
     * @param mixed $bank_bb_conta
     */
    public function setBankBbConta($bank_bb_conta)
    {
        $this->bank_bb_conta = $bank_bb_conta;
    }
}