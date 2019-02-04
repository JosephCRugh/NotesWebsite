<?php

  require 'EnforceSession.php';

  $projectName = $_POST['projectName'];
  $projectDesc = $_POST['projectDesc'];

  if (!empty($projectDesc)) {
    if (strlen($projectDesc) > 100) {
      return;
    }
  }

  require 'UpdateUserProject.php';
  updateProject($projectName, "SET description=?", $projectDesc, SQLITE3_TEXT);

?>
