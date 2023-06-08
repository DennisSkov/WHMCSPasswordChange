<?php

use WHMCS\Mail\Template as EmailTemplate;
use WHMCS\Module\Addon\PasswordChange\Admin\AdminDispatcher;

if (!defined("WHMCS")) {
    die("This file cannot be accessed directly");
}
function PasswordChange_config(): array
{
    return [
        'name' => 'Password Change for WHMCS',
        'description' => 'Manually change passwords for users',
        'version' => '1.0',
        'author' => 'DennisHermannsen'
    ];
}

function PasswordChange_activate(): array
{
    try {
        $changeTemplate = new EmailTemplate;
        $changeTemplate->type = 'user';
        $changeTemplate->name = 'Password Change for User';
        $changeTemplate->subject = 'Your password has been changed';
        $changeTemplate->message = /** @lang text */
            '<p>Dear Client.</p>
            <p>You have requested a password change. Your new password is <b>{$password}</b>.</p>
            <p>Please login and update your password as soon as possible.</p>;
            {$signature}';
        $changeTemplate->save();
        return [
            'status' => 'success',
            'description' => 'PasswordChange has been activated'
            ];
    }catch(Exception $e){
        return [
            'status' => 'error',
            'description' => 'Could not create email template: '.$e->getMessage()
        ];
    }
}

function PasswordChange_deactivate(): array
{
    try {
        EmailTemplate::where('name', 'Password Change for User')->delete();
        return [
            'status' => 'success',
            'description' => 'PasswordChange has been deactivated'
        ];
    } catch (Exception $e){
        return [
            'status' => 'error',
            'description' => 'Could not delete email template: '.$e->getMessage()
        ];
    }
}

function PasswordChange_output($vars): void
{
    $action = $_REQUEST['action'] ?? '';

    $dispatcher = new AdminDispatcher();
    $response = $dispatcher->dispatch($action, $vars);
    echo $response;
}