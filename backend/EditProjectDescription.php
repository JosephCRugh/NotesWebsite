<?php

  require 'EnforceSession.php';

  $projectId = $_POST['projectId'];
  $projectDesc = $_POST['projectDesc'];

  if (!empty($projectDesc)) {
    if (strlen($projectDesc) > 100) {
      return;
    }
  }

  require 'UpdateUserProject.php';
  updateProject($projectId, "SET description=?", $projectDesc, SQLITE3_TEXT);

?>
