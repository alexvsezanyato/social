<?php 
require_once __DIR__ . "/check.php";
require_once __DIR__ . "/../app/app.php";

if (User::in()) { 
    App::redirect("/index.php");
    die;
}
