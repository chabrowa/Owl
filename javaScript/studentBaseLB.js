$(document).ready(function() {

    $(".addNewStudentBaseBtn").on("click", function() {
        $("#addingNewStudentBase input[name='choosenSubjectId']").val($(this).data("subject-id"));
    });
    $(".addNewStudentBaseBtn").leanModal();


    $(".editStudentBaseBtn").on("click", function() {
        $("#editingStudentBase input[name='choosenGroupId']").val($(this).data("group-id"));
    });
    $(".editStudentBaseBtn").leanModal();
});

$(function() {
    var studentCounter = $(".album-bar").length;
    if (studentCounter > 0) {
        $('.studentListTable').hide();
    }
});


$(function() {
    $(".addGradesColumnBtn").on("click", function() {
        $("#addingGradesColumnModal input[name='choosenGroupId']").val($(this).data("group-id"));
    });
    $(".addGradesColumnBtn").leanModal();

    $(".editGradesColumnBtn").on("click", function() {
        var testId = $(this).data("test-id");
        $("input[name^='studentGrade']").each(function() {
            var studentId = $(this).data("student-id");
            var studentGrade = $("td[data-student-id='" + studentId + "'][data-test-id='" + testId + "']").text();
            $(this).val(studentGrade);
        });
        
        $("#editingGradesColumnModal input[name='choosenTestId']").val($(this).data("test-id"));
        $("#editingGradesColumnModal input[name='choosenTestName']").val($(this).data("test-name"));
    });
    $(".editGradesColumnBtn").leanModal();
});