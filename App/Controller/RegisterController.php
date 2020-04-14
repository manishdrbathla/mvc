<?php

/**
 * File: RegisterController.php.
 * Author: Self
 * Standard: PSR-12.
 * Do not change codes without permission.
 * Date: 3/24/2020
 */

namespace App\Controller;

use System\Core\BaseController;
use System\Core\View;

class RegisterController extends BaseController
{
    private $error = array();

    public function indexAction()
    {
        if ($this->request->server['REQUEST_METHOD'] === 'POST' && $this->validate()) {
            if ($this->AntiCSRF->validateRequest()) {
                echo $this->purifier->purify($this->request->post['first_name']);
                echo $this->purifier->purify($this->request->post['last_name']);
                echo $this->purifier->purify($this->request->post['mobile_number']);
                echo $this->purifier->purify($this->request->post['email']);
                echo $this->purifier->purify($this->request->post['password']);
            } else {
                echo 'Log a CSRF attack attempt';
            }
        }

        $data['error_first_name'] = $this->error['first_name'] ?? '';
        $data['error_last_name'] = $this->error['last_name'] ?? '';
        $data['error_mobile_number'] = $this->error['mobile_number'] ?? '';
        $data['error_email'] = $this->error['email'] ?? '';
        $data['error_password'] = $this->error['password'] ?? '';

        $data['first_name'] = $this->request->post['first_name'] ?? '';
        $data['last_name'] = $this->request->post['last_name'] ?? '';
        $data['mobile_number'] = $this->request->post['mobile_number'] ?? '';
        $data['email'] = $this->request->post['email'] ?? '';

        $data['base_url'] = BASE_URL;

        View::renderTemplate('Register/register.twig', $data);
    }

    protected function validate()
    {
        if (trim($this->request->post['first_name']) === '' || strlen(trim($this->request->post['first_name'])) > 32) {
            $this->error['first_name'] = 'Required';
        }

        if (trim($this->request->post['last_name']) === '' || strlen(trim($this->request->post['last_name'])) > 32) {
            $this->error['last_name'] = 'Required!';
        }

        if (trim($this->request->post['mobile_number']) === '') {
            $this->error['mobile_number'] = 'Required!';
        }

        if (preg_match('/^[0-9]$/', $this->request->post['mobile_number']) && mb_strlen($this->request->post['mobile_number']) !== (int)'10') {
            $this->error['mobile_number'] = 'Your 10 digits mobile number!';
        }

        if (trim($this->request->post['email']) === '') {
            $this->error['email'] = 'Required!';
        }

        if (!filter_var($this->request->post['email'], FILTER_VALIDATE_EMAIL)) {
            $this->error['email'] = 'Invalid email!';
        }

        if (strlen(trim($this->request->post['password'])) < 8) {
            $this->error['password'] = 'At least 8 character password';
        }

        return !$this->error;
    }
}