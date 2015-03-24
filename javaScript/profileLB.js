$(document).ready(function() {
    $("input[name='passwordChange']").leanModal();
    $(".addNewQuestionBaseBtn").on("click", function() {
        $("#addingNewQuestionBase input[name='choosenSubjectId']").val($(this).data("subject-id"));
    });
    $(".addNewQuestionBaseBtn").leanModal();


    $(".addNewQuestionBtn").on("click", function() {

        $("#addingNewQuestion input[name='chosenQbaseId']").val($(this).data("qbase-id"));

        if ($('#checkDirection').hasClass('showQuestionsDirection')) {
            var actionDirection = "showQuestions.php?subjectId=" + $(this).data("subject-id") +
                    "&qbaseId=" + $(this).data("qbase-id");
        }
        else {
            actionDirection = "indexQuestionBase.php";
        }
        $("#addingNewQuestion").find(".addinQuestionForm").attr('action', actionDirection);
    });
    $(".addNewQuestionBtn").leanModal();

    $(".addNewQuestionBtn").click(function() {
        $(".addQuestionMain").css('visibility', 'hidden');
        $(".addQuestionLoadingGif").show();

        $.ajax({
            url: "getQBasePoints.php",
            type: "POST",
            data: {qBaseId: $(this).data("qbase-id")},
            success: function(data, textStatus, jqXHR)
            {
                data = JSON.parse(data);
                $pointsSelect = $(".pointsSelect");
                $pointsSelect.empty().append("<option value='' disabled selected>-- Wybierz liczbę punktów --</option>");
                for (var i = 0; i < data.length; i++) {
                    $pointsSelect.append("<option value='" + data[i] + "'>" + data[i] + "</option>");
                }

                $(".addQuestionMain").css('visibility', 'visible');
                $(".addQuestionMain").hide();
                $(".addQuestionLoadingGif").fadeOut(500);
                $(".addQuestionMain").fadeIn(500);
            },
            error: function(jqXHR, textStatus, errorThrown)
            {
                console.log(errorThrown);
            }
        });
    });


});

$(function() {
    
    $('[id^="addPoints_"]').click(function() {
        var qBaseId = $(this).data("id");
        var $newPointsTr = $("#pointsTrTemplate_" + qBaseId).clone().attr("id", null).show();
        $("#pointsTable_inputContainer_" + qBaseId).append($newPointsTr);
        var pointCounter = $("#pointsTable_"+ qBaseId + " .qbaseDiv").length - 1; // -1, bo jeszcze template
        if (pointCounter > 0) {
            $('.possiblePointsText').text("Possible points to get");
        }
        $newPointsTr.find('.pointRemoveBtn').click(function() {
            $(this).closest(".qbaseDiv").remove();
            if ($(this).closest("[id^='pointsTable_']").find(".qbaseDiv").length < 2) {
                $('.possiblePointsText').text("No possible points for this base");
            }
            return false;
        });
        $("#editQuestionBase_" + qBaseId).leanModal("center");
        return false;
    });
    
    $('#addPoints').click(function() {
        var $newPointsTr = $("#pointsTrTemplate").clone().attr("id", null).show();
        $("#pointsTable_inputContainer").append($newPointsTr);
        var pointCounter = $(".qbaseDiv").length - 1; // -1, bo jeszcze template
        if (pointCounter > 0) {
            $('.possiblePointsText').text("Possible points to get");
        }
        $newPointsTr.find('.pointRemoveBtn').click(function() {
            $(this).closest(".qbaseDiv").remove();
            if ($(".qbaseDiv").length < 2) {
                $('.possiblePointsText').text("No possible points for this base");
            }
            return false;
        });
        $("#addingNewQuestionBase").leanModal("center");
        return false;
    });
    $('#addAnswers').click(function() {
        var $newAnswersTr = $("#answersTrTemplate").clone().attr("id", null).show();
        $("#answersTable_inputContainer").append($newAnswersTr);
        var questionCounter = $(".answerDiv").length - 1; // -1, bo jeszcze template
        $newAnswersTr.find(".answersCounter").text(questionCounter++ + ".");
        if (questionCounter > 0) {
            $('#answersTable_answersText').text("Answers");
        }
        $newAnswersTr.find('.answerRemoveBtn').click(function() {
            $(this).closest(".answerDiv").remove();
            $(".answerDiv").each(function(index) {
                $(this).find(".answersCounter").text(index + ".");
            });
            if ($(".answerDiv").length < 2) {
                $('#answersTable_answersText').text("There are no answers");
            }
            return false;
        });
        $("#addingNewQuestion").leanModal("center");
        return false;
    });

    $('.submitQuestion').click(function() {
        $(".answerDiv:not([id='answersTrTemplate'])").each(function(index) {
            var name = "answers" + index;
            var checkName = "correctAnswerCheck" + index;
            $(this).find(".questionContent").attr('name', name);
            $(this).find(".checkContent").attr('name', checkName);
            console.log($(this).find(".checkContent").attr('name'));
        });
    });
    $('.submitQbase').click(function() {
        $(".qbaseDiv:not([id='pointsTrTemplate']").each(function(index) {
            var name = "points" + index;
            $(this).find(".qbasePoints").attr('name', name);
        });
    });


});

$(document).ready(function() {
    $(".dropdown-trigger").click(function() {
        $(this).closest(".dropdown-element").find(".dropdown-menu").first().slideToggle(300);
    });
});