<?php
include '../templates/sessionstart.php';

@ $subject = $_POST['subject'];
@ $subjectSubmit = $_POST['submitSubject'];
@ $userId = $user['id'];
@ $subjectsQuery = mysql_query("SELECT * FROM subjects WHERE users_id='$userId'");
@ $update = $_POST['update'];
@ $deleteSubjectId = $_GET['deleteSubjectId'];
@ $submitNewPassword = $_POST['submitNewPassword'];


if ($subjectSubmit) {

    $query = mysql_query("INSERT INTO subjects VALUES('',$userId,'$subject')");
    $subjectsQuery = mysql_query("SELECT * FROM subjects WHERE users_id='$userId'");
}

if ($deleteSubjectId) {
    $DefaultSubjectName = '_DEFAULT_SUBJECT_';
    $allQbasesQuery = mysql_query("SELECT * FROM qbases WHERE subject_id = '$deleteSubjectId'");
    $allStudentBaseQuery = mysql_query("SELECT * FROM studentsbases WHERE subject_id = '$deleteSubjectId'");
    $checkingDefaultQuery = mysql_query("SELECT * FROM subjects WHERE users_id = '$userId' AND name = '$DefaultSubjectName'");

    if (mysql_num_rows($checkingDefaultQuery) == 0) {
        $creatingDeloultSubject = mysql_query("INSERT INTO subjects VALUES ('', '$userId', '$DefaultSubjectName')");
        $defaultSubjectId = mysql_insert_id();
    } else {
        $defaultSubject = mysql_fetch_array($checkingDefaultQuery);
        $defaultSubjectId = $defaultSubject['id'];
    }

    if (mysql_num_rows($allQbasesQuery) != 0) {
        while ($nextQbase = mysql_fetch_assoc($allQbasesQuery)) {
            $updateQbasesQuery = mysql_query("UPDATE qbases SET subject_id = '$defaultSubjectId' ");
        }
    }
    if (mysql_num_rows($allStudentBaseQuery) != 0) {
        while ($nextQbase = mysql_fetch_assoc($allStudentBaseQuery)) {
            $updateStudentsBaseQuery = mysql_query("UPDATE studentsbases SET subject_id = '$defaultSubjectId' ");
        }
    }
    $sql_query = "DELETE FROM subjects WHERE users_id = '$userId' AND subjects.id = '$deleteSubjectId'";
    $deleteQuery = mysql_query($sql_query);
    $subjectsQuery = mysql_query("SELECT * FROM subjects WHERE users_id='$userId'");
}

if ($update) {
    $firstName = strip_tags($_POST['firstName']);
    $lastName = strip_tags($_POST['lastName']);
    $username = strip_tags($_POST['username']);
    $mail = strip_tags($_POST['mail']);



    if ($username != $user['username']) {
        $nameChecking = mysql_query("SELECT username FROM users WHERE username='$username'");
        $namecheck = mysql_num_rows($nameChecking);
    } else {
        $namecheck = 0;
    }
    if ($mail != $user['mail']) {

        $mailChecking = mysql_query("SELECT username FROM users WHERE mail='$mail'");
        $mailcheck = mysql_num_rows($mailChecking);
    } else {
        $mailcheck = 0;
    }

    $firstNameLength = strlen($firstName);
    $lastNameLength = strlen($lastName);
    $usernameLength = strlen($username);

    if ($firstNameLength > 0 && $firstNameLength < 25 &&
            $lastNameLength > 0 && $lastNameLength < 25 &&
            $usernameLength > 0 && $usernameLength < 25 &&
            $namecheck == 0 && $mailcheck == 0) {

        //&& filter_var($mail, FILTER_VALIDATE_EMAIL)

        $query = mysql_query("UPDATE users SET username='$username', firstname='$firstName', lastname='$lastName', mail='$mail' WHERE id=$userId ");
        $updateQuery = mysql_query("SELECT * FROM users WHERE username='$username'");
        $user = mysql_fetch_array($updateQuery);
    }
}

