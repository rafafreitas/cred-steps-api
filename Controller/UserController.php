<?php
/**
 * Created by PhpStorm.
 * User: Rafael Freitas
 * Date: 01/08/2018
 * Time: 19:42
 */

require_once 'Basics/User.php';
require_once 'DAO/UserDAO.php';
class UserController
{
    public function getUsers(){
        $userDAO = new UserDAO();
        return $userDAO->getUsers();
    }
}