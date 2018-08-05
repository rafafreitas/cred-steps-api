<?php
/**
 * Created by PhpStorm.
 * User: Rafael Freitas
 * Date: 04/08/2018
 * Time: 20:49
 */

class EstadualMunicipal
{
    private $id;
    private $margemInfo;
    private $margemValor;
    private $matricula;
    private $password;
    private $imageName;
    private $imageUrl;
    private $imageFile;

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
    public function getMargemInfo()
    {
        return $this->margemInfo;
    }

    /**
     * @param mixed $margemInfo
     */
    public function setMargemInfo($margemInfo)
    {
        $this->margemInfo = $margemInfo;
    }

    /**
     * @return mixed
     */
    public function getMargemValor()
    {
        return $this->margemValor;
    }

    /**
     * @param mixed $margemValor
     */
    public function setMargemValor($margemValor)
    {
        $this->margemValor = $margemValor;
    }

    /**
     * @return mixed
     */
    public function getMatricula()
    {
        return $this->matricula;
    }

    /**
     * @param mixed $matricula
     */
    public function setMatricula($matricula)
    {
        $this->matricula = $matricula;
    }

    /**
     * @return mixed
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param mixed $password
     */
    public function setPassword($password)
    {
        $this->password = $password;
    }

    /**
     * @return mixed
     */
    public function getImageName()
    {
        return $this->imageName;
    }

    /**
     * @param mixed $imageName
     */
    public function setImageName($imageName)
    {
        $this->imageName = $imageName;
    }

    /**
     * @return mixed
     */
    public function getImageUrl()
    {
        return $this->imageUrl;
    }

    /**
     * @param mixed $imageUrl
     */
    public function setImageUrl($imageUrl)
    {
        $this->imageUrl = $imageUrl;
    }

    /**
     * @return mixed
     */
    public function getImageFile()
    {
        return $this->imageFile;
    }

    /**
     * @param mixed $imageFile
     */
    public function setImageFile($imageFile)
    {
        $this->imageFile = $imageFile;
    }
}