<?php
include '../templates/sessionstart.php';
@ $userId = $user['id'];
$testsQuery = mysql_query("SELECT tests.name AS test_name, grades.grade AS grade FROM tests "
        . " INNER JOIN grades ON grades.test_id = tests.id WHERE grades.user_student_id = $userId ");

?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <link rel="stylesheet" type="text/css" href="../style/globalstyle.css">
        <link rel="stylesheet" type="text/css" href="../style/tables.css">
        <script type="text/javascript" src="../javaScript/jquery-1.10.2.js"></script>
        <script type="text/javascript" src="../javaScript/jquery.leanModal.min.js"></script>
        <script type="text/javascript" src="../javaScript/profileLB.js"></script>
        <title></title>
    <body>
        <div class="all">
            <?php include '../templates/header.php'; ?>
            <?php include '../templates/studentMenu.php'; ?> 

             <div class = "content">
                <div class = "question-bases"> 
                        <div class = "group-bar">
                            <span>Grades</span>
                        </div>
                        <!-- for po wszystkich bazach dla danego subjecta -->
                        <ul class="list-of-qbases">
                            <?php
                            while ($nextTest = mysql_fetch_array($testsQuery)) { 
                                ?>
                                <li class="album-bar">
                                    <span style="width: 150px; float:left;"><?php echo $nextTest['test_name'] ?></span>
                                    <span><?php echo $nextTest['grade'] ?></span>
                                </li>
                            <?php } ?>
                        </ul>
                </div>
            </div>
        </div>

    </body>
</html>
