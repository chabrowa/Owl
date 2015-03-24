<?php
include '../templates/sessionstart.php';
@ $userId = $user['id'];
@ $testId = $_GET['testId'];
@ $currentTestQuery = mysql_query("SELECT DISTINCT questions.qcontent AS question_content, questions.id AS question_id,"
        . "portal_test_occ_question.id AS portal_test_question_id "
        . "FROM portaltests "
        . "INNER JOIN portal_test_occ ON portal_test_occ.portaltest_id=portaltests.id "
        . "INNER JOIN portal_test_occ_question ON portal_test_occ_question.portal_test_occ_id = portal_test_occ.id "
        . "INNER JOIN portal_test_occ_answer ON portal_test_occ_answer.portal_test_question_id = portal_test_occ_question.id "
        . "INNER JOIN questions ON questions.id = portal_test_occ_question.question_id "
        . "INNER JOIN answers ON answers.id = portal_test_occ_answer.answer_id "
        . "WHERE portal_test_occ.student_id = $userId AND portaltests.id = $testId "
        . "ORDER BY portal_test_occ_question.id");

@ $submitTest = $_POST['submitTest'];
if ($submitTest) {
    while ($row = mysql_fetch_assoc($currentTestQuery)) {
        $currentQuestionId = $row['question_id'];
        $currentPortalTestQuestionId = $row['portal_test_question_id'];
        $currentAnswersQuery = mysql_query("SELECT DISTINCT portal_test_occ_answer.id AS id, answers.acontent AS acontent "
                . "FROM answers INNER JOIN portal_test_occ_answer ON answers.id = portal_test_occ_answer.answer_id "
                . "WHERE portal_test_occ_answer.portal_test_question_id = $currentPortalTestQuestionId "
                . "ORDER BY portal_test_occ_answer.id");
        while ($answersRow = mysql_fetch_assoc($currentAnswersQuery)) {
            $answersArray[] = $answersRow['id'];
        }
    }
    foreach ($answersArray as &$nextId) {

        if (isset($_POST["answer" . $nextId])) {
            $answer = $_POST["answer" . $nextId];
            $insertAnswer = mysql_query("INSERT INTO students_answers VALUES('$userId', '$nextId')");
        }
    }

    $calculatingGradeQuery = mysql_query("SELECT 
                                        SUM(CASE WHEN 
                                                answers.correctness = 0
                                                THEN
                                                - questions.`wrong-answer` / 100 * questions.points
                                                ELSE
                                                questions.points / questions.correct_answer_count
                                                END) * 100 / portaltests.total_points_number AS POINTS
                                        FROM
                                                users
                                                INNER JOIN
                                                students_answers ON students_answers.student_id = users.id
                                                INNER JOIN
                                                portal_test_occ_answer ON portal_test_occ_answer.id = students_answers.test_occ_answer_id
                                                INNER JOIN
                                                answers ON portal_test_occ_answer.answer_id = answers.id
                                                INNER JOIN
                                                portal_test_occ_question ON portal_test_occ_question.id = portal_test_occ_answer.portal_test_question_id
                                                INNER JOIN
                                                questions ON questions.id = portal_test_occ_question.question_id
                                                INNER JOIN
                                                portal_test_occ ON portal_test_occ.id = portal_test_occ_question.portal_test_occ_id
                                                INNER JOIN
                                                portaltests ON portaltests.id = portal_test_occ.portaltest_id
                                        WHERE
                                                portal_test_occ.portaltest_id = $testId
                                                AND
                                                users.id = $userId");
    $gradeRow = mysql_fetch_array($calculatingGradeQuery);
    $grade = $gradeRow['POINTS'];
    $roundedGrade = round($grade, 2); 
    $testIdQuery = mysql_query("SELECT DISTINCT tests.id AS test_id FROM users "
            . "INNER JOIN studentsinbases ON users.id = studentsinbases.student_id "
            . "INNER JOIN tests ON tests.studentsbase_id = studentsinbases.studentbase_id "
            . "WHERE users.id = $userId AND tests.portal_test_id = $testId ");
    $testIdRow = mysql_fetch_array($testIdQuery);
    $testId = $testIdRow['test_id'];

    $submitGradeQuery = mysql_query("INSERT INTO grades VALUES ('', '$testId', '$userId', '$roundedGrade')");
    
    
    header('Location: indexStudentsTests.php');
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
        <script type="text/javascript" src="../javaScript/profileLB.js"></script>
        <title></title>
    <body>
        <div class="all">
<?php include '../templates/header.php'; ?>
            <?php include '../templates/studentMenu.php'; ?> 


            <div class = "content">

                <div class = "question-bases"> 
                    <div class = "group-bar">
                        <span>Test:</span>
                        <div class="align-right">
                            <a href="indexStudentsTests.php"><button>Return</button></a>
                        </div>
                    </div>
                    <div class ="dropdown-container">
                        <form action="answerTest.php?testId=<?php echo $testId; ?>" method="POST">
                            <ul class="list-of-qbases">
<?php
$counter = 1;
while ($nextRow = mysql_fetch_assoc($currentTestQuery)) {
    ?>
                                    <div class="dropdown-element">
                                        <li class="album-bar">
                                            <span><?php echo $counter . ". " . $nextRow['question_content']; ?></span>
                                        </li>

                                        <ul>
    <?php
    $counter++;
    $questionId = $nextRow['question_id'];
    $currentPortalTestQuestionId = $nextRow['portal_test_question_id'];
    $answersQuery = mysql_query("SELECT DISTINCT portal_test_occ_answer.id AS id, "
            . "answers.acontent AS acontent FROM answers "
            . "INNER JOIN portal_test_occ_answer ON answers.id = portal_test_occ_answer.answer_id "
            . "WHERE portal_test_occ_answer.portal_test_question_id = $currentPortalTestQuestionId "
            . "ORDER BY portal_test_occ_answer.id");
    while ($nextAnswer = mysql_fetch_assoc($answersQuery)) {
        ?>
                                                <li class="answer-bar">
                                                    <label><input type="checkbox" name="answer<?php echo $nextAnswer['id']; ?>" value="<?php echo $nextAnswer['id']; ?>">
        <?php echo $nextAnswer['acontent']; ?>
                                                    </label>
                                                </li>

    <?php } ?>
                                        </ul>
                                    </div>
<?php } ?>
                            </ul>
                            <input type="submit" name="submitTest" value="Submit Test" style="float: right;"/>
                        </form>
                    </div>
                </div>
            </div>
        </div>

    </body>
</html>
