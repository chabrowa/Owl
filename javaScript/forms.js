$(document).ready(function() {
    $("form[name='registration']").on("submit", function() {
        var isValid = true;
        $(this).find("input[type='text']").each(function() {
            if (!stringValidation($(this))) {
                isValid = false;
            }
        });
        $(this).find("input[type='password']").each(function() {
            if (!stringValidation($(this))) {
                isValid = false;
            }
        });
        $(this).find("input[type='email']").each(function() {
            if (!mailValidation($(this))) {
                isValid = false;
            }
        });
        return isValid;
    });
});

$(document).ready(function() {
    $("form[name='login']").on("submit", function() {
        var isValid = true;
        $(this).find("input[type='text']").each(function() {
            if (!stringValidation($(this))) {
                isValid = false;
            }
        });
        $(this).find("input[type='password']").each(function() {
            if (!stringValidation($(this))) {
                isValid = false;
            }
        });
        return isValid;
    });
});

function stringValidation($field)
{
    var value = $field.val();
    if (value == null || value === "")
    {
        $("div[data-assosiated-field='" + $field.attr("name") + "']").text("Field value is incorrect");
        return false;
    }
    else {
        $("div[data-assosiated-field='" + $field.attr("name") + "']").text("");
        return true;
    }
}

function mailValidation($field)
{
    var value = $field.val();
    var atpos = value.indexOf("@");
    var dotpos = value.lastIndexOf(".");
    if (atpos < 1 || dotpos < atpos + 2 || dotpos + 2 >= value.length)
    {
        $("div[data-assosiated-field='" + $field.attr("name") + "']").text("Address is not correct");
        return false;
    }
    else {
        $("div[data-assosiated-field='" + $field.attr("name") + "']").text("");
        return true;
    }
}