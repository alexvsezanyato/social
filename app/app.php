<?php

class App {
    static function redirect($whereto) { 
        header("Location: $whereto");
        return;
    }
}
