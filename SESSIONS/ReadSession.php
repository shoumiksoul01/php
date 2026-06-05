<?php

session_start();

if(isset($_SESSION['logged_in'])&& $_SESSION['logged_in']===true){
    echo "Hello,". htmlspecialchars($_SESSION['username'])."!"; 


}
else{
    header('Location: /login.php');
    exit();
}