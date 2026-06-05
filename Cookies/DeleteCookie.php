<?php
setcookie('username', '', [
    'expires'  => time() - 3600,  
    'path'     => '/',             
    'secure'   => true,
    'httponly' => true,
    'samesite' => 'Strict'
]);

unset($_COOKIE['username']);