<?php
include '../templates/sessionstart.php';
@ $userId = $user['id'];
@ $subjectsQuery = mysql_query("SELECT * FROM subjects WHERE users_id='$userId'");
@ $addingNewStudentBase = $_POST['add-new-student-base'];
@ $studentsBaseId = $_GET['nextStudentsBase'];
@ $studentsBaseQuery = mysql_query("SELECT * FROM studentsbases WHERE id = $studentsBaseId");
@ $studentsBase = mysql_fetch_array($studentsBaseQuery);
@ $addTest = $_POST['add-test'];
@ $editTest = $_POST['edit-test'];

if ($addTest) {
    $choosenGroupId = $_POST['choosenGroupId'];
    $testTitle = $_POST['test-title'];
    if (strlen($testTitle) != 0) {
        $currentDate = date("y.m.d");
        $query = mysql_query("INSERT INTO tests VALUES ( '', '$userId', '$choosenGroupId', '$testTitle','$currentDate')");
        $testId = mysql_insert_id();

        if ($testId != FALSE) {
            $studentsQuery = mysql_query("SELECT users.* FROM studentsinbases INNER JOIN users ON users.id = studentsinbases.student_id WHERE studentsinbases.studentbase_id = '$studentsBaseId'");
            $rowNumber = mysql_num_rows($studentsQuery);
            $counter = 1;
            while ($nextStudent = mysql_fetch_assoc($studentsQuery)) {
                $studentId = $nextStudent['id'];
                $studentGrade = $_POST["studentGrade" . $counter];
                if ($studentGrade != NULL) {
                    $addGradeQuerry = mysql_query("INSERT INTO grades VALUES ('', '$testId', '$studentId', '$studentGrade')");
                }
                $counter++;
            }
        }
    }
}

