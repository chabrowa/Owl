<?php
include '../templates/sessionstart.php';
@ $userId = $user['id'];
@ $deleteqpointId = $_GET['deleteqpointId'];
@ $qpointSubmit = $_POST['submitqpoint'];
@ $addNewQbase = $_POST['add-new-qbase'];
@ $deleteQbase = $_GET['deleteQBase'];
@ $qpointsQuery = mysql_query("SELECT * FROM qpoints");
@ $subjectsQuery = mysql_query("SELECT * FROM subjects WHERE users_id='$userId'");
@ $addNewQuestion = $_POST['add-new-question'];
@ $editQbase = $_POST['edit-qbase'];

if ($addNewQbase) {

    $choosenSubjectId = $_POST['choosenSubjectId'];
    $qbaseTitle = $_POST['qbase-title'];
    if (strlen($qbaseTitle) != 0) {
        // $query = mysql_query("INSERT INTO qbases VALUES('','$choosenSubjectId','$qbaseTitle')");
        $query = mysql_query("INSERT INTO `qwerty`.`qbases` (`id`, `subject_id`, `name`) VALUES ( NULL, '$choosenSubjectId', '$qbaseTitle')");
        $qbaseId = mysql_insert_id();

        if ($qbaseId != FALSE) {

            for ($i = 0; isset($_POST["points" . $i]); $i++) {
                
                $point = $_POST["points" . $i];

                $addPointsQuerry = mysql_query("INSERT INTO qpoints VALUES ('', '$qbaseId', '$point')");
            }

        }
    }
}


if ($deleteQbase) {
    $deleteQbaseId = $_GET['qbaseId'];

    $query = mysql_query("DELETE FROM qbases WHERE id = '$deleteQbaseId'");
}

