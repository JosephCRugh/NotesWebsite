// Reference:
// https://www.w3resource.com/javascript/form/email-validation.php
const EMAIL_REGEX = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/;

$( document ).ready(function() {

  resetButtonFail($('#process-login-button'), "LOG IN");

  clearInvInput($('#login-email'));
  clearInvInput($('#login-password'));

  attemptLogin();

});

function attemptLogin() {

  var logBtn = $('#process-login-button');
  logBtn.click(function() {

    var email = $('#login-email').val();
    var password = $('#login-password').val();

    var isError = false;
    if (isEmpty(email)) {
        setFailButton(logBtn, "No Email");
        $('#login-email').addClass('is-invalid');
        isError = true;
    }

    if (isEmpty(password)) {
        if (!isError) {
          setFailButton(logBtn, "No Password");
        }
        $('#login-password').addClass('is-invalid');
        isError = true;
    }

    if (email.lenth > 250) {
      if (!isError) {
        setFailButton(logBtn, "Email Too Long");
      }
      $('#login-email').addClass('is-invalid');
      isError = true;
    }

    if (!email.match(EMAIL_REGEX)) {
      if (!isError) {
        setFailButton(logBtn, "Invalid Email");
      }
      $('#login-email').addClass('is-invalid');
      isError = true;
    }

    if (password.length > 50) {
      if (!isError) {
        setFailButton(logBtn, "Password Too Long");
      }
      $('#login-password').addClass('is-invalid');
      isError = true;
    }

    if (isError) {
      return;
    }

    $.post("backend/ProcessLogin.php",
          { email: email, password: password },
          function(response) {
          if (response === "fail") {
            setFailButton(logBtn, "Invalid Email Or Password");
          } else if (response === "success") {
            $(location).attr('href', 'index.php');
          }
    });
  });
}
