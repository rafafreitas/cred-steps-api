<?php
/**
 * Created by PhpStorm.
 * User: Rafel Freitas
 * Date: 04/08/2018
 * Time: 20:27
 */

class Parentesco
{
    private $id;
    private $grau;
    private $proximidade;
    private $nome;
    private $cpf;
    private $telefone;
    private $nascimento;
    private $ocupacao;
    private $estado;
    private $cidade;
    private $empresa;

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
    public function getGrau()
    {
        return $this->grau;
    }

    /**
     * @param mixed $grau
     */
    public function setGrau($grau)
    {
        $this->grau = $grau;
    }

    /**
     * @return mixed
     */
    public function getProximidade()
    {
        return $this->proximidade;
    }

    /**
     * @param mixed $proximidade
     */
    public function setProximidade($proximidade)
    {
        $this->proximidade = $proximidade;
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
    public function getOcupacao()
    {
        return $this->ocupacao;
    }

    /**
     * @param mixed $ocupacao
     */
    public function setOcupacao($ocupacao)
    {
        $this->ocupacao = $ocupacao;
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
}