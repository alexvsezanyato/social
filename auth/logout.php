<?php
session_start();
session_unset();
setcookie('pid', '', time() - 1, '/');
header('Location: /login.php');
session_commit();
