<?php
include '../templates/sessionstart.php';

@ $subjectId = $_GET["subjectId"];
@ $qbaseId = $_GET["qbaseId"];
$subjectQuery = mysql_query("SELECT * FROM subjects WHERE id='$subjectId'");
if (!($subjectQuery)) {
    die();
}
$qbaseQuery = mysql_query("SELECT * FROM qbases WHERE id='$qbaseId'");
if (!($qbaseQuery)) {
    die();
}
$subjectChosen = mysql_fetch_array($subjectQuery);
$qbaseChosen = mysql_fetch_array($qbaseQuery);


@ $addNewQuestion = $_POST['add-new-question'];
if ($addNewQuestion) {

    $chosenQbaseId = $_POST['chosenQbaseId'];
    $points = $_POST['points'];
    $questionContent = $_POST['questionContent'];
    $wrongAnswerCost = $_POST['wrongAnswerCost'];

    if (strlen($questionContent) != 0) {

        $query = mysql_query("INSERT INTO questions VALUES ('', '$chosenQbaseId', '$questionContent', '$points', '$wrongAnswerCost', '0')");
        $questionId = mysql_insert_id();
        if ($questionId != FALSE) {
            for ($i = 0; isset($_POST["answers" . $i]); $i++) {
                $answer = $_POST["answers" . $i];

                $checkBox = isset($_POST["correctAnswerCheck" . $i]) && $_POST["correctAnswerCheck" . $i] ? "1" : "0";
                $addAnswersQuerry = mysql_query("INSERT INTO answers VALUES ('', '$questionId', '$answer', '$checkBox')");
            }
        }
    }
}

@ $deleteQuestion = $_POST['deleteQuestion'];
if($deleteQuestion){
    $questionId = $_POST['questionId'];
    $deleteQuestionQuery = mysql_query("DELETE FROM questions WHERE id=$questionId");
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
            <?php include '../templates/menu.php'; ?> 


            <div class = "content">
                <div class = "question-bases"> 
                    <div class = "group-bar">
                        <div>
                            <span><?php echo $subjectChosen['name']; ?>:</span> 
                            <span>  <?php echo $qbaseChosen['name']; ?></span>
                            <span class="align-right">
                                <input href="#addingNewQuestion" data-qbase-id='<?php echo $qbaseChosen['id'] ?>' 
                                       data-subject-id='<?php echo $subjectChosen['id']; ?>' type="button" class='addNewQuestionBtn showQuestionsDirection' value="Add question" id="checkDirection"/>
                                <a href="indexQuestionBase.php"><button>Return</button></a>
                            </span>
                        </div>
                    </div>
                    <!-- for po wszystkich bazach dla danego subjecta -->
                    <div class ="dropdown-container">

                        <ul class="list-of-qbases">
                            <?php
                            $questionQuery = mysql_query("SELECT * FROM questions WHERE qbase_id = '$qbaseId'");
                            while ($nextQuestion = mysql_fetch_assoc($questionQuery)) {
                                ?>
                                <div class="dropdown-element">
                                    <li class="album-bar">
                                        <span class="dropdown-trigger"><?php echo $nextQuestion['qcontent'] ?></span>
                                        <div class="align-right">
                                            <form style="display: inline;" action="showQuestions.php?subjectId=<?php echo $subjectId; ?>&qbaseId=<?php echo $qbaseId; ?>" method="POST">
                                                <input type="hidden" name="questionId" value="<?php echo $nextQuestion['id']; ?>"/>
                                                <input type="submit" name="deleteQuestion" value="Delete"/>
                                            </form>
                                        </div>
                                    </li>

                                    <ul class="dropdown-menu">
                                        <?php
                                        $currentAnswerId = $nextQuestion['id'];
                                        $answersQuery = mysql_query("SELECT * FROM answers WHERE question_id='$currentAnswerId'");
                                        while ($nextAnswer = mysql_fetch_assoc($answersQuery)) {
                                            ?>
                                            <li class="answer-bar">
                                                <span class="dropdown-trigger">
                                                    <?php if ($nextAnswer['correctness'] == 0) { ?>
                                                        <img src='../images/incorrect.png' style='width: 15px; height: 15px;' />
                                                    <?php } else { ?>
                                                        <img src='../images/correct.png' style='width: 15px; height: 15px;' />
                                                    <?php } ?>
                                                    <?php echo $nextAnswer['acontent'] ?>
                                                </span>
                                            </li>

                                        <?php } ?>
                                    </ul>
                                </div>
                            <?php } ?>
                        </ul>
                    </div>

                </div>
            </div>


        </div>
        <?php include '../lightBoxes/QuestionBaseLBTemp.php'; ?> 
    </body>
</html>
