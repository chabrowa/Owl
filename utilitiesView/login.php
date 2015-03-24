<?php
session_start();

@ $username = $_POST['username'];
@ $password = $_POST['password'];
@ $submit = $_POST['logIn'];

if ($submit) {
    if ($username && $password) {
        $password = md5($password);

        $connect = mysql_connect("localhost", "root", "") or die("couldn't connect to the database");
        mysql_select_db("qwerty") or die("couldn't find database'");

        $query = mysql_query("SELECT * FROM users WHERE username='$username' AND password='$password'");
        $rows = mysql_num_rows($query);

        if ($rows == 1) {
            $user = mysql_fetch_array($query);
            if ($user['access'] == 1) {
                $_SESSION['id'] = $user['id'];
                $duty = 'student';
                if ($user['duty'] == $duty) {
                    header('Location: ..\studentView\indexStudentsProfile.php');
                } else {
                    header('Location: ..\supervisorView\indexProfile.php');
                }
            } else {
                header('Location: login.php');
            }
        } else {
            echo 'input data is not valid';
        }
    } else {
        echo 'fields cannot be empty!';
    }
}
?>


<!DOCTYPE html>

<html>
    <head>
        <meta charset="UTF-8">
        <title>Welcome</title>
        <link rel="stylesheet" type="text/css" href="../style/utilitiesStyle.css">
        <script type="text/javascript" src="../javaScript/jquery-1.10.2.js"></script>
        <script type="text/javascript" src="../javaScript/forms.js"></script>
    </head>
    <body>
        <div class='all'>
            <div class='content'>
                <div class='loginForm'>
                    <form name="login" action="login.php" method="POST">
                        <div class='inputslogin'>
                            Username: <input type="text" name="username"> <br>
                            <div class="error" data-assosiated-field="username"></div>
                            Password:&nbsp <input type="password" name="password"> <br>
                            <div class="error" data-assosiated-field="password"></div>
                        </div>
                        <input type="submit" class="submit-on-right" name="logIn" value="Log in">
                    </form>
                </div>
                <div class ='links'>
                    <a href="registration.php" >You don't have account?</a><br>
                </div>
            </div>
        </div>
    </body>
</html>
