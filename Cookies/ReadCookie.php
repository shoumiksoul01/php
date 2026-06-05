<?php
if(isset($_COOKIE['username'])){
    $name= htmlspecialchars($_COOKIE['username']);
    echo "Welcome back, $name!";

}
else{
    echo "Welcome, new user!";
}