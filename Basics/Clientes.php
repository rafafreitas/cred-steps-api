<?php
/**
 * Created by PhpStorm.
 * User: Rafael Freitas
 * Date: 01/08/2018
 * Time: 19:34
 */

class Clientes
{
    private $id;
    private $nome;
    private $cpf;
    private $telefone;
    private $nascimento;
    private $email;
    private $valorEmprestimo;
    private $parcelas;
    private $status;
    private $cadastro;
    private $tipo_id;

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
    public function getNome()
    {
        return $this->nome;
    }

    /**
     * @param mixed $nome
     */
    public function setNome($nome)
    {
        $this->nome = $nome;
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
    public function getTelefone()
    {
        return $this->telefone;
    }

    /**
     * @param mixed $telefone
     */
    public function setTelefone($telefone)
    {
        $this->telefone = $telefone;
    }

    /**
     * @return mixed
     */
    public function getNascimento()
    {
        return $this->nascimento;
    }

    /**
     * @param mixed $nascimento
     */
    public function setNascimento($nascimento)
    {
        $this->nascimento = $nascimento;
    }

    /**
     * @return mixed
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param mixed $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * @return mixed
     */
    public function getValorEmprestimo()
    {
        return $this->valorEmprestimo;
    }

    /**
     * @param mixed $valorEmprestimo
     */
    public function setValorEmprestimo($valorEmprestimo)
    {
        $this->valorEmprestimo = $valorEmprestimo;
    }

    /**
     * @return mixed
     */
    public function getParcelas()
    {
        return $this->parcelas;
    }

    /**
     * @param mixed $parcelas
     */
    public function setParcelas($parcelas)
    {
        $this->parcelas = $parcelas;
    }

    /**
     * @return mixed
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param mixed $status
     */
    public function setStatus($status)
    {
        $this->status = $status;
    }

    /**
     * @return mixed
     */
    public function getCadastro()
    {
        return $this->cadastro;
    }

    /**
     * @param mixed $cadastro
     */
    public function setCadastro($cadastro)
    {
        $this->cadastro = $cadastro;
    }

    /**
     * @return mixed
     */
    public function getTipoId()
    {
        return $this->tipo_id;
    }

    /**
     * @param mixed $tipo_id
     */
    public function setTipoId($tipo_id)
    {
        $this->tipo_id = $tipo_id;
    }



}