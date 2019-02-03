const ALPHABET_ONLY = /^[A-Za-z]+$/;

$( document ).ready(function() {

  $('#form-users-name-displays').hide();

  resetButtonFail($('#submit-create-project'), "Create");
  clearInvInput($('#form-project-name'));
  clearInvInput($('#form-project-description'));
  hideUserSearchResults();

  userSearch();

  clickedAddUser(function() {});

  clickedRemoveUser(function() {});

  submitForm();

});

function submitForm() {
  $('#submit-create-project').click(function() {

    var projectName = $('#form-project-name').val();
    var projectDesc = $('#form-project-description').val();
    var privateToF = $('#form-private-select').is(":checked");

    var isError = false;
    if (isEmpty(projectName)) {
      $('#form-project-name').addClass('is-invalid');
      setFailButton($(this), "No Project Name");
      isError = true;
      return;
    }

    if (!projectName.match(ALPHABET_ONLY)) {
      $('#form-project-name').addClass('is-invalid');
      if (!isError) {
        setFailButton($(this), "Invalid Project Name");
      }
      isError = true;
      return;
    }

    if (projectName.length > 20) {
      $('#form-project-name').addClass('is-invalid');
      if (!isError) {
        setFailButton($(this), "Project Name Too Long");
      }
      isError = true;
    }

    if (!isEmpty(projectDesc)) {
      if (projectDesc.length > 100) {
        $('#form-project-description').addClass('is-invalid');
        if (!isError) {
          setFailButton($(this), "Project Description Too Long");
        }
        isError = true;
      }
    }

    if (isError) {
      return;
    }

    var userIds = [];
    $('#form-added-users ul li').each(function(index) {
      userIds.push($(this).attr('id').split('-')[4]);
    });

    $.post('backend/ProcessNewProject.php', {
      projectName: projectName,
      projectDesc: projectDesc,
      privateToF: privateToF,
      userIds: userIds
    }, function(response) {
      if (response == "fail") {
        $('#form-project-name').addClass('is-invalid');
        setFailButton($('#submit-create-project'), "Project Name Taken");
      } else {
        $(location).attr('href', 'ProjectPage.php?name=' + projectName + "&id=" + response);
      }
    });
  });
}