if ($submitNewPassword) {
    $currentPassword = md5($_POST['currentPassword']);
    $newPassword = $_POST['newPassword'];
    $repeatNewPassword = $_POST['repeatNewPassword'];

    if ($currentPassword == $user['password']) {
        if ($newPassword == $repeatNewPassword) {
            $newPassword = md5($newPassword);
            $updatePasswordQuery = mysql_query("UPDATE users SET password='$newPassword' WHERE id = '$userId'");
            $subjectsQuery = mysql_query("SELECT * FROM subjects WHERE users_id='$userId'");
        }
    }
}
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <link rel="stylesheet" type="text/css" href="../style/globalstyle.css">
        <script type="text/javascript" src="../javaScript/jquery-1.10.2.js"></script>
        <script type="text/javascript" src="../javaScript/jquery.leanModal.min.js"></script>
        <script type="text/javascript" src="../javaScript/profileLB.js"></script>
        <title>Your Profile</title>
    </head>
    <body>
        <div class="all">
            <?php include '../templates/header.php'; ?>
            <?php include '../templates/menu.php'; ?> 


            <div class = "content">
                <div class="inner-content">
                    <div class ="profile-left-side">
                        <div class ="updateform">
                            <p>Update your profile data</p>
                            <form name="update" action="indexProfile.php" method="POST">
                                <table>
                                    <tr>
                                        <td>First name:</td>
                                        <td><input type="text" name="firstName" value="<?php echo $user['firstname']; ?>"/><br>
                                            <div class="error" data-assosiated-field="firstName"></div></td>
                                    </tr>
                                    <tr>
                                        <td>Last name:</td>
                                        <td><input type="text" name="lastName" value="<?php echo $user['lastname']; ?>"/><br>
                                            <div class="error" data-assosiated-field="lastName"></div></td>
                                    </tr>
                                    <tr>
                                        <td>Username:</td>
                                        <td><input type="text" name="username" value="<?php echo $user['username']; ?>"/><br>
                                            <div class="error" data-assosiated-field="username"></div></td>
                                    </tr>
                                    <tr>
                                        <td>e-mail:</td>
                                        <td><input type="email" name="mail" value="<?php echo $user['mail']; ?>"/><br>
                                            <div class="error" data-assosiated-field="mail"></div></td>
                                    </tr>
                                    <tr>
                                        <td><input type="submit" name="update" value="update"/></td>
                                    </tr>
                                </table>
                            </form>
                        </div>

                        <div class="passwordChanging">
                            <p>Change your password</p>
                            <input href="#passwordChangeModal" type="button" name="passwordChange" value="Change password"/>
                        </div>

                        <div id="passwordChangeModal" class="emi-modal">
                            <div class="emi-modal-title">Password changing</div>
                            <div class="emi-modal-content">
                                <form name="passwordChange" action="indexProfile.php" method="POST">
                                    <table>
                                        <tr>
                                            <td>Current Password:</td>
                                            <td><input type="password" name="currentPassword"/><br>
                                                <div class="error" data-assosiated-field="currentPassword"></div></td>
                                        </tr>
                                        <tr>
                                            <td>New password:</td>
                                            <td><input type="password" name="newPassword"/><br>
                                                <div class="error" data-assosiated-field="newPassword"></div></td>
                                        </tr>
                                        <tr>
                                            <td>Repeat new password:</td>
                                            <td><input type="password" name="repeatNewPassword"/><br>
                                                <div class="error" data-assosiated-field="repeatNewPassword"></div></td>
                                        </tr>
                                        <tr>
                                            <td colspan="2" class="submit-on-right"><input type="submit" name="submitNewPassword" value="submit"/></td>
                                        </tr>
                                    </table>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="profile-right-side">
                        <div class="subjects">
                            <p>List of subjects:</p>
                            <table>

                                <?php
                                while ($nextSubject = mysql_fetch_assoc($subjectsQuery)) {
                                    echo "<tr><td>";
                                    echo $nextSubject['name'] . "</td><td>";
                                    echo "<a href='indexProfile.php?deleteSubjectId=" . $nextSubject['id'] . "'><button>Delete</button></a></td></tr>";
                                }
                                ?>

                            </table>
                            <form action="indexProfile.php" method="POST">
                                Add next: <input type="text" name="subject"/>
                                <input type="submit" name="submitSubject" value="Add" />
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>
