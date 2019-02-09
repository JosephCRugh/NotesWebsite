var projectIdToDelete = -1;
var projects = [];
var sliderIndex = 0;

$( document ).ready(function() {

  scrollProjects();

  $('#projects-pane div div[name="new-project"]').click(function() {
    // Redirecting the user to make a new project.
    $(location).attr('href', 'NewProject.php');
  });

  $('.trash-section').click(function() {
    $('.gray-overlay').removeAttr('hidden');
    $('#delete-option').removeAttr('hidden');
    projectIdToDelete = $(this).parent().attr('id').split('-')[3];
  });

  $('#delete-option button[name="no-delete"]').click(function() {
    $('.gray-overlay').attr('hidden', true);
    $('#delete-option').attr('hidden', true);
  });

  $('#delete-option button[name="do-delete"]').click(function() {
    $.post('backend/DeleteProject.php', {
      projectId: projectIdToDelete
    }, function(response) {
      $(location).attr('href', 'index.php');
    });
  });

  $('#projects-pane div div[name="existing-project"]').click(function() {

    if ($('.trash-section:hover').length != 0) {
      return;
    }

    // Redirecting the user to the project page they selected.
    var ids = $(this).attr('id').split('-');
    var userId =  ids[2];
    var projectId = ids[3];
    $(location).attr('href', 'ProjectPage.php?pid=' + projectId + "&uid=" + userId);
  });
});

function scrollProjects() {

  $('#projects-pane div div').each(function(index, inProject) {
    var project = $(inProject);
    if (project.attr('name') != "existing-project") {
      return;
    }
    projects.push(project);
  });

  $('div[name="left-arrow"]').click(function() {
    if (sliderIndex == 0) {
      return;
    }

    sliderIndex--;
    slideProjects();
  });

  $('div[name="right-arrow"]').click(function() {
    if (sliderIndex + 3 == projects.length) {
      return;
    }

    sliderIndex++;
    slideProjects();
  });
}

function slideProjects() {
  var validProjectIndexs = [ sliderIndex, sliderIndex+1, sliderIndex+2 ];
  for (var i = 0; i < projects.length; i++) {
    if (validProjectIndexs.includes(i)) {
      projects[i].attr('hidden', false);
    } else {
      projects[i].attr('hidden', true);
    }
  }
}
