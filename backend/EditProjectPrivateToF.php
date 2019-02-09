<?php

  require 'EnforceSession.php';

  $projectId = $_POST['projectId'];
  $privateToF = $_POST['privateToF'];

  require 'UpdateUserProject.php';
  updateProject($projectId, "SET privateToF=?", $privateToF == "true" ? 1 : 0, SQLITE3_INTEGER);

?>
