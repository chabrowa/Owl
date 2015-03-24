<script type="text/javascript">
    var studentsGroupList = [];
<?php
    $query = mysql_query("SELECT studentsbases.id AS studentsBase_id, studentsbases.name AS studentsBase_name FROM studentsbases "
            . " INNER JOIN subjects ON subjects.id = studentsbases.subject_id "
            . " INNER JOIN qbases ON subjects.id = qbases.subject_id WHERE qbases.id = $qbaseId ");
?>
    $("#e2").select2({
        placeholder: "-Select students group-",
        minimumInputLength: 1,
        data: [
            <?php
            while ($nextSbase = mysql_fetch_assoc($query)) {
                echo '{id:"'.$nextSbase['studentsBase_name'].'#'.$nextSbase['studentsBase_id'].'", text: \'' .$nextSbase['studentsBase_name'].'\'},';
            }
            ?>
                        
        ]
    });
</script>
