<?php
include '../templates/sessionstart.php';

@ $refresh = $_POST['refresh'];
@ $student = $_POST['student'];
@ $supervisor = $_POST['supervisor'];
@ $delete = $_POST['delete'];
@ $user_id = $_POST['user_id'];

if($refresh){
    header('Location: indexAdmin.php');
}

if($student){
    $userToStudentQuery = mysql_query("UPDATE users SET duty = 'student', access = 1 WHERE id = $user_id");
}

if($supervisor){
    $userToSupervisorQuery = mysql_query("UPDATE users SET duty = 'supervisor', access = 1 WHERE id = $user_id");
}

if($delete){
    $deleteUser = mysql_query("DELETE FROM users WHERE id = $user_id");
}

?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <link rel="stylesheet" type="text/css" href="../style/globalstyle.css">
        <link rel="stylesheet" type="text/css" href="../style/tables.css">
        <title></title>
    </head>
    <body>
        <div class="all">
            <?php include '../templates/header.php'; ?>
            <?php include '../templates/menu.php'; ?> 


            <div class = "content">
                <div class = "question-bases"> 
                    <div class = "group-bar">
                        <span>Registered users list</span>
                        <div class="align-right">
                            <form action="indexAdmin.php" method="POST">
                            <input type="submit" name="refresh" value="Refresh" />
                            </form>
                        </div>
                    </div>

                    <ul class="list-of-qbases">
                        <?php
                        $noaccessUsersQuery = mysql_query("SELECT * FROM users WHERE access = 0");
                        while ($nextUser = mysql_fetch_assoc($noaccessUsersQuery)) {
                            ?>
                            <li class="album-bar">
                                <span><?php echo $nextUser['firstname'] ?> <?php echo $nextUser['lastname'] ?> <?php echo $nextUser['mail'] ?></span>
                                <div class="align-right">
                                    <form action="indexAdmin.php" method="POST">
                                        <input type="hidden" name="user_id" value="<?php echo $nextUser['id'] ?>"/>
                                        <input type="submit" name="student" value="Student"/>
                                        <input type="submit" name="supervisor" value="Supervisor"/>
                                        <input type="submit" name="delete" value="Delete"/>
                                    </form>
                                </div>
                            </li>
                        <?php } ?>
                    </ul>
                </div>
            </div>
        </div>
    </body>
</html>
