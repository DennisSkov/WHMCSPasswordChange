<?php

namespace WHMCS\Module\Addon\PasswordChange;

use WHMCS\User\User;

class PasswordGenerator
{
    protected string $hash;
    protected int $userId;
    protected $userModel;

    public function __construct($userId){
        $this->userId = $userId;
        $this->userModel = User::findOrFail($userId);
    }
    protected function generateHash($password): string
    {
        $this->hash = password_hash($password, PASSWORD_BCRYPT);
        return $this->hash;
    }
    public function getHash(): string
    {
        return $this->hash;
    }

    protected function store(){
        $this->userModel->password = $this->hash;
        $this->userModel->save();
    }
    public function setPassword($passwordString): void
    {
        $this->generateHash($passwordString);
        $this->store();
    }
}