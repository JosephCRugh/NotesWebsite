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

  $statement = $db->prepare("UPDATE user_projects SET name=? WHERE name=? AND id=?");
  $statement->bindValue(1, $newProjectName, SQLITE3_TEXT);
  $statement->bindValue(2, $currentProjectName, SQLITE3_TEXT);
  $statement->bindValue(3, $_SESSION['sess_id'], SQLITE3_INTEGER);

  $statement->execute();

  $db->close();

?>
