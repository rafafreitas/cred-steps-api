<?php
/**
 * Created by PhpStorm.
 * User: Rafael Freitas
 * Date: 06/08/2018
 * Time: 15:34
 */

class Finalize
{
    private $id;
    private $cli_id;
    private $rg;
    private $cpf;
    private $compResidencia;
    private $contraCheque;
    private $carteiraTrabalho;
    private $impostoRenda;

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
    public function getRg()
    {
        return $this->rg;
    }

    /**
     * @param mixed $rg
     */
    public function setRg($rg)
    {
        $this->rg = $rg;
    }

    /**
     * @return mixed
     */
    public function getCpf()
    {
        return $this->cpf;
    }

    /**
     * @param mixed $cpf
     */
    public function setCpf($cpf)
    {
        $this->cpf = $cpf;
    }

    /**
     * @return mixed
     */
    public function getCompResidencia()
    {
        return $this->compResidencia;
    }

    /**
     * @param mixed $compResidencia
     */
    public function setCompResidencia($compResidencia)
    {
        $this->compResidencia = $compResidencia;
    }

    /**
     * @return mixed
     */
    public function getContraCheque()
    {
        return $this->contraCheque;
    }

    /**
     * @param mixed $contraCheque
     */
    public function setContraCheque($contraCheque)
    {
        $this->contraCheque = $contraCheque;
    }

    /**
     * @return mixed
     */
    public function getCarteiraTrabalho()
    {
        return $this->carteiraTrabalho;
    }

    /**
     * @param mixed $carteiraTrabalho
     */
    public function setCarteiraTrabalho($carteiraTrabalho)
    {
        $this->carteiraTrabalho = $carteiraTrabalho;
    }

    /**
     * @return mixed
     */
    public function getImpostoRenda()
    {
        return $this->impostoRenda;
    }

    /**
     * @param mixed $impostoRenda
     */
    public function setImpostoRenda($impostoRenda)
    {
        $this->impostoRenda = $impostoRenda;
    }
}