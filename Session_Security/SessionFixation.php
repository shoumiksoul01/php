<?php
session_start();

if ($loginSuccessful) {
    // true = delete the old session file
    session_regenerate_id(true);

    $_SESSION['user_id']   = $user['id'];
    $_SESSION['logged_in'] = true;
    // Now the attacker's old ID is worthless
}