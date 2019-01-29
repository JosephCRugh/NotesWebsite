$( document ).ready(function() {

  $('#projects-pane div div[name="new-project"]').click(function() {

    // Redirecting the user to make a new project.
    $(location).attr('href', 'NewProject.php');
  });

  $('#projects-pane div div[name="existing-project"]').click(function() {

    // Redirecting the user to the project page they selected.
    var projectName = $(this).first().text().trim();
    var userId =  $(this).attr('id').split('-')[2];
    $(location).attr('href', 'ProjectPage.php?name=' + projectName + "&id=" + userId);
  });
});
