
var error1 = 'This field is required';
var error2 = 'Maximum 255 symbol';
var error3 = 'Please insert a valid Email address';
var error4 = 'The password must be at least 6 characters';
var error5 = 'The Company name must be at least 2 characters';
var error6 = 'Passwords do not match';
var error7 = 'Email is required';
var error8 = 'Password is required';
var re = /^(([^<>()\[\]\.,;:\s@\"]+(\.[^<>()\[\]\.,;:\s@\"]+)*)|(\".+\"))@(([^<>()[\]\.,;:\s@\"]+\.)+[^<>()[\]\.,;:\s@\"]{2,})$/i;

$('#registerForm').on('submit',function () {
    $('.message').hide();
    var errors = false;
    var first_name = $('#first_name').val().trim();
    var last_name = $('#last_name').val().trim();
    var email = $('#email').val().trim();
    var company_name = $('#company_name').val().trim();
    var password = $('#password').val().trim();
    var password_confirm = $('#password-confirm').val().trim();

    if((first_name.length < 1) || (first_name.length >255)){
        $('#span1').text((first_name.length < 1) ? error1 : error2);
        $('#span1').show();
        errors = true;
    }
    if ((last_name.length < 1) || (last_name.length >255)) {
        $('#span2').text((last_name.length < 1) ? error1 : error2);
        $('#span2').show();
        errors = true;
    }
    if ((email.length < 1) || (!re.test(email))) {
        $('#span3').text((email.length < 1) ? error1 : error3);
        $('#span3').show();
        errors = true;
    }
    if ((company_name.length < 1) || (company_name.length < 2)) {
        $('#span4').text((company_name.length < 1) ? error1 : error5);
        $('#span4').show();
        errors = true;
    }
    if ((password.length < 1) || (password.length < 6)) {
        $('#span5').text((password.length < 1) ? error1 : error4);
        $('#span5').show();
        errors = true;
    }
    if (password_confirm !== password) {
        $('#span6').text(error6);
        $('#span6').show();
        errors = true;
    }
    if(errors) return false;
});

$('#loginForm').on('submit',function () {
    $('#warningsP').empty();
    var errors = false;
    var email = $('#email').val().trim();
    var password = $('#password').val().trim();

    if ((email.length < 1) || (!re.test(email))) {
        $('#warningsP').text((email.length < 1) ? error7 : error3);
        errors = true;
    }
    if ((password.length < 1) || (password.length < 6)) {
        $('#warningsP').text((password.length < 1) ? error8 : error4);
        errors = true;
    }
    if(errors) return false;
});