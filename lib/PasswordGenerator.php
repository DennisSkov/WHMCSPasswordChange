<?php

namespace WHMCS\Module\Addon\PasswordChange;

use Exception;
use WHMCS\User\User;

class PasswordGenerator
{
    protected string $hash;
    protected int $userId;
    protected $userModel;

    /**
     * @throws Exception
     */
    public function __construct($userId){
        if(!isset($_SESSION['adminid'])){
            throw new Exception('Not authenticated as admin');
        }
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

    protected function store(): void
    {
        $this->userModel->password = $this->hash;
        $this->userModel->save();
    }
    public function setPassword($passwordString): void
    {
        $this->generateHash($passwordString);
        $this->store();
    }
}