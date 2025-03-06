$(document).ready(function () {
    $(".setfloatvalue").focusout(function () {
        if ($(this).val()) {
            $(this).val(parseFloat($(this).val()).toFixed(2));
        }
    });
    $(".allowfloatvalue").keydown(function (e) {
        allowNumbersOnly(e, true);
    });
    $(".allowtextonly").keydown(function (e) {
        allowTextOnly(e);
    });
    $(".allownumbervalue").keydown(function (e) {
        allowNumbersOnly(e, false);
    });
    $('.setpropercase').on('keypress keyup change', function () {
        var str = this.id;

        var val2 = $('#' + str).val();
        var valarr = val2.split(' ');
        var allval = '';
        for (i = 0; i < valarr.length; i++) {
            valarr[i] = valarr[i].toString();
            if (i != 0) {
                allval = allval + ' ' + valarr[i].charAt(0).toUpperCase() + valarr[i].substr(1).toLowerCase();
            } else {
                allval = allval + valarr[i].charAt(0).toUpperCase() + valarr[i].substr(1).toLowerCase();
            }
        }
        $('#' + str).val(allval);
    });
})

function blockSpecialChar(e) {
    var k = e.keyCode;
    return ((k > 64 && k < 91) || (k > 96 && k < 123) || k == 8 || (k >= 48 && k <= 57));
}
function allowNumbersOnly(e, allowDot) {
    var codeArray = [46, 8, 9, 27, 13, 190];
    if (allowDot) {
        codeArray.push(110)
    };
    if ($.inArray(e.keyCode, codeArray) !== -1 || ((e.keyCode == 65 || e.keyCode == 86 || e.keyCode == 67) && (e.ctrlKey === true || e.metaKey === true)) || (e.keyCode >= 35 && e.keyCode <= 40)) {
        return;
    }
    if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
        e.preventDefault();
    }
}

function allowTextOnly(e) {
    var codeArray = [46, 8, 9, 27, 13, 190, 110, 32];
    if ($.inArray(e.keyCode, codeArray) !== -1 || (e.keyCode == 65 && (e.ctrlKey === true) || (e.keyCode >= 35 && e.keyCode <= 40))) {
        return;
    }
    if ((e.keyCode >= 65 && e.keyCode <= 90)) {
        return;
    } else {
        e.preventDefault();
    }
}

function disablebutton(name) {
    $("." + name).each(function () {
        $(this).prop("disabled", true);
    });
}

function showErrorMessage(message) {
    document.getElementById('errormessage').style.display = "block";
    document.getElementById("errormessages").innerHTML = message;
    enablebutton('savebtn');
    $("#errormessage").focus();
}