if ($addNewQuestion) {

    $chosenQbaseId = $_POST['chosenQbaseId'];
    $points = $_POST['points'];
    $questionContent = $_POST['questionContent'];
    $wrongAnswerCost = $_POST['wrongAnswerCost'];

    if (strlen($questionContent) != 0) {

        $query = mysql_query("INSERT INTO questions VALUES ('', '$chosenQbaseId', '$questionContent', '$points', '$wrongAnswerCost', '0', '0')");
        $questionId = mysql_insert_id();
        $correctAnswerCount = 0;
        if ($questionId != FALSE) {
            for ($i = 0; isset($_POST["answers" . $i]); $i++) {
                $answer = $_POST["answers" . $i];

                $checkBox = isset($_POST["correctAnswerCheck" . $i]) && $_POST["correctAnswerCheck" . $i] ? "1" : "0";
                $addAnswersQuerry = mysql_query("INSERT INTO answers VALUES ('', '$questionId', '$answer', '$checkBox')");
                if($checkBox == 1){
                    $correctAnswerCount++;
                }
            }
            $updateQuery = mysql_query("UPDATE questions SET correct_answer_count = $correctAnswerCount WHERE id = $questionId	");
        }
    }
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
                    <?php
                    while ($nextSubject = mysql_fetch_assoc($subjectsQuery)) {
                        $nextSubjectId = $nextSubject['id'];
                        ?>
                        <div class = "group-bar">
                            <span><?php echo $nextSubject['name']; ?></span>
                            <div class="align-right">
                                <input href="#addingNewQuestionBase" data-subject-id='<?php echo $nextSubjectId ?>' 
                                       type="button" class='addNewQuestionBaseBtn' value="add" id="checkDirection"/>
                            </div>
                        </div>
                        <!-- for po wszystkich bazach dla danego subjecta -->
                        <ul class="list-of-qbases">
                            <?php
                            $qbasesQuery = mysql_query("SELECT * FROM qbases WHERE subject_id = '$nextSubjectId'");
                            while ($nextQbase = mysql_fetch_assoc($qbasesQuery)) {
                                ?>
                                <li class="album-bar">
                                    <span><?php echo $nextQbase['name'] ?></span>
                                    <div class="align-right">
                                        <input href="#addingNewQuestion" data-qbase-id='<?php echo $nextQbase['id'] ?>' 
                                               type="button" class='addNewQuestionBtn' value="Add question" />
                                        <a href="showQuestions.php?subjectId=<?php echo $nextSubjectId ?>&qbaseId=<?php echo $nextQbase['id']; ?>"><button>Show</button></a>
                                        <input href="#editQuestionBase_<?php echo $nextQbase['id'] ?>" data-qbase-id='<?php echo $nextQbase['id'] ?>'
                                               type="button" class='editQuestionBaseBtn' value="Edit" />

                                        <a href="indexQuestionBase.php?deleteQBase=true&qbaseId=<?php echo $nextQbase['id']; ?>"><button>Remove</button></a>
                                    </div>
                                    <div>
                                        <div id="editQuestionBase_<?php echo $nextQbase['id']; ?>" class="emi-modal">
                                            <div class="emi-modal-title">Edit question base
                                                <div class="closeButton align-right" style="cursor: pointer; position: absolute; top: 8px; right:10px;">X</div>
                                            </div>
                                            <div class="emi-modal-content" style="max-height: 250px; overflow-y: auto;">
                                                <form name="updateQuestionBase" action="indexQuestionBase.php" method="POST">
                                                    <input type='hidden' name='chosenQbaseId' value="<?php echo $nextQbase['id']; ?>"/>
                                                    <table id="pointsTable_<?php echo $nextQbase['id']; ?>">
                                                        <tbody>
                                                            <tr>
                                                                <td>Update title for the base</td>
                                                                <td colspan="2"><input type="text" name="qbase-title" value="<?php echo $nextQbase['name'] ?>"/><br>
                                                                    <div class="error" data-assosiated-field="qbase-title"></div></td>
                                                            </tr>
                                                            <tr>
                                                                <td colspan="3">
                                                                    <div class="possiblePointsText">No possible points for this base</div>
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                        <?php
                                                        $qbase_id = $nextQbase['id'];
                                                        $addedPointsQuerry = mysql_query("SELECT * FROM qpoints WHERE qbase_id = $qbase_id");
                                                        while ($nextQpoint = mysql_fetch_assoc($addedPointsQuerry)) {
                                                            $pointsValue = $nextQpoint['points'];
                                                            $questuionForPointsNrQuery = mysql_query(" SELECT COUNT(*) AS question_count FROM qbases INNER JOIN questions ON qbases.id = questions.qbase_id WHERE qbases.id = $qbase_id AND questions.points = $pointsValue ");
                                                            $questionsNumberQuery = mysql_fetch_array($questuionForPointsNrQuery);
                                                            $questionNumber = $questionsNumberQuery['question_count'];
                                                            if ($questionNumber > 0) {
                                                                $zmienna =  'disabled ';
                                                            } else {
                                                                $zmienna = "";
                                                            }
                                                            ?>
                                                            <tbody id="pointsTable_inputContainer_<?php echo $nextQbase['id']; ?>">
                                                                <tr style="display: table-row;" class="qbaseDiv">
                                                                    <td><input <?php echo $zmienna?> type="text" class="qbasePoints" value="<?php echo $nextQpoint['points']; ?>"/></td>
                                                                    <td>Points</td>
                                                                    <td><button <?php echo $zmienna?> class="pointRemoveBtn" type='button'>Remove</button></td>
                                                                </tr>
                                                            </tbody>
                                                        <?php } ?>
                                                        <tbody id="pointsTable_inputContainer_<?php echo $nextQbase['id']; ?>">
                                                            <tr style="display: none;" id="pointsTrTemplate_<?php echo $nextQbase['id']; ?>" class="qbaseDiv">
                                                                <td><input type="text" class="qbasePoints" /></td>
                                                                <td>Points</td>
                                                                <td><button class="pointRemoveBtn" type='button'>Remove</button></td>
                                                            </tr>
                                                        </tbody>
                                                        <tbody>
                                                            <tr>
                                                                <td><button id="addPoints_<?php echo $nextQbase['id']; ?>" data-id="<?php echo $nextQbase['id']; ?>">Add points</button></a></td>
                                                                <td colspan="2"><input class="submit-on-right submitQbase" type="submit" name="edit-qbase" value="submit"/></td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                            <?php } ?>
                        </ul>
                    <?php }
                    ?>


                </div>
            </div>
        </div>

        <?php include '../lightBoxes/QuestionBaseLBTemp.php'; ?> 
    </body>
</html>



