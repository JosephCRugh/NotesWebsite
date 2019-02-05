$(document).ready(function() {

  uploadPicture();

  changePassword();

});

function uploadPicture() {
  $('#picture-upload').on('change', function() {
    var pictureFormData = new FormData();
    pictureFormData.append('file', $(this)[0].files[0]);

    $.ajax({
         url: 'backend/ChangeUserPicture.php',
         type: 'POST',
         data: pictureFormData,
         processData: false,
         contentType: false,
         success: function(response) {
          if (response != "fail") {
            var newImagePath = 'backend/' + response;
            $('#display-picture').attr('src', newImagePath);
            $('#nav-user-toolbar').find('img').attr('src', newImagePath);
          }
         }
       });
  });
}

function changePassword() {

  resetButtonFail($('#submit-new-password'), "Save");

  clearInvInput($('#current-pass-input'));
  clearInvInput($('#new-pass-input'));

  $('#current-pass-input').click(function() {
    $('#saved-pass-span').hide();
    $('#saved-pass-label').hide();
  });

  $('#new-pass-input').click(function() {
    $('#saved-pass-span').hide();
    $('#saved-pass-label').hide();
  });

  $('#submit-new-password').click(function() {
    var currentPass = $('#current-pass-input').val();
    var newPass = $('#new-pass-input').val();

    var isError = false;
    if (isEmpty(currentPass)) {
      setFailButton($(this), "Empty Password");
      $('#current-pass-input').addClass('is-invalid');
      isError = true;
    }

    if (isEmpty(newPass)) {
      if (!isError) {
        setFailButton($(this), "Empty New Password");
      }
      $('#new-pass-input').addClass('is-invalid');
      isError = true;
    }

    if (currentPass.length > 50) {
      if (!isError) {
        setFailButton($(this), "Password Too Long");
      }
      $('#current-pass-input').addClass('is-invalid');
      isError = true;
    }

    if (newPass.length > 50) {
      if (!isError) {
        setFailButton($(this), "New Password Too Long");
      }
      $('#new-pass-input').addClass('is-invalid');
      isError = true;
    }

    if (currentPass === newPass) {
      if (!isError) {
        setFailButton($(this), "Passwords Match");
      }
      $('#new-pass-input').addClass('is-invalid');
      isError = true;
    }

    if (isError) {
      return;
    }

    $.post('backend/ChangeUserPassword.php', {
      currentPass: currentPass,
      newPass: newPass
    }, function(response) {
      if (response == "fail") {
        setFailButton($('#submit-new-password'), "Incorrect Password");
        $('#current-pass-input').addClass('is-invalid');
      } else if (response == "success") {
        $('#saved-pass-span').removeAttr('hidden').show();
        $('#saved-pass-label').removeAttr('hidden').show();
      }
    });
  });
}
