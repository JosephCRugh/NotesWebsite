<?php

  require 'EnforceSession.php';

  $projectName = $_POST['projectName'];
  $privateToF = $_POST['privateToF'];

  require 'UpdateUserProject.php';
  updateProject($projectName, "SET privateToF=?", $privateToF == "true" ? 1 : 0, SQLITE3_INTEGER);

?>
