<?php

session_start();

if (!$_SESSION['id']) {
    header('Location: login.php');
} else {
    $connect = mysql_connect("localhost", "root", "") or die("couldn't connect to the database");
    mysql_select_db("qwerty") or die("couldn't find database'");
    $currentuser = $_SESSION['id'];
    @ $query = mysql_query("SELECT * FROM users WHERE id='$currentuser'");
    @ $user = mysql_fetch_array($query);
}
?>