<?php
$token = bin2hex(random_bytes(32));
setcookie('remembertoken', $token,
    [
        'expires'=> time() + (86400 *15),
        'path'=>'/',
        'secure'=> true,
        'httponly'=> true,
        'samesite'=> 'Lax'



]);