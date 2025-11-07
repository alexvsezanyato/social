<?php

namespace App\Services;

class Auth
{
    public function logout()
    {
        session_start();
        session_unset();
        setcookie('pid', '', time() - 1, '/');
        session_commit();
    }
}