if ($editTest) {
    $choosenTestId = $_POST['choosenTestId'];
    $testTitle = $_POST['choosenTestName'];

    if (strlen($testTitle) != 0) {
        $query = mysql_query("UPDATE tests SET name = $testTitle WHERE id = $choosenTestId");

        $deleteGredesQuery = mysql_query("DELETE FROM grades WHERE test_id = $choosenTestId ");
        $studentsQuery = mysql_query("SELECT users.* FROM studentsinbases INNER JOIN users ON users.id = studentsinbases.student_id WHERE studentsinbases.studentbase_id = '$studentsBaseId'");
        $rowNumber = mysql_num_rows($studentsQuery);
        $counter = 1;
        while ($nextStudent = mysql_fetch_assoc($studentsQuery)) {
            $studentId = $nextStudent['id'];
            $studentGrade = $_POST["studentGrade" . $counter];
            if ($studentGrade != NULL) {
                $addGradeQuerry = mysql_query("INSERT INTO grades VALUES ('', '$choosenTestId', '$studentId', '$studentGrade')");
            }
            $counter++;
        }
    }
}
@ $deleteColumn = $_POST['deleteColumn'];
if ($deleteColumn) {
    $testId = $_POST['testId'];
    $deleteColumnQuery = mysql_query("DELETE FROM tests WHERE id = $testId");
    header('Location: showGrades.php?nextStudentsBase=' . $studentsBaseId);
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
        <link rel="stylesheet" type="text/css" href="../style/gradesStyle.css">
        <title></title>
    <body>
        <div class="all">
<?php include '../templates/header.php'; ?>
            <?php include '../templates/menu.php'; ?>

            <div class = "content">
                <div class = "question-bases"> 
                    <div class = "group-bar">
                        <span><?php echo $studentsBase['name']; ?></span>
                        <div class="align-right">
                            <span class="addGreadesColumnClass">
                                <input href="#addingGradesColumnModal" data-group-id='<?php echo $studentsBaseId ?>' 
                                       type="button" class='addGradesColumnBtn' value="Add new grades column"/>
                            </span>
                            <a href="indexStudents.php"><button>Return</button></a>


                        </div>
                    </div>
                    <table id="students">
                        <tr class="gradeHeaderRow">
                            <th>List of students</th>
<?php
$testQuery = mysql_query("SELECT tests.id AS testId, tests.name AS testName FROM tests "
        . "WHERE tests.studentsbase_id = $studentsBaseId");

$testsArray = Array();
while ($row = mysql_fetch_assoc($testQuery)) {
    $test = Array(
        "id" => $row["testId"],
        "name" => $row["testName"]
    );
    $testsArray[$row["testId"]] = $test;
}

$studentGradesQuery = mysql_query(""
        . "SELECT users.id AS userId, users.firstname AS userFirstname, users.lastname AS userLastname, "
        . "grades.grade AS grade, grades.test_id AS testId "
        . "FROM studentsinbases "
        . "INNER JOIN users ON users.id = studentsinbases.student_id "
        . "LEFT JOIN grades ON users.id = grades.user_student_id "
        . "WHERE studentsinbases.studentbase_id = $studentsBaseId"
);
$studentsGradesArray = Array();
while ($row = mysql_fetch_assoc($studentGradesQuery)) {
    // czy w tablicy studentów nie ma już danych studenta (czy w jej kluczach jest już id studenta
    if (!array_key_exists($row["userId"], $studentsGradesArray)) {
        // nie ma studenta, tworzymy nowa tablice dla studenta
        $studentGrades = Array(
            "firstname" => $row["userFirstname"],
            "lastname" => $row["userLastname"],
            "gradesArray" => Array()
        );
    } else {
        // student juz jest, pobieramy go
        $studentGrades = $studentsGradesArray[$row["userId"]];
    }
    $testId = $row["testId"];
    // czy w wierszu $row jest ocena, czy tylko dane studenta
    if ($testId !== null) {
        // wpisujemy ocene studentowi
        $studentGrades["gradesArray"][$row["testId"]] = $row["grade"];
    }
    $studentsGradesArray[$row["userId"]] = $studentGrades;
}
//wypisujemy testy w wierszu nagøówkowym
foreach ($testsArray as $testId => $test) {
    ?>
                                <th class="testName"><?php echo $test["name"]; ?>
                                    <input href="#editingGradesColumnModal" data-test-id='<?php echo $test["id"]; ?>' data-test-name="<?php echo $test["name"]; ?>"
                                           type="image" src="../images/edit.png" alt="Edit" style='width: 15px; height: 15px;' 
                                           class='editGradesColumnBtn'/>
                            <form action="showGrades.php?nextStudentsBase=<?php echo $studentsBaseId; ?>" method="POST" style="display: inline;">
                                <input type="hidden" name="testId" value="<?php echo $test["id"]; ?>"/>
                                <input type="image" value="submit" alt="delete" name="deleteColumn" src='../images/delete.png' style='width: 15px; height: 15px;'   />

                            </form>

                            </th>
<?php } ?>

                        </tr>
<?php
//wiersze studentow
foreach ($studentsGradesArray as $userId => $studentData) {
    echo "<tr>";
    //komorka z imieniem i nazwiskiem
    echo "<td>" . $studentData["firstname"] . " " . $studentData["lastname"] . "</td>";
    // dane z testow
    foreach ($testsArray as $testId => $test) {
        // jesli odpowiedzial na test
        if (array_key_exists($testId, $studentData["gradesArray"])) {
            // to wpisujemy ocene
            echo "<td data-student-id='" . $userId . "' data-test-id='" . $testId . "'>" . $studentData["gradesArray"][$testId] . "</td>";
        } else {
            //user nie ma oceny
            echo "<td></td>";
        }
    }
    echo "</tr>";
}
?>
                    </table>
                </div>
            </div>
        </div>

<?php include '../lightBoxes/StudentBaseLBTemp.php'; ?> 
    </body>
</html>
