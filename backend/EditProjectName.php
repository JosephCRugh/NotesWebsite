<?php

  require 'EnforceSession.php';

  $currentProjectName = $_POST['currentProjectName'];
  $newProjectName = $_POST['newProjectName'];

  if (empty($currentProjectName) || empty($newProjectName)) {
    return;
  }

  if (!preg_match("/^[a-zA-Z]+$/", $newProjectName)){
    return;
  }

  if (strlen($newProjectName) > 20) {
    return;
  }

  if ($_SESSION['pageOwnerId'] != $_SESSION['sess_id']) {
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
  updateProject($currentProjectName, "SET name=?", $newProjectName, SQLITE3_TEXT);

  $_SESSION['editprojectname'] = 'true';

?>