function validateFormData(fieldsets) {
    // debugger;
    disablebutton('savebtn');
    var validated = true;
    var fieldsetid = "#" + fieldsets;
    $(fieldsetid + " select.required").each(function () {
        var value = $(this).val();
        var id = $(this).prop('id');
        if (value == null || value == 'null' || value == '' || value == -1 || value == undefined) {
            var title = $(this).prop('title');
            if (!title || title.length == 0) {
                title = $(this).prop('name');
            }
            var message = "Please select the " + title + ".";
            if (!$('#' + id + '-error-message').length) {
                $(this).parent().parent().append('<label id="' + id + '-error-message" for=' + id + ' class="errormessage">' + message + '</label>');
                $(this).focus();
            }
            validated = false;
        }
    });
    $(fieldsetid + " input[type=text].required").each(function () {

        var value = $(this).val().trim();
        if (value == '') {
            var title = $(this).prop('title');
            var id = $(this).prop('id');
            if (!title || title.length == 0) {
                title = $(this).prop('name');
            }
            var message = "Please insert the " + title + ".";
            if (!$('#' + id + '-error-message').length) {
                $(this).parent().parent().append('<label id="' + id + '-error-message" for=' + id + ' class="errormessage">' + message + '</label>');
                $(this).focus();
            }
            validated = false;
        }
    });

    $(fieldsetid + " input[type=file].required.inputfile").each(function () {
        
        
        var value = $(this).val().trim();
        if (value == '') {
            var title = $(this).prop('title');
            var id = $(this).prop('id');
            if (!title || title.length == 0) {
                title = $(this).prop('name');
            }
            var message = "Please insert the " + title + ".";
            if (!$('#' + id + '-error-message').length) {
                $(this).parent().append('<label id="' + id + '-error-message" for=' + id + ' class="errormessage">' + message + '</label>');
                $(this).focus();
            }
            validated = false;
        }
    });

    $(fieldsetid + " input[type=file].required").each(function () {
        // debugger;
        console.log($(this).parent().parent().find('img').attr('src'));
        
        if($(this).parent().parent().find('img').attr('src')!='' && $(this).parent().parent().find('img').attr('src')!=undefined && $(this).parent().parent().find('img').attr('src')!=null && $(this).parent().parent().find('img').attr('src')!='undefined')
        {
            return true;
        }
        var value = $(this).val().trim();
        if (value == '') {
            var title = $(this).prop('title');
            var id = $(this).prop('id');
            if (!title || title.length == 0) {
                title = $(this).prop('name');
            }
            var message = "Please insert the " + title + ".";
            if (!$('#' + id + '-error-message').length) {
                $(this).parent().parent().append('<label id="' + id + '-error-message" for=' + id + ' class="errormessage">' + message + '</label>');
                $(this).focus();
            }
            validated = false;
        }
    });

    $(fieldsetid + " .fileext").each(function () {

        var file = $(this).prop('files')[0];
        var id = $(this).prop('id');
        if (file != NaN && file != undefined) {
            var ext1 = $(this).attr('ext');
            var res = ext1.split(",");
            var ext = $(this).val().split('.').pop().toLowerCase();
            if ($.inArray(ext, res) == -1) {
                var message = "The Selected File should be in " + ext1 + " format.";
                if (!$('#' + id + '-error-message').length) {
                    $(this).parent().append('<label style="font-size: 12px; !important" id="' + id + '-error-message" for=' + id + ' class="errormessage">' + message + '</label>');
                    $(this).focus();
                }
                validated = false;
            }
        }
    });


    $(fieldsetid + " textarea.required").each(function () {
        var value = $(this).val().trim();
        var id = $(this).prop('id');
        if (value == '') {
            var title = $(this).prop('title');
            if (!title || title.length == 0) {
                title = $(this).prop('name');
            }
            var message = "Please insert the " + title + ".";
            if (!$('#' + id + '-error-message').length) {
                $(this).parent().parent().append('<label id="' + id + '-error-message" for=' + id + ' class="errormessage">' + message + '</label>');
                $(this).focus();
            }
            validated = false;
        }
    });

    $(fieldsetid + " .checkFileSize").each(function () {
        var limitsize = $(this).attr('filesize');
        var id = $(this).prop('id');
        if (limitsize == undefined) {
            limitsize = 2;
        }
        var file = $(this).prop('files')[0];
        if (file != NaN && file != undefined) {
            var title = $(this).prop('title');
            title = title && title.length > 0 ? title : 'Attachment';
            var fileSize = file.size;
            var fileSizeInKb = (fileSize / 1024).toFixed(2);
            var maxSize = parseInt(limitsize)*1024;
            if (parseFloat(fileSizeInKb) > parseFloat(maxSize)) {
                var message = "The Selected File Size Can't Be More Than " + maxSize + " KB.";
                if (!$('#' + id + '-error-message').length) {
                    $(this).parent().parent().append('<label style="font-size: 12px; !important" id="' + id + '-error-message" for=' + id + ' class="errormessage">' + message + '</label>');
                    $(this).focus();
                }
                validated = false;
            }
        }
    });



    $(fieldsetid + " .checknumbervalue").each(function () {
        var value = $(this).val();
        var id = $(this).prop('id');

        if (value != '' && parseInt(value) != value) {
            var title = $(this).prop('title');
            if (!title || title.length == 0) {
                title = $(this).prop('name');
            }
            var message = "Please fill the Number value only in '" + title + "'.";
            if (!$('#' + id + '-error-message').length) {
                $(this).parent().append('<label id="' + id + '-error-message" for=' + id + ' class="errormessage">' + message + '</label>');
                $(this).focus();
            }
            validated = false;
        }
    });

    $(fieldsetid + " .checkpincode").each(function () {
        var id = $(this).prop('id');

        var value = $(this).val().trim();
        if (value.length > 0 && !(validatePin(value))) {
            var title = $(this).prop('title');
            if (!title || title.length == 0) {
                title = $(this).prop('name');
            }
            var message = title + " should be 6 digits";
            if (!$('#' + id + '-error-message').length) {
                $(this).parent().append('<label id="' + id + '-error-message" for=' + id + ' class="errormessage">' + message + '</label>');
                $(this).focus();
            }
            validated = false;
        }
    });
    $(fieldsetid + " .checkmobileno").each(function () {
        var value = $(this).val().trim();
        var id = $(this).prop('id');
        if (value.length > 0 && !validateMobile(value)) {
            var title = $(this).prop('title');
            if (!title || title.length == 0) {
                title = $(this).prop('name');
            }
            var message = title + " should be 10 digits";
            if (!$('#' + id + '-error-message').length) {
                $(this).parent().parent().append('<label id="' + id + '-error-message" for=' + id + ' class="errormessage">' + message + '</label>');
                $(this).focus();
            }
            validate = false;
        }
    });
    $(fieldsetid + " .checkemail").each(function () {
        var value = $(this).val().trim();
        var id = $(this).prop('id');
        if (value.length > 0 && !validateEmail(value)) {
            var title = $(this).prop('title');
            if (!title || title.length == 0) {
                title = $(this).prop('name');
            }
            var message = title + " is not valid";
            $(this).focus();
            if (!$('#' + id + '-error-message').length) {
                $(this).parent().parent().append('<label id="' + id + '-error-message" for=' + id + ' class="errormessage">' + message + '</label>');
                $(this).focus();
            }
            validated = false;
        }
    });
    // debugger;
    $(fieldsetid + " .checkaadharno").each(function () {
        // debugger;
        var value = $(this).val().trim();
        var id = $(this).prop('id');
        if (value.length > 0 && !validateAadhar(value)) {
            var title = $(this).prop('title');
            if (!title || title.length == 0) {
                title = $(this).prop('name');
            }
            var message = title + " is not valid";
            $(this).focus();
            if (!$('#' + id + '-error-message').length) {
                $(this).parent().parent().append('<label id="' + id + '-error-message" for=' + id + ' class="errormessage">' + message + '</label>');
                $(this).focus();
            }
            validated = false;
        }
    });

    $(fieldsetid + ".textToupper").each(function () {
        var value = $(this).val();
        if (value) {
            $(this).val(value.toUpperCase());
        }
    });

    $(fieldsetid + ".textToproper").each(function () {
        var value = $(this).val();
        if (value) {
            $(this).val(toProperCase(value));
        }
    });


    $(fieldsetid + ".textTolower").each(function () {
        var value = $(this).val();
        if (value) {
            $(this).val(value.toLowerCase());
        }
    });
    if (!validated) {
        enablebutton("savebtn")
        return false;
    } else {
        return true;
    }
}

