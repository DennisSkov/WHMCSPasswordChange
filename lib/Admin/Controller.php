<?php

namespace WHMCS\Module\Addon\PasswordChange\Admin;
use WHMCS\Module\Addon\PasswordChange\PasswordGenerator;
use WHMCS\Database\Capsule;
class Controller
{
    public function changePassword(){
        if($_SERVER["REQUEST_METHOD"] !== 'POST'){
            die('Method not allowed');
        }
        $password1 = $_POST['password'];
        $password2 = $_POST['confirmPassword'];
        $sendToClient = $_POST['sendToClient'];
        $clientId = $_POST['userid'];
        $userId = Capsule::table('tblusers_clients')
            ->select('auth_user_id')
            ->where('client_id', $clientId)
            ->where('owner', 1)
            ->first();
        $userId = $userId->auth_user_id;
        if(!(is_int($userId))){
            exit(json_encode(array('result' => 'error', 'message' => 'idIsNotAnInt')));
        }
        if(!$password1 or !$password2){
            exit(json_encode(array('result' => 'error', 'message' => 'emptyFields')));
        }
        if($password1 != $password2){
            exit(json_encode(array('result' => 'error', 'message' => 'notmatching')));
        }else {
            $changePassword = new PasswordGenerator($userId);
            $changePassword->setPassword($password1);
            if($sendToClient){

                $postData = [
                    'messagename' => 'Password Change for User',
                    'id' => $userId,
                    'customvars' => base64_encode(serialize(['password' => $password1]))
                ];
                localAPI('SendEmail', $postData);
                exit(json_encode(array('result' => 'success', 'message' => $sendToClient)));
            }
            exit(json_encode(array('result' => 'success')));

        }
        header('Location: clientssummary.php?userid='.$userId);
        exit();
    }
}