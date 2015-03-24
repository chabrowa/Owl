<?php
include '../templates/sessionstart.php';
@ $userId = $user['id'];
@ $subjectsQuery = mysql_query("SELECT * FROM subjects WHERE users_id='$userId'");
@ $addingNewStudentBase = $_POST['add-new-student-base'];
@ $editingStudentBase = $_POST['edit-student-base'];

if ($addingNewStudentBase) {
    $choosenSubjectId = $_POST['choosenSubjectId'];
    $studentBaseTitle = $_POST['student-base-title'];
    if (strlen($studentBaseTitle) != 0) {
        $query = mysql_query("INSERT INTO studentsbases VALUES('','$choosenSubjectId','$studentBaseTitle')");
    }
}

if ($editingStudentBase) {
    $choosenGroupId = $_POST['choosenGroupId'];
    $studentBaseTitle = $_POST['student-base-title'];
    if (strlen($studentBaseTitle) != 0) {
        $query = mysql_query("UPDATE studentsbases SET name='$studentBaseTitle' WHERE id='$choosenGroupId'");
    }
}

@ $deleteSbase = $_POST['deleteSbase'];
if($deleteSbase){
    $sbaseId = $_POST['sbaseId'];
    $deleteQbaseQuery = mysql_query("DELETE FROM studentsbases WHERE id = $sbaseId");
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
                    <?php
                    while ($nextSubject = mysql_fetch_assoc($subjectsQuery)) {
                        $nextSubjectId = $nextSubject['id'];
                        ?>
                        <div class = "group-bar">
                            <span><?php echo $nextSubject['name']; ?></span>
                            <div class="align-right">
                                <input href="#addingNewStudentBase" data-subject-id='<?php echo $nextSubjectId ?>' 
                                       type="button" class='addNewStudentBaseBtn' value="Add students group"/>
                            </div>
                        </div>
                        <!-- for po wszystkich bazach dla danego subjecta -->
                        <ul class="list-of-qbases">
                            <?php
                            $qbasesQuery = mysql_query("SELECT * FROM studentsbases WHERE subject_id = '$nextSubjectId'");
                            while ($nextStudentsBase = mysql_fetch_assoc($qbasesQuery)) {
                                ?>
                                <li class="album-bar">
                                    <span><?php echo $nextStudentsBase['name'] ?></span>
                                    <div class="align-right">
                                        <a href="studentsListManage.php?nextStudentsBase=<?php echo $nextStudentsBase['id']; ?>"><button>Manage students list</button></a>
                                        <a href="showGrades.php?nextStudentsBase=<?php echo $nextStudentsBase['id']; ?>"><button>Show grades</button></a>
                                        <input href="#editingStudentBase" data-group-id='<?php echo $nextStudentsBase['id'] ?>' 
                                               type="button" class='editStudentBaseBtn' value="Edit"/>
                                        <form action="indexStudents.php" method="POST" style="display: inline;">
                                            <input type="hidden" name="sbaseId" value="<?php echo $nextStudentsBase['id'];?>"/>
                                            <input type="submit" name="deleteSbase" value="Delete" />
                                        </form>
                                    </div>
                                </li>
                            <?php } ?>
                        </ul>
                    <?php }
                    ?>


                </div>
            </div>
        </div>

        <?php include '../lightBoxes/StudentBaseLBTemp.php'; ?> 
    </body>
</html>
