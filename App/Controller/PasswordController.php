<?php

/**
 * File: PasswordController.php.
 * Author: Self
 * Standard: PSR-12.
 * Do not change codes without permission.
 * Date: 3/24/2020
 */

namespace App\Controller;

use App\Model\UserModel;
use System\Core\BaseController;
use System\Core\View;

class PasswordController extends BaseController
{
    public function forgotAction()
    {
        View::renderTemplate('Password/forgot.twig');
    }

    public function requestResetAction()
    {
        UserModel::sendPasswordReset($this->request->post['email']);
        View::renderTemplate('Password/reset_requested.twig');
    }

    public function resetAction($key)
    {
        $user = $this->getUserOrExit($key);

        View::renderTemplate('Password/reset.html', [
            'token' => $key
        ]);
    }

    /**
     * Reset the user's password
     *
     * @return void
     */
    public function resetPasswordAction()
    {
        $token = $_POST['token'];

        $user = $this->getUserOrExit($token);

        if ($user->resetPassword($_POST['password'])) {
            //yaha pe update records
            View::renderTemplate('Password/reset_success.html');
        } else {
            View::renderTemplate('Password/reset.twig', [
                'token' => $token,
                'user' => $user
            ]);
        }
    }

    /**
     * Find the user model associated with the password reset token, or end the request with a message
     *
     * @param string $token Password reset token sent to user
     *
     * @return mixed User object if found and the token hasn't expired, null otherwise
     */
    protected function getUserOrExit($token)
    {
        $user = UserModel::findByPasswordReset($token);

        if ($user) {
            return $user;
        } else {
            View::renderTemplate('Password/token_expired.html');
            exit;

        }
    }
}
