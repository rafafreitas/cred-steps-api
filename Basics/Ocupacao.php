<?php
/**
 * Created by PhpStorm.
 * User: Rafel Freitas
 * Date: 02/08/2018
 * Time: 23:15
 */

class Ocupacao
{
    private $id;
    private $opcao;
    private $estado;
    private $cidade;
    private $empresa;
    private $cli_id;

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
    public function getOpcao()
    {
        return $this->opcao;
    }

    /**
     * @param mixed $opcao
     */
    public function setOpcao($opcao)
    {
        $this->opcao = $opcao;
    }

    /**
     * @return mixed
     */
    public function getEstado()
    {
        return $this->estado;
    }

    /**
     * @param mixed $estado
     */
    public function setEstado($estado)
    {
        $this->estado = $estado;
    }

    /**
     * @return mixed
     */
    public function getCidade()
    {
        return $this->cidade;
    }

    /**
     * @param mixed $cidade
     */
    public function setCidade($cidade)
    {
        $this->cidade = $cidade;
    }

    /**
     * @return mixed
     */
    public function getEmpresa()
    {
        return $this->empresa;
    }

    /**
     * @param mixed $empresa
     */
    public function setEmpresa($empresa)
    {
        $this->empresa = $empresa;
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

}