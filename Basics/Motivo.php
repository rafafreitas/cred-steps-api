<?php
/**
 * Created by PhpStorm.
 * User: Rafael Freitas
 * Date: 04/08/2018
 * Time: 09:21
 */

class Motivo
{
    private $id;
    private $cli_id;
    private $motivo_id;
    private $tratamento;
    private $data_festa;

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
    public function getMotivoId()
    {
        return $this->motivo_id;
    }

    /**
     * @param mixed $motivo_id
     */
    public function setMotivoId($motivo_id)
    {
        $this->motivo_id = $motivo_id;
    }

    /**
     * @return mixed
     */
    public function getTratamento()
    {
        return $this->tratamento;
    }

    /**
     * @param mixed $tratamento
     */
    public function setTratamento($tratamento)
    {
        $this->tratamento = $tratamento;
    }

    /**
     * @return mixed
     */
    public function getDataFesta()
    {
        return $this->data_festa;
    }

    /**
     * @param mixed $data_festa
     */
    public function setDataFesta($data_festa)
    {
        $this->data_festa = $data_festa;
    }
}