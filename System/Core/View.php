<?php

/**
 * File: View.php.
 * Author: Self
 * Standard: PSR-2.
 * Do not change codes without permission.
 * Date: 2/22/2020
 */

namespace System\Core;

use App\Auth;
use ParagonIE\AntiCSRF\AntiCSRF;
use Twig\TwigFunction;

class View
{
    public static function renderTemplate($template, $args = [])
    {
        static $twig = null;

        if ($twig === null) {
            $loader = new \Twig\Loader\FilesystemLoader(TEMP_DIR);
            $twig = new \Twig\Environment($loader);
            $twig->addGlobal('current_user', Auth::getUser());
            $twig->addFunction(new TwigFunction('form_token', function ($lock_to = null) {
                static $csrf;
                if ($csrf === null) {
                    $csrf = new AntiCSRF;
                }
                return $csrf->insertToken($lock_to, false);
            }, ['is_safe' => ['html']]));
        }

        echo $twig->render($template, $args);
    }
}
