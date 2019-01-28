// Reference:
// https://www.w3resource.com/javascript/form/email-validation.php
const EMAIL_REGEX = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/;
const ALPHABET_ONLY = /^[A-Za-z]+$/;

$( document ).ready(function() {

  resetButtonFail($('#process-register-button'), "Submit");

  clearInvInput($("#register-first-name"));
  clearInvInput($("#register-last-name"));
  clearInvInput($("#register-email"));
  clearInvInput($("#register-password"));
  clearInvInput($("#register-retyped-password"));

  attemptRegister();

});

function attemptRegister() {

  var subBtn = $('#process-register-button');
  subBtn.click(function() {

    var firstName = $("#register-first-name").val();
    var lastName = $('#register-last-name').val();
    var email = $('#register-email').val();
    var password = $('#register-password').val();
    var retypedPassword = $('#register-retyped-password').val();

    var isError = false;
    if (isEmpty(firstName)) {
      setFailButton(subBtn, "No First Name");
      $('#register-first-name').addClass('is-invalid');
      isError = true;
    }

    if (isEmpty(lastName)) {
      if (!isError) {
        setFailButton(subBtn, "No Last Name");
      }
      $('#register-last-name').addClass('is-invalid');
      isError = true;
    }

    if (isEmpty(email)) {
      if (!isError) {
        setFailButton(subBtn, "No Email");
      }
      $('#register-email').addClass('is-invalid');
      isError = true;
    }

    if (isEmpty(password)) {
      if (!isError) {
        setFailButton(subBtn, "No Password");
      }
      $('#register-password').addClass('is-invalid');
      isError = true;
    }

    if (isEmpty(retypedPassword)) {
      if (!isError) {
        setFailButton(subBtn, "No Retyped Password");
      }
      $('#register-retyped-password').addClass('is-invalid');
      isError = true;
    }

    if (firstName.length > 20) {
      if (!isError) {
        setFailButton(subBtn, "First Name Too Long");
      }
      $('#register-first-name').addClass('is-invalid');
      isError = true;
    }

    if (lastName.length > 20) {
      if (!isError) {
        setFailButton(subBtn, "Last Name Too Long");
      }
      $('#register-last-name').addClass('is-invalid');
      isError = true;
    }

    if (email.length > 250) {
      if (!isError) {
        setFailButton(subBtn, "Email Too Long");
      }
      $('#register-email').addClass('is-invalid');
      isError = true;
    }

    if (password.length > 50) {
      if (!isError) {
        setFailButton(subBtn, "Password Too Long");
      }
      $('#register-password').addClass('is-invalid');
      isError = true;
    }

    if (!firstName.match(ALPHABET_ONLY)) {
      if (!isError) {
        setFailButton(subBtn, "First Name Alphabet Only");
      }
      $('#register-first-name').addClass('is-invalid');
      isError = true;
    }

    if (!lastName.match(ALPHABET_ONLY)) {
      if (!isError) {
        setFailButton(subBtn, "Last Name Alphabet Only");
      }
      $('#register-last-name').addClass('is-invalid');
      isError = true;
    }

    if (!email.match(EMAIL_REGEX)) {
      if (!isError) {
        setFailButton(subBtn, "Invalid Email");
      }
      $('#register-email').addClass('is-invalid');
      isError = true;
    }

    if (password !== retypedPassword) {
      if (!isError) {
        setFailButton(subBtn, "Passwords Don't Match");
      }
      $('#register-retyped-password').addClass('is-invalid');
      isError = true;
    }

    if (isError) {
      return;
    }

    $.post("backend/ProcessRegister.php",
          {
            firstName: firstName,
            lastName: lastName,
            email: email,
            password: password
          },
          function(response) {
            if (response === "fail") {
              setFailButton(subBtn, "Email Taken");
            } else if (response === "success") {
              $(location).attr('href', 'HomePage.php');
            }
    });

  });

}
