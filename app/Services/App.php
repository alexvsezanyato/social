<?php

namespace App\Services;

class App {
    static function redirect(string $whereto): bool { 
        header("Location: $whereto");
        return true;
    }
}
