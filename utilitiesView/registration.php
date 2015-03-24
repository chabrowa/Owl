<?php
@ $firstName = strip_tags($_POST['firstName']);
@ $lastName = strip_tags($_POST['lastName']);
@ $username = strip_tags($_POST['username']);
@ $password = strip_tags($_POST['password']);
@ $repeatPassword = strip_tags($_POST['repeatPassword']);
@ $mail = strip_tags($_POST['mail']);
@ $submit = $_POST['submit'];

if ($submit) {
    $connect = mysql_connect("localhost", "root", "") or die("couldn't connect to the database");
    mysql_select_db("qwerty") or die("couldn't find database'");
    $nameChecking = mysql_query("SELECT username FROM users WHERE username='$username'");
    $namecheck = mysql_num_rows($nameChecking);
    $mailChecking = mysql_query("SELECT username FROM users WHERE mail='$mail'");
    $mailcheck = mysql_num_rows($mailChecking);

    $passwordLength = strlen($password);
    $repeatPasswordLength = strlen($repeatPassword);
    $firstNameLength = strlen($firstName);
    $lastNameLength = strlen($lastName);
    $usernameLength = strlen($username);


    if ($passwordLength > 0 && $passwordLength < 25 &&
            $repeatPasswordLength > 0 && $repeatPasswordLength < 25 &&
            $password === $repeatPassword &&
            $firstNameLength > 0 && $firstNameLength < 25 &&
            $lastNameLength > 0 && $lastNameLength < 25 &&
            $usernameLength > 0 && $usernameLength < 25 &&
            $namecheck == 0 && $mailcheck == 0) {

        //&& filter_var($mail, FILTER_VALIDATE_EMAIL)
        $password = md5($password);



        $query = mysql_query("INSERT INTO users VALUES('', '$username','$password','$firstName','$lastName','$mail','','0')");
        header('Location: login.php');
    } else {
        echo 'You field the registration form incorrectly\n';
        echo 'Remember, all fields should be fullfield and cannot be longer than 25 signs';
    }
}
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Reqistration</title>
        <link rel="stylesheet" type="text/css" href="../style/utilitiesStyle.css">
        <script type="text/javascript" src="../javaScript/jquery-1.10.2.js"></script>
        <script type="text/javascript" src="../javaScript/forms.js"></script>
        <style>
            .error {
                color: #F00;
            }
        </style>
    </head>
    <body>
        <div class = "profile">
            <p style="font-weight: bold; margin-left: 130px;">Registration</p>
            <div style="margin-left: 30px;">
                <form name="registration" action="registration.php" method="POST">
                    <table>
                        <tr>
                            <td> First name: </td>
                            <td><input type="text" name="firstName" /><br>
                                <div class="error" data-assosiated-field="firstName"></div></td>
                        </tr>
                        <tr>
                            <td> Last name: </td>
                            <td><input type="text" name="lastName" /><br>
                                <div class="error" data-assosiated-field="lastName"></div></td>
                        </tr>
                        <tr>
                            <td>Username:</td>
                            <td><input type="text" name="username" /><br>
                                <div class="error" data-assosiated-field="username"></div></td>
                        </tr>
                        <tr>
                            <td>Password:</td>
                            <td><input type="password" name="password" /> <br>
                                <div class="error" data-assosiated-field="password"></div></td>
                        </tr>
                        <tr>
                            <td>Repeat password:</td>
                            <td><input type="password" name="repeatPassword" /> <br>
                                <div class="error" data-assosiated-field="repeatPassword"></div></td>
                        </tr>
                        <tr>
                            <td>e-mail:</td>
                            <td><input type="email" name="mail" /><br>
                                <div class="error" data-assosiated-field="mail"></div></td>
                        </tr>
                        <tr>
                            <td></td>
                            <td style="padding-left: 100px; padding-top: 10px;">
                                <input type="submit" name="submit" value="submit">
                            </td>
                        </tr>
                    </table>
                </form>
            </div>
            <div style="margin-left: 50px; margin-top: 10px;">
                <a href="login.php">Go back to login page</a>
            </div>
        </div>
    </body>
</html>

