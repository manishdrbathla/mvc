<?php

namespace App\Model;

use App\Mail;
use System\Core\BaseModel;
use System\Core\View;
use System\Library\MyPDO;
use App\Token;

class UserModel extends BaseModel
{

    public static function emailExists($email)
    {
        return static::findByEmail($email) !== false;
    }

    public static function findByEmail($email)
    {
        return MyPDO::run(
            "SELECT * FROM user_account WHERE email = ?",
            [$email]
        )->fetchObject(get_called_class());
    }

    public static function authenticate($email, $password)
    {
        $user = static::findByEmail($email);

        if ($user) {
            if (password_verify($password, $user->password)) {
                return $user;
            }
        }
        return false;
    }

    public static function findByID($id)
    {
        return MyPDO::run("SELECT * FROM user_account WHERE id = ?", [$id])->fetchObject(get_called_class());
    }


    public function rememberLogin()
    {
        $token = new Token();
        $hashed_token = $token->getHash();
        $this->remember_token = $token->getValue();
        $this->expiry_timestamp = time() + 60 * 60 * 24 * 30;
        $expires_on = date('Y-m-d H:i:s', $this->expiry_timestamp);

        $data = MyPDO::run(
            "INSERT INTO remember_login (token_hash, user_account_id, expires_at) VALUES (?, ?, ?)",
            [$hashed_token, $this->id, $expires_on]
        )->fetchObject();
    }

    public static function sendPasswordReset($email)
    {
        $user = static::findByEmail($email);
        if ($user && $user->startPasswordReset()) {
            $user->sendPasswordResetEmail();
        }
    }

    protected function startPasswordReset()
    {
        $token = new Token();
        $hashed_token = $token->getHash();
        $this->password_reset_token = $token->getValue();
        $this->expiry_timestamp = time() + 60 * 60 * 2;
        $expires_on = date('Y-m-d H:i:s', $this->expiry_timestamp);

        return MyPDO::run(
            "UPDATE user_account SET password_reset_key = :token_hash, password_reset_expiry = :expires_at WHERE id = :id",
            ['token_hash' => $hashed_token, 'expires_at' => $expires_on, 'id' => $this->id]
        );
    }

    protected function sendPasswordResetEmail()
    {
        $url = 'http://' . $_SERVER['HTTP_HOST'] . '/password/reset/' . $this->password_reset_token;
        $html = 'Hi there, <a href="' . $url . '">Click here to reset password</a>.';
        $text = 'Hi there, <a href="' . $url . '">Click here to reset password</a>.';
        Mail::send($this->email, 'Password reset', $text, $html);
    }

    public static function findByPasswordReset($token)
    {
        $token = new Token($token);
        $hashed_token = $token->getHash();

        $user = MyPDO::run(
            "SELECT * FROM user_account WHERE password_reset_key = ?",
            [$hashed_token]
        )->fetchObject();

        if ($user) {
            if (strtotime($user->password_reset_expiry) > time()) {
                return $user;
            }
        }
    }


    public function resetPassword($password)
    {
        $this->password = $password;
//
//        if (empty($this->errors)) {
//
//            $password_hash = password_hash($this->password, PASSWORD_DEFAULT);
//
//            $sql = 'UPDATE users
//                    SET password_hash = :password_hash,
//                        password_reset_hash = NULL,
//                        password_reset_expires_at = NULL
//                    WHERE id = :id';
//
//            $db = static::getDB();
//            $stmt = $db->prepare($sql);
//
//            $stmt->bindValue(':id', $this->id, PDO::PARAM_INT);
//            $stmt->bindValue(':password_hash', $password_hash, PDO::PARAM_STR);
//
//            return $stmt->execute();
//        }

        return false;
    }
}