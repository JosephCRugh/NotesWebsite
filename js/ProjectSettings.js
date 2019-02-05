const ALPHABET_ONLY = /^[A-Za-z]+$/;

$( document ).ready(function() {

  editProjectName();

  editProjectDesc();

  editPrivateTof();

  hideUserSearchResults();
  userSearch();

  clickedAddUser(onUserAdd);

  clickedRemoveUser(onUserRemove);

});

function editProjectName() {

  $('#form-project-name').click(function() {
      $('#save-name-btn').text('Save').removeClass('btn-danger').addClass('btn-primary');
      $(this).removeClass('is-invalid');
      $('#save-name-label-success').hide();
      $('#save-name-span-success').hide();
  });

  $('#save-name-btn').click(function() {

    var currentProjectName = $('title').text().split(" ")[0];
    var projectName = $('#form-project-name').val();

    if (projectName === currentProjectName) {
      return;
    }

    if (isEmpty(projectName)) {
      $('#form-project-name').addClass('is-invalid');
      setFailButton($(this), "No Name");
      return;
    }

    if (projectName.length > 20) {
      $('#form-project-name').addClass('is-invalid');
      setFailButton($(this), "Too Long");
      return;
    }

    if (!projectName.match(ALPHABET_ONLY)) {
      $('#form-project-name').addClass('is-invalid');
      setFailButton($(this), "Alphabet Characters Only");
      return;
    }

    $.post("backend/EditProjectName.php", {
      currentProjectName: currentProjectName,
      newProjectName: projectName
    }, function(response) {
      if (response == "fail") {
        $('#form-project-name').addClass('is-invalid');
        setFailButton($('#save-name-btn'), "Name Taken");
      } else {
        $(location).attr(
          'href',
          'ProjectSettings.php?name=' + projectName + "&id=" + $('body').attr('id').split('-')[2]);
      }
    });
  });
}

function editProjectDesc() {

  $('#form-project-description').click(function() {
      $('#save-desc-btn').text('Save').removeClass('btn-danger').addClass('btn-primary');
      $(this).removeClass('is-invalid');
      $('#save-desc-label-success').hide();
      $('#save-desc-span-success').hide();
  });

  $('#save-desc-btn').click(function() {

    var projectDesc = $('#form-project-description').val();

    if (!isEmpty(projectDesc)) {
      if (projectDesc.length > 100) {
        $('#form-project-description').addClass('is-invalid');
        setFailButton($(this), "Too Long");
        return;
      }
    }

    $.post('backend/EditProjectDescription.php', {
      projectName: $('title').text().split(" ")[0],
      projectDesc: projectDesc
    }, function() {
      $('#save-desc-label-success').removeAttr('hidden');
      $('#save-desc-label-success').show();
      $('#save-desc-span-success').removeAttr('hidden');
      $('#save-desc-span-success').show();
    });
  });
}

function editPrivateTof() {

  $('input[type=radio]').change(function() {
    var privateToF = $('#form-private-select').is(":checked");

    $.post('backend/EditProjectPrivateToF.php', {
      projectName: $('title').text().split(" ")[0],
      privateToF: privateToF
    });
  });
}

function onUserAdd(userId) {
  $.post('backend/AddUserToProject.php', {
    projectName: $('title').text().split(" ")[0],
    userId: userId
  });
}

function onUserRemove(userId) {
  $.post('backend/RemoveUserFromProject.php', {
    projectName: $('title').text().split(" ")[0],
    userId: userId
  });
}
