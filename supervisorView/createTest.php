<?php
include '../templates/sessionstart.php';
@ $userId = $user['id'];
@ $qbaseId = $_GET['qbaseId'];
@ $subjectQuery = mysql_query("SELECT DISTINCT qbases.name AS qbase_name, subjects.name AS subject_name FROM qbases INNER JOIN subjects ON "
        . "qbases.subject_id = subjects.id WHERE qbases.id = '$qbaseId' ");
@ $subjectQBaseData = mysql_fetch_array($subjectQuery);
@ $qbasePointsQuery = mysql_query("SELECT DISTINCT qpoints.points AS qpoint FROM qbases INNER JOIN qpoints ON qbases.id = qpoints.qbase_id WHERE qbases.id = '$qbaseId' ");

@ $submitTest = $_POST['sumbitTest'];

if ($submitTest) {
    $testName = $_POST['testName'];
    $nrOfVersions = $_POST['nrOfVersions'];
    @ $mixedQuestions = $_POST['mixing'];
    if ($mixedQuestions != 1) {
        $mixedQuestions = 0;
    }

    $pointsString = "";
    $qpts = Array();
    while ($pointsValue = mysql_fetch_array($qbasePointsQuery)) {
        $questionsForPoints = $_POST["pointsValue" . $pointsValue['qpoint']];
        if ($questionsForPoints != 0 && $questionsForPoints != NULL) {
            $qpts[$pointsValue['qpoint']] = $questionsForPoints;
            $pointsString .= $pointsValue['qpoint'] . ', ';
        }
    }
    if ($pointsString != 0 && $pointsString != NULL) {
        $pointsString = substr($pointsString, 0, -2);
    }

    $questionList = mysql_query("SELECT answers.id AS answer_id, questions.id AS question_id, questions.points AS points "
            . "FROM answers INNER JOIN questions ON answers.question_id = questions.id "
            . "WHERE questions.qbase_id = '$qbaseId' AND questions.points IN ($pointsString) ");

    $availablePointsAndQuestions = Array();
    while ($row = mysql_fetch_assoc($questionList)) {
        if (!array_key_exists($row["points"], $availablePointsAndQuestions)) {
            $point = Array(
                "pointValue" => $row["points"],
                "questions" => Array()
            );
            $availablePointsAndQuestions[$row["points"]] = &$point;
        } else {
            $point = &$availablePointsAndQuestions[$row["points"]];
        }

        unset($questionArray);
        $questionArray = null;
        $questionIndex = null;
        for ($i = 0; $i < count($point["questions"]); $i++) {
            if ($point["questions"][$i]["questionId"] === $row["question_id"]) {
                $questionArray = &$point["questions"][$i];
                $questionIndex = $i;
                break;
            }
        }

        if ($questionArray === null) {
            $questionArray = Array(
                "questionId" => $row["question_id"],
                "answers" => Array()
            );
        }
        $answerArray = &$questionArray["answers"];
        array_push($answerArray, $row["answer_id"]);
        if ($questionIndex === null) {
            array_push($point["questions"], $questionArray);
        }
    }

    function array_copy($arr) {
        $newArray = array();
        foreach ($arr as $key => $value) {
            if (is_array($value))
                $newArray[$key] = array_copy($value);
            elseif (is_object($value))
                $newArray[$key] = clone $value;
            else
                $newArray[$key] = $value;
        }
        return $newArray;
    }

    $allVersionTestsArray = Array();
    for ($i = 0; $i < $nrOfVersions; $i++) {
        $testArray = Array();
        $availablePointsAndQuestionsCopy = array_copy($availablePointsAndQuestions);

        foreach ($qpts as $points => $questionCount) {
            if ($questionCount > 0) {
                $testArray[$points] = Array(
                    "pointsValue" => $points,
                    "questions" => Array()
                );

                $availablePointData = &$availablePointsAndQuestionsCopy[$points];
                $availableQuestions = &$availablePointData["questions"];
                if ($availablePointData != null) {
                    for ($j = 0; ($j < $questionCount) && ($availableQuestionsCount = count($availableQuestions)); $j++) {
                        $questionIndex = rand(1, $availableQuestionsCount) - 1;
                        $testArray[$points]["questions"][$j] = Array(
                            "questionId" => $availableQuestions[$questionIndex]["questionId"],
                            "answers" => Array()
                        );
                        $availableAnswers = &$availableQuestions[$questionIndex]["answers"];

                        while ($availableAnswersCount = count($availableAnswers)) {
                            $answerIndex = rand(1, $availableAnswersCount) - 1;
                            array_push($testArray[$points]["questions"][$j]["answers"], $availableAnswers[$answerIndex]);
                            array_splice($availableAnswers, $answerIndex, 1);
                        }
                        array_splice($availableQuestions, $questionIndex, 1);
                    }
                }
            }
        }
        array_push($allVersionTestsArray, $testArray);
        unset($availablePointsAndQuestionsCopy);
    }


    // adding to database tak powinno zadzialac
    $currentDate = date("Y.m.d");
    $addingTestQuery = mysql_query("INSERT INTO pdftests VALUES('', '$qbaseId', '$testName', '$currentDate' ) ");
    $testId = mysql_insert_id();

    foreach ($qpts as $points => $questionCount) {
        $addingNrOfQuestionsQuery = mysql_query("INSERT INTO pdf_nr_of_questions_for_given_points VALUES ('', '$testId', '$points', '$questionCount') ");
    }

    for ($i = 0; $i < $nrOfVersions; $i++) {

        $currentTest = &$allVersionTestsArray[$i];
        $versionSpecificationQuery = mysql_query("INSERT INTO pdf_test_occ VALUES ('', '$testId', '$i') ");
        $versionId = mysql_insert_id();

        if ($mixedQuestions) {

            while (count($currentTest)) {
                $randomPoints = array_rand($currentTest, 1);
                $questionsForSelectedPoints = &$currentTest[$randomPoints]['questions'];
                if (count($questionsForSelectedPoints)) {
                    $question = &$questionsForSelectedPoints[0];
                    $selectedQuestionId = $question["questionId"];
                    $questionInsertQuery = mysql_query("INSERT INTO pdf_test_occ_question VALUES ('', '$versionId', '$selectedQuestionId' )");
                    $insertQuestionId = mysql_insert_id();
                    $answersNumber = count($question["answers"]);
                    for ($k = 0; $k < $answersNumber; $k++) {
                        $currentAnswerId = $question["answers"][$k];
                        $answerInsertQuery = mysql_query("INSERT INTO pdf_test_occ_answer VALUES ( '', '$insertQuestionId', '$currentAnswerId' )");
                    }
                    array_splice($questionsForSelectedPoints, 0, 1);
                }

                if (!count($questionsForSelectedPoints)) {
                    unset($currentTest[$randomPoints]);
                }
            }
        } else {
            foreach ($qpts as $points => $questionCount) {
                $questionsNumber = count($currentTest[$points]['questions']);

                for ($j = 0; $j < $questionsNumber; $j++) {
                    $currentQuestionId = $currentTest[$points]['questions'][$j]["questionId"];
                    $questionInsertQuery = mysql_query("INSERT INTO pdf_test_occ_question VALUES ('', '$versionId', '$currentQuestionId' )");
                    $insertQuestionId = mysql_insert_id();

                    $answersNumber = count($currentTest[$points]['questions'][$j]["answers"]);
                    for ($k = 0; $k < $answersNumber; $k++) {
                        $currentAnswerId = $currentTest[$points]['questions'][$j]["answers"][$k];
                        $answerInsertQuery = mysql_query("INSERT INTO pdf_test_occ_answer VALUES ( '', '$insertQuestionId', '$currentAnswerId' )");
                    }
                }
            }
        }
    }

    header('Location: indexTests.php');
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
        <script type="text/javascript" src="../javaScript/testBase.js"></script>
        <script type="text/javascript" src="../select2/select2.min.js"></script>
        <link rel="stylesheet" type="text/css" href="../select2/select2.css">
        <link rel="stylesheet" type="text/css" href="../style/testsAdding.css">
        <title></title>
    <body>
        <div class="all">
            <?php include '../templates/header.php'; ?>
            <?php include '../templates/menu.php'; ?> 


            <div class = "content">
                <div class = "question-bases"> 
                    <div class = "group-bar bottomRound">
                        <span>Test creation </span>
                        <div class="align-right">
                            <a href="indexTests.php"><button>Return</button></a>
                        </div>
                    </div>

                    <div class="testCreationContent">
                        <div class="testCreationTopContent">
                            <span>Subject: <?php echo $subjectQBaseData['subject_name'] ?> from: <?php echo $subjectQBaseData['qbase_name'] ?> question base</span>
                        </div>

                        <div class="testCreationFormContent pdfversion">
                            <form name="testVariablesDefinition" action="createTest.php?qbaseId=<?php echo $qbaseId; ?>" method="POST">

                                <div>
                                    <span>Name for a test: </span>
                                    <span><input type="text" name="testName"/></span>
                                </div>

                                <div style="margin-top: 15px; margin-bottom: 15px;">
                                    <span>Define number of questions</span>
                                    <div style="margin-left: 10px;">
                                        <?php while ($pointsValue = mysql_fetch_array($qbasePointsQuery)) { ?>
                                            <div>
                                                <span><?php echo $pointsValue['qpoint']; ?> points</span>
                                                <span><input type="text" name="pointsValue<?php echo $pointsValue['qpoint']; ?>"/> questions</span>
                                            </div>
                                        <?php } ?>
                                    </div>
                                </div>

                                <div style="margin-bottom: 10px;">
                                    <span>Number of versions of test </span>
                                    <span><input type="text" name="nrOfVersions"/></span>
                                </div>
                                <div style="margin-bottom: 10px;">
                                    Define the order of questions in test: <br/>
                                    <div style="margin-left: 10px;">
                                        <input type="radio" name="mixing" value="1" /> Questions for various points should be mixed<br/>
                                        <input type="radio" name="mixing" value="0" /> Questions should be sorted by points value<br/>
                                    </div>
                                </div>
                                <div style="padding-bottom: 10px; margin-left: 350px;">
                                    <input type="submit" name="sumbitTest" value="Submit" />
                                </div>
                            </form>

                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php include '../lightBoxes/TestsLBTemp.php'; ?> 

    </body>
</html>
