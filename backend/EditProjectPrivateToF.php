<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

  require 'EnforceSession.php';

  $projectName = $_POST['projectName'];
  $privateToF = $_POST['privateToF'];

  require 'UpdateUserProject.php';
  updateProject($projectName, "SET privateToF=?", $privateToF == "true" ? 1 : 0, SQLITE3_INTEGER);

?>
