<?php

namespace App\Services;

class Auth
{
    public function logout()
    {
        session_start();
        session_unset();
        session_commit();

        setcookie('pid', '', time() - 1, '/');
    }
}