// $(document).ready(function () {
//     document.onkeydown = function (e) {
//         if (e.ctrlKey && e.keyCode === 85) {
//             return false;
//         }
//         if (e.ctrlKey && e.shiftKey && e.keyCode === 73) {
//             return false;
//         } else {
//             return true;
//         }
//     };
// })

// document.addEventListener('contextmenu', event => event.preventDefault());


function toProperCase(str) {
    return str.replace(/(?:^|\s)\w/g, function (match) {
        return match.toUpperCase();
    });
}

function enablebutton(name) {
    $("." + name).each(function () {
        $(this).removeProp("disabled")
    });
}

function validateMobile(mobileNo) {
    var filter = /^\d{10}$/;
    if (filter.test(mobileNo)) {
        return true;
    } else {
        return false;
    }
}

function validatePin(pincode) {
    var filter = /^\d{6}$/;
    if (filter.test(pincode)) {
        return true;
    } else {
        return false;
    }
}

function validateAadhar(aadharNo) {
    var filter = /^\d{12}$/;
    if (filter.test(aadharNo)) {
        return true;
    } else {
        return false;
    }
}

function validateEmail(emailAddress) {
    var filter = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
    if (filter.test(emailAddress)) {
        return true;
    } else {
        return false;
    }
}

(function ($) {
    "use strict";
    var input = $('.validate-input .input100');

    $('.validate-form').on('submit', function () {
        var check = true;
        for (var i = 0; i < input.length; i++) {
            if (validate(input[i]) == false) {
                showValidate(input[i]);
                check = false;
            }
        }
        return check;
    });



    $('.validate-form .input100').each(function () {
        $(this).focus(function () {
            hideValidate(this);
        });
    });



    function validate(input) {
        if ($(input).attr('type') == 'email' || $(input).attr('name') == 'email') {
            if ($(input).val().trim().match(/^([a-zA-Z0-9_\-\.]+)@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.)|(([a-zA-Z0-9\-]+\.)+))([a-zA-Z]{1,5}|[0-9]{1,3})(\]?)$/) == null) {
                return false;
            }
        }

        else {
            if ($(input).val().trim() == '') {
                return false;
            }
        }
    }

    function showValidate(input) {
        var thisAlert = $(input).parent();
        $(thisAlert).addClass('alert-validate');
    }

    function hideValidate(input) {
        var thisAlert = $(input).parent();
        $(thisAlert).removeClass('alert-validate');
    }

})(jQuery);


