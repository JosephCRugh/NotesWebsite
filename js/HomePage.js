$( document ).ready(function() {

  $('#projects-pane div div[name="new-project"]').click(function() {

    // Redirecting the user to make a new project.
    $(location).attr('href', 'NewProject.php');
  });

  $('#projects-pane div div[name="existing-project"]').click(function() {

    // Redirecting the user to the project page they selected.
    var ids = $(this).attr('id').split('-');
    var userId =  ids[2];
    var projectId = ids[3];
    // console.log("Redirecting to : " + ('ProjectPage.php?pid=' + projectId + "&uid=" + userId));
    $(location).attr('href', 'ProjectPage.php?pid=' + projectId + "&uid=" + userId);
  });
});
