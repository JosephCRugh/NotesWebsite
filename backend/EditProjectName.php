<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

  require 'EnforceSession.php';

  $projectId = $_POST['projectId'];
  $newProjectName = $_POST['newProjectName'];

  if (empty($newProjectName)) {
    return;
  }

  if (!preg_match("/^[a-zA-Z]+$/", $newProjectName)){
    return;
  }

  if (strlen($newProjectName) > 20) {
    return;
  }

  if ($_SESSION['pageOwnerId'] != $_SESSION['user_id']) {
    return;
  }

  require 'EnforceSqliteConnection.php';

  require 'CheckIfProjectIsTaken.php';
  if (isProjectNameTaken($db, $newProjectName)) {
    $db->close();
    echo "fail";
    return;
  }

  require 'UpdateUserProject.php';
  updateProject($projectId, "SET name=?", $newProjectName, SQLITE3_TEXT);

?>
