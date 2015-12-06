<?php

namespace User\Service;

use User\Model\UserHandler;

class User
{
    private $userHandler;

    public function __construct(UserHandler $userHandler)
    {
        $this->setUserHandler($userHandler);
    }

    public function getUserHandler()
    {
        return $this->userHandler;
    }

    public function setUserHandler(UserHandler $userHandler)
    {
        $this->userHandler = $userHandler;
        return $this;
    }

    /**
     * Création d'un utilisateur en base
     */
    public function create(array $data)
    {
        return $this->getUserHandler()->insert($this->getUser($data));
    }

    /**
     * Validation des données utilisateurs (formulaire)
     */
    public function isValid(array $data)
    {
        $errors = [];

        $data['email']     = isset($data['email'])     ? trim($data['email'])     : null;
        $data['username']  = isset($data['username'])  ? trim($data['username'])  : null;
        $data['password']  = isset($data['password'])  ? trim($data['password'])  : null;
        $data['firstname'] = isset($data['firstname']) ? trim($data['firstname']) : null;
        $data['lastname']  = isset($data['lastname'])  ? trim($data['lastname'])  : null;
        $data['birthdate'] = isset($data['birthdate']) ? trim($data['birthdate']) : null;
        $data['isActive']  = isset($data['isActive'])  ? trim($data['isActive'])  : null;

        // EMAIL
        if (empty($data['email'])) {
            $errors['email']['empty'] = 'Saisissez un email';
        }

        if (strlen($data['email']) < 10 || strlen($data['email']) > 255) {
            $errors['email']['length'] = 'Hum... cette taille est étrange :/ ';
        }

        if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $errors['email']['invalid'] = 'Email invalid :( ';
        }

        // USERNAME
        if (empty($data['username'])) {
            $errors['username']['empty'] = "Saisissez un nom d'utilisateur";
        }
    
        if (strlen($data['username']) < 5 || strlen($data['username']) > 16) {
            $errors['username']['length'] = "Nom d'utilisateur trop de pas assez";
        }

        // PASSWORD
        if (empty($data['password'])) {
            $errors['password']['empty'] = "Saisissez un mot de passe et vite";
        }

        if (strlen($data['password']) < 8 || strlen($data['password']) > 16) {
            $errors['password']['length'] = "Ton mot de pass c'est n'importe quoi !!!";
        }

        if (!preg_match('#^[a-zA-Z][a-zA-Z0-9]{8,15}$#', $data['password'])) {
            $errors['password']['invalid'] = "Mot de pass invalid ! T'es nul !!!";
        }

        $result['errors'] = $errors;
        $result['vars']   = $data;
        $result['valid']  = count($errors) == 0;

        return $result;
    }

    /**
     * Création d'un objet user
     * 
     * $data == $_POST
     * $data == $_GET
     */
    public function getUser(array $data)
    {
        $user = new \User\Model\User();
        $user->setEmail($data['email'])
             ->setFirstname($data['firstname'])
             ->setLastname($data['lastname'])
             ->setUsername($data['username'])
             ->setPassword($data['password'])
             ->setIsActive($data['isActive'])
             ->setBirthdate(new \DateTime($data['birthdate']));

        return $user;
    }
}
