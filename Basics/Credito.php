<?php
/**
 * Created by PhpStorm.
 * User: Rafael Freitas
 * Date: 04/08/2018
 * Time: 09:21
 */

class Credito
{
    private $id;
    private $cli_id;
    private $cred_id;
    private $limite_cartao;

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
    public function getCredId()
    {
        return $this->cred_id;
    }

    /**
     * @param mixed $cred_id
     */
    public function setCredId($cred_id)
    {
        $this->cred_id = $cred_id;
    }

    /**
     * @return mixed
     */
    public function getLimiteCartao()
    {
        return $this->limite_cartao;
    }

    /**
     * @param mixed $limite_cartao
     */
    public function setLimiteCartao($limite_cartao)
    {
        $this->limite_cartao = $limite_cartao;
    }
}