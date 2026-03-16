<?php

require_once __DIR__ . '/JsonStorage.php';

class PasswordResetService
{
    private $resetStorage;
    private $parentsStorage;

    public function __construct()
    {
        $this->resetStorage = new JsonStorage(__DIR__ . '/../../storage/reset_codes.json');
        $this->parentsStorage = new JsonStorage(__DIR__ . '/../../storage/parents.json');
    }

    public function findParent($identifier)
    {
        $parents = $this->parentsStorage->all();

        foreach ($parents as $parent) {
            if ($parent['email'] === $identifier || $parent['phone'] === $identifier) {
                return $parent;
            }
        }

        return null;
    }

    public function generateCode()
    {
        return rand(100000, 999999);
    }

    public function storeCode($parentId, $code)
    {
        $data = $this->resetStorage->all();

        $data[$parentId] = [
            "code" => $code,
            "expires" => date('Y-m-d H:i:s', strtotime('+10 minutes'))
        ];

        $this->resetStorage->save($data);
    }

    public function sendEmail($email, $code)
    {
        mail(
            $email,
            "Mudzunga Creche Password Reset",
            "Your password reset code is: $code"
        );
    }

    public function sendSms($phone, $code)
    {
        $message = "TO: $phone\n";
        $message .= "MESSAGE: Your reset code is $code\n";
        $message .= "TIME: " . date('Y-m-d H:i:s') . "\n";
        $message .= "---------------------\n";

        file_put_contents(
            __DIR__ . "/../../storage/sms_log.txt",
            $message,
            FILE_APPEND
        );
    }

    public function verifyCode($parentId, $enteredCode)
    {
        $data = $this->resetStorage->all();

        if (!isset($data[$parentId])) {
            return false;
        }

        $stored = $data[$parentId];

        if ($stored['code'] !== $enteredCode) {
            return false;
        }

        if (strtotime($stored['expires']) < time()) {
            return false;
        }

        return true;
    }

    public function resetPassword($parentId, $newPassword)
    {
        $parents = $this->parentsStorage->all();

        foreach ($parents as &$parent) {
            if ($parent['id'] === $parentId) {
                $parent['password'] = password_hash($newPassword, PASSWORD_DEFAULT);
            }
        }

        $this->parentsStorage->save($parents);
    }
}

