<div id="addingNewStudentBase" class="emi-modal">
    <div class="emi-modal-title">New student base 
        <div class="closeButton align-right" style="cursor: pointer; position: absolute; top: 8px; right:10px;">X</div>
    </div>
    <div class="emi-modal-content" style="max-height: 250px; overflow-y: auto;">
        <form name="addStudentBase" action="indexStudents.php" method="POST">
            <input type='hidden' name='choosenSubjectId'/>

            Title for the group
            <input type="text" name="student-base-title"/><br>
            <input class="submit-on-right submitStudentBase" type="submit" name="add-new-student-base" value="submit"/>
        </form>
    </div>
</div>

<div id="editingStudentBase" class="emi-modal">
    <div class="emi-modal-title">Edit student base 
        <div class="closeButton align-right" style="cursor: pointer; position: absolute; top: 8px; right:10px;">X</div>
    </div>
    <div class="emi-modal-content" style="max-height: 250px; overflow-y: auto;">
        <form name="editStudentBase" action="indexStudents.php" method="POST">
            <input type='hidden' name='choosenGroupId'/>

            Update title for the group:
            <input type="text" name="student-base-title"/><br>
            <input class="submit-on-right submitStudentBase" type="submit" name="edit-student-base" value="submit"/>
        </form>
    </div>
</div>

<div id="addingGradesColumnModal" class="emi-modal">
    <div class="emi-modal-title">Add new test
        <div class="closeButton align-right" style="cursor: pointer; position: absolute; top: 8px; right:10px;">X</div>
    </div>
    <div class="emi-modal-content" style="max-height: 250px; overflow-y: auto;">
        <form name="editStudentBase" action="showGrades.php?nextStudentsBase=<?php echo $studentsBaseId; ?>" method="POST">
            <input type='hidden' name='choosenGroupId'/>

            Test title:
            <input type="text" name="test-title"/><br>
            <ul>
                <div class='studentListTable'>
                    <div class="studentListText">No students assigned to this group</div>
                </div>
                <?php
                $query = mysql_query("SELECT users.* FROM studentsinbases INNER JOIN users ON users.id = studentsinbases.student_id WHERE studentsinbases.studentbase_id = '$studentsBaseId'");
                $rowNumber = mysql_num_rows($query);
                $counter = 1;
                while ($nextStudent = mysql_fetch_assoc($query)) {
                    ?>
                    <div class="dropdown-element">
                        <li class="album-bar">
                            <span><?php echo $nextStudent['firstname']; ?></span>
                            <span><?php echo $nextStudent['lastname']; ?></span>
                            <span><input type="text" name="studentGrade<?php echo $counter;?>" /></span>
                        </li>
                    </div>
                <?php 
                $counter++;
                } ?>
            </ul>
            <input class="submit-on-right submitStudentBase" type="submit" name="add-test" value="submit"/>
        </form>
    </div>
</div>

<div id="editingGradesColumnModal" class="emi-modal">
    <div class="emi-modal-title">Edit test
        <div class="closeButton align-right" style="cursor: pointer; position: absolute; top: 8px; right:10px;">X</div>
    </div>
    <div class="emi-modal-content" style="max-height: 250px; overflow-y: auto;">
        <form name="editStudentBase" action="showGrades.php?nextStudentsBase=<?php echo $studentsBaseId; ?>" method="POST">
            <input type='hidden' name='choosenTestId'/>

  
            Test title: <input type="text" name="choosenTestName" >
            <ul>
                <div class='studentListTable'>
                    <div class="studentListText">No students assigned to this group</div>
                </div>
                <?php
                $counter = 1;
                foreach ($studentsGradesArray as $studentId => $student) {
                    ?>
                    <div class="dropdown-element">
                        <li class="album-bar">
                            <span><?php echo $student['firstname']; ?></span>
                            <span><?php echo $student['lastname']; ?></span>
                            <span><input type="text" name="studentGrade<?php echo $counter;?>" /></span>
                        </li>
                    </div>
                <?php 
                 $counter++;
                } ?>
            </ul>
            <input class="submit-on-right submitStudentBase" type="submit" name="edit-test" value="submit"/>
        </form>
    </div>
</div>
