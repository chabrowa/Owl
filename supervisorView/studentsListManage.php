<?php
include '../templates/sessionstart.php';
@ $userId = $user['id'];
@ $studentsBaseId = $_GET['nextStudentsBase'];
@ $submitStudent = $_POST['submitStudent'];
@ $deleteStudentId = $_GET['deleteStudentId'];

@ $submitStudentBaseQuery = mysql_query("SELECT * FROM studentsbases WHERE id = '$studentsBaseId'");
@ $studentBaseFullRow = mysql_fetch_array($submitStudentBaseQuery);

if ($submitStudent) {
    $studentId = $_POST['studentId'];

    $query = mysql_query("INSERT INTO studentsinbases VALUES('', '$studentId','$studentsBaseId')");
}

if ($deleteStudentId) {
    $query = mysql_query("DELETE FROM studentsinbases WHERE student_id = '$deleteStudentId'");
}
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <link rel="stylesheet" type="text/css" href="../style/globalstyle.css">
        <link rel="stylesheet" type="text/css" href="../style/tables.css">
        <script type="text/javascript" src="../javaScript/jquery-1.10.2.js"></script>
        <script type="text/javascript" src="../javaScript/jquery.leanModal.min.js"></script>
        <script type="text/javascript" src="../javaScript/studentBaseLB.js"></script>
        <script type="text/javascript" src="../select2/select2.min.js"></script>
        <link rel="stylesheet" type="text/css" href="../select2/select2.css">
        <title></title>
    <body>
        <div class="all">
            <?php include '../templates/header.php'; ?>
            <?php include '../templates/menu.php'; ?>

            <div class = "content">
                <div class = "question-bases"> 
                    <div class = "group-bar">
                        <div>
                            <span><?php echo $studentBaseFullRow['name']; ?></span> 
                            <span class="align-right">
                                <a href="indexStudents.php"><button>Return</button></a>
                            </span>
                        </div>
                    </div>

                    <ul class="list-of-qbases">
                        <div class='studentListTable'>
                            <div class="studentListText">No students assigned to this group</div>
                        </div>
                        <?php
                        $query = mysql_query("SELECT users.* FROM studentsinbases INNER JOIN users ON users.id = studentsinbases.student_id WHERE studentsinbases.studentbase_id = '$studentsBaseId'");
                        while ($nextStudent = mysql_fetch_assoc($query)) {
                            ?>
                            <li class="album-bar">
                                <span><?php echo $nextStudent['firstname']; ?></span>
                                <span><?php echo $nextStudent['lastname']; ?></span>
                                <span><?php echo $nextStudent['mail']; ?></span>

                                <div class="align-right">
                                    <a href="studentsListManage.php?nextStudentsBase=<?php echo $studentsBaseId; ?>&deleteStudentId=<?php echo $nextStudent['id']; ?>"><button>Remove</button></a>
                                </div>
                            </li>
                        <?php } ?>
                    </ul>

                    <div id="studentTable_inputContainer">
                        <div name='addingNewStudentForm' id="studentsTrTemplate" class='studentDiv'>
                            <form name="addStudent" action="studentsListManage.php?nextStudentsBase=<?php echo $studentsBaseId; ?>" method="POST">
                                <input type="hidden" id="e1" name="studentId" style="width:300px"/> 
                                <input type="submit" name="submitStudent" value="Add">
                            </form>
                        </div>
                    </div>

                </div>
            </div>

            <?php include '../lightBoxes/StudentBaseLBTemp.php'; ?> 
            <?php include './getJsUserAutocompleteList.php'; ?>
    </body>
</html>
