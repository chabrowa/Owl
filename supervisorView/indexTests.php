<?php
include '../templates/sessionstart.php';
@ $userId = $user['id'];
$portalTestsQuery = mysql_query("SELECT * FROM portaltests");
$testsArray = Array();
while (($row = mysql_fetch_assoc($portalTestsQuery))) {
    $testsArray[] = $row;
}

foreach ($testsArray as &$nextPortalTest) {
    if ($nextPortalTest["PSC"] === "P") {
        if (time() > strtotime($nextPortalTest["finish_date"])) {
            $nextPortalTest["PSC"] = "C";
            $portalTestId = $nextPortalTest['id'];
            $query = mysql_query("UPDATE portaltests SET PSC = 'C' WHERE id = $portalTestId");
        }
    }
}
reset($testsArray);

@ $suspend = $_POST['suspend'];
if($suspend){
    $chosenTest = $_POST['testId'];
    $suspendTestQuery = mysql_query("UPDATE portaltests SET PSC='S', start_date= NULL, finish_date = NULL WHERE id=$chosenTest");
    $DeleteTestsGrades = mysql_query("DELETE FROM tests WHERE portal_test_id = $chosenTest ");
    header('Location: indexTests.php');
}

@ $delete = $_POST['delete'];
if($delete){
    $chosenTest = $_POST['testId'];
    $suspendTestQuery = mysql_query("DELETE FROM portaltests WHERE id=$chosenTest");
    
    header('Location: indexTests.php');
}

@ $deletePDF = $_POST['deletePDF'];
if($deletePDF){
    $chosenTest = $_POST['testId'];
    $suspendTestQuery = mysql_query("DELETE FROM pdftests WHERE id=$chosenTest");
    
    header('Location: indexTests.php');
}

@ $submitChangeToPlaned = $_POST['submitChangeToPlaned'];
if($submitChangeToPlaned){
    $startDate = $_POST['startTestDate'];
    $finishDate = $_POST['finishTestDate'];
    $chosenTestId = $_POST['chosenTestId'];

    
    $updateTestQuery = mysql_query("UPDATE portaltests SET start_date = '$startDate', finish_date = '$finishDate', PSC = 'P' WHERE id = $chosenTestId");

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
        <link rel="stylesheet" type="text/css" href="../datetimepicker/jquery.datetimepicker.css">
        <script type="text/javascript" src="../datetimepicker/jquery.datetimepicker.js"></script> 
        <title></title>
    <body>
        <div class="all">
            <?php include '../templates/header.php'; ?>
            <?php include '../templates/menu.php'; ?> 


            <div class = "content">
                <div class = "question-bases"> 
                    <div class = "group-bar">
                        <span>Planed tests:</span>
                        <div class="align-right">
                            <input href="#addingNewPortalTest" type="button" class='addNewPortalTestBtn' value="Add new"/>
                        </div>
                    </div>
                    <ul class="list-of-qbases">
                        <?php
                        foreach ($testsArray as &$nextPortalTest) {
                            if ($nextPortalTest["PSC"] === "P") {
                                ?>
                                <li class="album-bar">
                                    <span><?php echo $nextPortalTest['title'] ?></span>
                                    <div class="align-right">
                                       
                                        <form action="indexTests.php" method="POST" style="display: inline;">
                                            <input type="hidden" name="testId" value="<?php echo $nextPortalTest['id']?>" />
                                            <input type="submit" name="suspend" value="Suspend"/>
                                        </form></span>
                                    </div>
                                </li>
                                <?php
                            }
                        }
                        reset($testsArray);
                        ?>
                    </ul>


                    <div class = "group-bar">
                        <span>Suspended tests:</span>
                    </div>
                    <ul class="list-of-qbases">
                        <?php
                        foreach ($testsArray as &$nextPortalTest) {
                            if ($nextPortalTest["PSC"] === "S") {
                                ?>
                                <li class="album-bar">
                                    <span><?php echo $nextPortalTest['title'] ?></span>
                                    <div class="align-right">
                                        <input href="#planedTest" type="button" class='moveToPlanedBnt' value="planed" data-test-id="<?php echo $nextPortalTest['id'] ?>"/>
                                        <form action="indexTests.php" method="POST" style="display: inline;">
                                            <input type="hidden" name="testId" value="<?php echo $nextPortalTest['id']?>" />
                                            <input type="submit" name="delete" value="Delete"/>
                                        </form>
                                       
                                    </div>
                                </li>
                                <?php
                            }
                        }
                        reset($testsArray);
                        ?>
                    </ul>

                    <div class = "group-bar">
                        <span>Completed tests:</span>
                    </div>
                    <ul class="list-of-qbases">
                        <?php
                        foreach ($testsArray as &$nextPortalTest) {
                            if ($nextPortalTest["PSC"] === "C") {
                                ?>
                                <li class="album-bar">
                                    <span><?php echo $nextPortalTest['title'] ?></span>
                                    <div class="align-right">
                                         <input href="#planedTest" type="button" class='addNewPortalTestBtn' value="planed" data-test-id="<?php echo $nextPortalTest['id'] ?>"/>
                                        <form action="indexTests.php" method="POST" style="display: inline;">
                                            <input type="hidden" name="testId" value="<?php echo $nextPortalTest['id']?>" />
                                            <input type="submit" name="delete" value="Delete"/>
                                        </form>
                                        
                                    </div>
                                </li>
                                <?php
                            }
                        }
                        ?>
                    </ul>

                    <div class = "group-bar">
                        <span>PDF tests:</span>
                        <div class="align-right">
                            <input href="#addingNewPdfTest" type="button" class='addNewPdfTestBtn' value="Add new"/>
                        </div>
                    </div>
                    <ul class="list-of-qbases">
                        <?php
                        $pdfTestsQuery = mysql_query("SELECT pdftests.name AS pdfTestName, pdf_test_occ.version_nr AS pdftestVersion, pdf_test_occ.pdftest_id AS pdftest_id "
                                . "FROM pdftests INNER JOIN qbases ON qbases.id = pdftests.qbase_id "
                                . "INNER JOIN subjects ON subjects.id = qbases.subject_id "
                                . "INNER JOIN pdf_test_occ ON pdftests.id = pdf_test_occ.pdftest_id "
                                . "WHERE subjects.users_id = '$userId' ");
                        while ($nextPdfTest = mysql_fetch_assoc($pdfTestsQuery)) {
                            ?>
                            <li class="album-bar">
                                <span><?php echo $nextPdfTest['pdfTestName']; ?>:<?php echo $nextPdfTest['pdftestVersion']; ?></span>
                                <div class="align-right">
                                    <a href="PdfGeneration.php?version=<?php echo $nextPdfTest['pdftestVersion']; ?>&pdftest=<?php echo $nextPdfTest['pdftest_id']; ?>"><button>Generate</button></a>
                                    <form action="indexTests.php" method="POST" style="display: inline;">
                                            <input type="hidden" name="testId" value="<?php echo $nextPdfTest['pdftest_id']?>" />
                                            <input type="submit" name="deletePDF" value="Delete"/>
                                    </form>
                                </div>
                            </li>
                        <?php } ?>
                    </ul>

                </div>
            </div>
        </div>
        <?php include '../lightBoxes/TestsLBTemp.php'; ?> 

    </body>
</html>
