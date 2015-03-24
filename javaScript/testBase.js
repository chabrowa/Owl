$(document).ready(function() {

    $(".addNewPdfTestBtn").leanModal();
    $(".addNewPortalTestBtn").leanModal();

    $('#startdatetimepicker').datetimepicker();
    $('#finishdatetimepicker').datetimepicker();
    
    $('#moveToPlanedstartdatetimepicker').datetimepicker();
    $('#moveToPlanedfinishdatetimepicker').datetimepicker();

    $(".moveToPlanedBnt").on("click", function() {
        $("#planedTest input[name='chosenTestId']").val($(this).data("test-id"));
    });
    $(".moveToPlanedBnt").leanModal();


    $(".datesAppear").change(function() {
        if ($(this).val() === 'planed') {
            //$(".dates").show();
            $(".dates input" ).prop('disabled', false);
        } else {
           // $(".dates").hide();
           $(".dates input").val("");
            $(".dates input" ).prop('disabled', true);
        }
    });
    
    
    ////////////////////////////////////////////////////////////////
    var groupsId = [];
    
    $('input[name="submitStudentsGroup"').click(function(){
        var $input = $('input[name="studentsBaseId"]');
        var val = $input.val();
        var name= val.substr(0, val.indexOf('#'));
        var id = val.substr(val.indexOf('#') + 1, val.length);
        groupsId.push(id);
        var template = document.getElementById('student_group_template');
        $('.studentsGroupListTable').append(Mustache.render(template.innerHTML, {name: name}));
        
        $('.deleteGroupBtn').click(function(){
            var id = $(this).data('id');
            var index = groupsId.indexOf(id);
            if( index > -1 )
                groupsId.splice(index, 1);
            $(this).closest('div.dropdown-element').remove();
            
            return false;
        });
        
        return false;
    });
    
    $('input[name="submitTest"]').click(function(){
        var str = groupsId.join(",");
        $('input[name="groupsIds"]').val(str);
        
        $('#testForm').submit();
    });
});

