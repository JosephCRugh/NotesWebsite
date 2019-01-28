$( document ).ready(function() {

  var projectPane = $('#projects-pane div div');
  projectPane.click(function() {
    if (projectPane.attr('name') !== "new-project") {
      return;
    }

    // Redirecting the user to make a new project.
    $(location).attr('href', 'NewProject.php');
  });
});
