<div id="addingNewPdfTest" class="emi-modal">
    <div class="emi-modal-title">New PDF test 
        <div class="closeButton align-right" style="cursor: pointer; position: absolute; top: 8px; right:10px;">X</div>
    </div>
    <div class="emi-modal-content" style="max-height: 250px; overflow-y: auto;">

        <p>Choose question base to prepare a test:</p>
        <?php
        $query = mysql_query("SELECT DISTINCT qbases.name AS qbase_name, qbases.id AS qbase_id FROM qbases INNER JOIN subjects ON "
                . "qbases.subject_id = subjects.id INNER JOIN  users  ON users.id = subjects.users_id "
                . "WHERE users.id = $userId ");

        while ($nextQBase = mysql_fetch_assoc($query)) {
            ?>

            <a href="createTest.php?qbaseId=<?php echo $nextQBase['qbase_id'] ?>"> <button style="width: 250px;"> <?php echo $nextQBase['qbase_name'] ?></button> </a><br>
        <?php } ?>
    </div>
</div>

<div id="addingNewPortalTest" class="emi-modal">
    <div class="emi-modal-title">New Portal test 
        <div class="closeButton align-right" style="cursor: pointer; position: absolute; top: 8px; right:10px;">X</div>
    </div>
    <div class="emi-modal-content" style="max-height: 250px; overflow-y: auto;">

        <p>Choose question base to prepare a test:</p>
        <?php
        $query = mysql_query("SELECT DISTINCT qbases.name AS qbase_name, qbases.id AS qbase_id FROM qbases INNER JOIN subjects ON "
                . "qbases.subject_id = subjects.id INNER JOIN  users  ON users.id = subjects.users_id "
                . "WHERE users.id = $userId ");

        while ($nextQBase = mysql_fetch_assoc($query)) {
            ?>

            <a href="createPortalTest.php?qbaseId=<?php echo $nextQBase['qbase_id'] ?>"> <button style="width: 250px;"> <?php echo $nextQBase['qbase_name'] ?></button> </a><br>
        <?php } ?>
    </div>
</div>

<div id="planedTest" class="emi-modal">
    <div class="emi-modal-title">Move to planed test 
        <div class="closeButton align-right" style="cursor: pointer; position: absolute; top: 8px; right:10px;">X</div>
    </div>
    <div class="emi-modal-content" style="max-height: 250px; overflow-y: auto;">
        <form action="indexTests.php" method="POST">
            <input type="hidden" name="chosenTestId">
            <div class="dates">
                <span>Start time:  <input id="moveToPlanedstartdatetimepicker" type="text" name="startTestDate" style="margin-left: 10px;" ></span><br/>
                <span>Finish time: <input id="moveToPlanedfinishdatetimepicker" type="text" name="finishTestDate" ></span>
            </div>
            <input type="submit" name="submitChangeToPlaned" value="submit" >
        </form>
    </div>
</div>