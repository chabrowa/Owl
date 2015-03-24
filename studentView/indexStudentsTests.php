<?php
include '../templates/sessionstart.php';
@ $userId = $user['id'];
@ $testsList = mysql_query("SELECT portaltests.title AS testTitle, portaltests.id AS testId, "
        . "CASE WHEN portaltests.start_date > NOW() THEN 1 ELSE 0 END AS testsStarted, "
        . "CASE WHEN portaltests.finish_date > NOW() THEN 1 ELSE 0 END AS testsEnded "
        . "FROM portaltests "
        . "INNER JOIN portaltest_students_bases_related ON portaltest_students_bases_related.PORTALtest_id = portaltests.id "
        . "INNER JOIN studentsbases ON studentsbases.id = portaltest_students_bases_related.students_base_id "
        . "INNER JOIN studentsinbases ON studentsinbases.studentbase_id = studentsbases.id "
        . "WHERE studentsinbases.student_id =$userId ");

while (($row = mysql_fetch_assoc($testsList))) {
    $testsArray[] = $row;
}

@ $refresh= $_POST['refresh'];
if($refresh){
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
                        <span>Scheduled tests:</span>

                        <div class="align-right">
                            <form action="indexStudentsTests.php" method="POST">
                                <input type="image" alt="refresh" src='../images/refresh.png' name="refresh"  style='width: 17px; height: 17px; margin-right: 10px;' />
                            </form>
                        </div>
                    </div>
                    <ul class="list-of-qbases">
                        <?php
                        foreach ($testsArray as &$nextTest) {
                            if ($nextTest['testsStarted'] == 0 && $nextTest['testsEnded'] == 1) {
                                ?>
                                <li class="album-bar">
                                    <span><?php echo $nextTest['testTitle']; ?></span>
                                    <div class="align-right">
                                        <a href="answerTest.php?testId=<?php echo $nextTest['testId']; ?>"><button>Start</button></a>
                                    </div>
                                </li>
                                <?php
                            } else if ($nextTest['testsStarted'] == 1 && $nextTest['testsEnded'] == 1) {
                                $zmienna = 'disabled';
                                ?>
                                <li class="album-bar">
                                    <span><?php echo $nextTest['testTitle']; ?></span>
                                    <div class="align-right">
                                        <button <?php echo $zmienna; ?> >Start</button>
                                    </div>
                                </li>
                            <?php } ?>
                        <?php } reset($testsArray) ?>
                    </ul>


                    <div class = "group-bar">
                        <span>Completed tests:</span>
                    </div>
                    <ul class="list-of-qbases">
                        <?php
                        foreach ($testsArray as &$nextTest) {
                            if ($nextTest['testsStarted'] == 0 && $nextTest['testsEnded'] == 0) {
                                $zmienna = 'disabled ';
                                ?>
                                <li class="album-bar">
                                    <span><?php echo $nextTest['testTitle']; ?></span>
                                </li>
                            <?php } ?>
                        <?php } ?>
                    </ul>
                </div>
            </div>
        </div>

    </body>
</html>
