<?php
session_name("PHPSESSID");


session_set_cookie_params([
    'lifetime' => 0,
    'path'     => '/',
    'domain'   => '',    
    'secure'   => true,   
    'httponly' => true,   
    'samesite' => 'Lax'   
]);

ini_set('session.use_strict_mode', '1');
ini_set('session.use_cookies', '1');
ini_set('session.use_only_cookies', '1');
ini_set('session.cache_limiter', 'nocache');
ini_set('session.gc_maxlifetime', '1440');

session_start();