$(document).ready(function () {
    $('input,select').change(function () {
        var valueofelement = $(this).val();
        var id = $(this).attr('id');
        if (valueofelement != '' || valueofelement != '-1') {
            $("#" + id + "-error-message").remove();
            $('#'+id).css('border-color', '#ced4da');
        }
    });
    var current_fs, next_fs, previous_fs; 
    var opacity;
    var current = 1;
    var steps = $("fieldset").length;

    setProgressBar(current);

    $(".next").click(function () {
        // debugger;
        var fieldset = $(this).parent().parent().parent().closest('fieldset').attr('id');


        if (validateFormData(fieldset) == true) {
            
            current_fs = $(this).parent().parent().parent();
            next_fs = $(this).parent().parent().parent().next();
            $("#maintabs li a").eq($("fieldset").index(next_fs)).addClass("active");
            $("#maintabs li a").eq($("fieldset").index(current_fs)).removeClass("active");
            next_fs.show();
            current_fs.animate({ opacity: 1 }, {
                step: function (now) {
                    opacity = 1 - now;

                    current_fs.css({
                        'display': 'none',
                        'position': 'relative'
                    });
                    // next_fs.css({ 'opacity': opacity });
                },
                duration: 500
            });
            var formid = $(this).parent().parent().parent().children('form').attr('id');
            var lastid = $(this).attr('id');
            setProgressBar(++current);
            submitStepForm(formid,lastid);
        }
    });

    $(".submitnext").click(function () {
        var fieldset = $(this).parent().parent().parent().closest('fieldset').attr('id');
        if (validateFormData(fieldset) == true) {
            // $("#mtplform input,select").removeAttr('disabled', 'disabled');
            $("#mtplform").submit();
        }
    });


    $(".previous").click(function () {

        current_fs = $(this).parent().parent().parent();
        previous_fs = $(this).parent().parent().parent().prev();

        
        $("#maintabs li a").eq($("fieldset").index(current_fs)).removeClass("active");
        $("#maintabs li a").eq($("fieldset").index(previous_fs)).addClass("active");
            // $("#maintabs li a").eq($("fieldset").index(current_fs)).removeClass("active");

        
        previous_fs.show();

        
        current_fs.animate({ opacity: 1 }, {
            step: function (now) {
                
                opacity = 1 - now;

                current_fs.css({
                    'display': 'none',
                    'position': 'relative'
                });
                // previous_fs.css({ 'opacity': opacity });
            },
            duration: 500
        });
        setProgressBar(--current);
    });

    function setProgressBar(curStep) {
        var percent = parseFloat(100 / steps) * curStep;
        percent = percent.toFixed();
        $(".progress-bar")
            .css("width", percent + "%")
    }

    $(".submit").click(function () {
        return false;
    })
});
$(document).on('keypress', '.onlytextvalue', function (event) {
    var regex = new RegExp("^[a-zA-Z ]+$");
    var key = String.fromCharCode(!event.charCode ? event.which : event.charCode);
    if (!regex.test(key)) {
        event.preventDefault();
        return false;
    }
});
$(document).ready(function () {
    $('.onlynumbervalue').keypress(function (e) {

        var a = [];
        var k = e.which;

        for (i = 48; i < 58; i++)
            a.push(i);

        if (!(a.indexOf(k) >= 0))
            e.preventDefault();
    });

});

// function submitStepForm(formid,isfinal)
// {
//     console.log(formid,isfinal);
   
//     var formData = new FormData($('#'+formid)[0]);
// // console.log(formData);return false;
//     formData.append("_token", $('meta[name="csrf-token"]').attr('content'));
//     formData.append('inserted_id', localStorage.getItem('inserted_id'));
//  formData.append('lead_id', '<?php echo isset($_GET['lead_id']) ? $lead_id : 0 ?>');
   
//     if(localStorage.getItem('inserted_id') !==null)
//     {
//         // formData.append('inserted_id', localStorage.getItem('inserted_id'));
       
//         var app_id = localStorage.getItem('inserted_id');
//         var url = '/app/application-form/'+formid;
//     }
//     else
//     {
//        var url = '/app/application-form/'+formid;
//     }
//     $.ajax({
//         url: url,
//         type:"POST",
//         data:formData,
//         success: function(response) {
//             // console.log(response);return false;
//             if (response.status == 'success') {
//                 if(response.app_id && response.app_id!='')
//                 {
//                     localStorage.setItem('app_id',response.app_id);
//                 }
//               toastr.success(response.message);
//                 if(isfinal=='final')
//                 {
//                     window.location.href = '/manage/students/applications';
//                     localStorage.removeItem('app_id');
//                 }
//             } else {
//               toastr.error(response.message);
//             }
//           },
//           error: function(response) {
//             toastr.error(response.responseJSON.message);
//           },
//         cache: false,
//         contentType: false,
//         processData: false,
//     })
// }