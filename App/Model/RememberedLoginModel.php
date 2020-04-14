<?php
/**
 * File: RememberdLoginModel.php.
 * Author: Self
 * Standard: PSR-2.
 * Do not change codes without permission.
 * Date: 3/10/2020
 */

namespace App\Model;

use App\Token;
use System\Core\BaseModel;
use System\Library\MyPDO;


class RememberedLoginModel extends BaseModel
{
    /**
     * Find a remembered login model by the token
     *
     * @param string $token The remembered login token
     *
     * @return mixed Remembered login object if found, false otherwise
     */
    public static function findByToken($token)
    {
        $token = new Token($token);
        $token_hash = $token->getHash();

        $data = MyPDO::run("SELECT * FROM remember_login WHERE token_hash = ?", [$token_hash])->fetchObject(get_called_class());
        return $data;

    }

    /**
     * Get the user model associated with this remembered login
     *
     * @return User The user model
     */
    public function getUser()
    {
        return UserModel::findByID($this->user_account_id);
    }

    /**
     * See if the remember token has expired or not, based on the current system time
     *
     * @return boolean True if the token has expired, false otherwise
     */
    public function hasExpired()
    {
        return strtotime($this->expires_at) < time();
    }

    /**
     * Delete this model
     *
     * @return void
     */
    public function delete()
    {
        $data = MyPDO::run("DELETE FROM remember_login WHERE token_hash = ?", [$this->token_hash])->fetchObject();
        return $data;
    }
}