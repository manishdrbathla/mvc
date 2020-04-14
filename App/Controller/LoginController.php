<?php

/**
 * File: LoginController.php.
 * Author: Self
 * Standard: PSR-2.
 * Do not change codes without permission.
 * Date: 2/22/2020
 */

namespace App\Controller;

use App\Model\UserModel;
use System\Core\BaseController;
use System\Core\View;
use \App\Auth;
use \App\FlashMessage;

class LoginController extends BaseController
{

    public function indexAction()
    {
        View::renderTemplate('Login/new.twig');
    }

    public function createAction()
    {
        $user = UserModel::authenticate($_POST['email'], $_POST['password']);

        $remember_me = isset($_POST['remember_me']);

        if ($user) {
            Auth::login($user, $remember_me);
            $this->redirect(Auth::getReturnToPage());
        } else {
            FlashMessage::addMessage('Login unsuccessful, please try again', FlashMessage::WARNING);
            View::renderTemplate('Login/new.twig', [
                'email' => $_POST['email'],
                'remember_me' => $remember_me
            ]);
        }
    }

    public function logoutAction()
    {
        Auth::logout();
        $this->redirect('/');
    }

    public function showLogoutMessageAction()
    {
        FlashMessage::addMessage('Logout successful');
        $this->redirect('/');
    }
}
