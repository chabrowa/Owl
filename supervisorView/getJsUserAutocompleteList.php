<script type="text/javascript">
    var userList = [];
<?php
    $query = mysql_query("SELECT * FROM users");
?>
    $("#e1").select2({
        placeholder: "- Wybierz użytkownika -",
        minimumInputLength: 3,
        data: [
            <?php
            while ($nextPerson = mysql_fetch_assoc($query)) {
                echo '{id:'. $nextPerson['id']. ', text: \'' .$nextPerson['firstname'].' '.$nextPerson['lastname'].' '.$nextPerson['mail'].'\'},';
            }
            ?>
        ]
    });
</script>
