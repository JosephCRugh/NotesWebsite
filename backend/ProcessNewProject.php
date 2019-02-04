<?php

  require 'EnforceSession.php';

  $projectName = $_POST['projectName'];
  $projectDesc = $_POST['projectDesc'];
  $privateToF = $_POST['privateToF'];
  $userIds = array();

  if (isset($_POST['userIds'])) {
    $userIds = $_POST['userIds'];
  }

  if (empty($projectName)) {
    return;
  }

  if (!preg_match("/^[a-zA-Z]+$/", $projectName)){
    return;
  }

  if (strlen($projectName) > 20) {
    return;
  }

  if (!empty($projectDesc)) {
    if ($projectDesc > 100) {
      return;
    }
  }

  require 'EnforceSqliteConnection.php';

  // Validating that the users exist before adding
  // them to the project.
  $validUserIds = array();
  if (isset($userIds)) {

    foreach ($userIds as &$userId) {

      $statement = $db->prepare("SELECT EXISTS(SELECT 1 FROM user_credentials WHERE id=?)");
      $statement->bindValue(1, $userId, SQLITE3_INTEGER);

      $result = $statement->execute();
      // The user does exist.
      if ($result->fetchArray()[0]) {
        array_push($validUserIds, $userId);
      }
    }
  }

  require 'CheckIfProjectIsTaken.php';
  if (isProjectNameTaken($db, $projectName)) {
    $db->close();
    echo "fail";
    return;
  }

  // Converting the array into text for the database.
  $strUserIds = "";
  foreach ($validUserIds as &$validUserId) {
    $strUserIds .= strval($validUserId) . ",";
  }
  $strUserIds = rtrim($strUserIds, ",");

  $makeProjectStmt = $db->prepare("INSERT INTO user_projects (id, name, description, privateToF, user_ids) VALUES (?, ?, ?, ?, ?)");
  $makeProjectStmt->bindValue(1, $_SESSION['sess_id'], SQLITE3_INTEGER);
  $makeProjectStmt->bindValue(2, $projectName, SQLITE3_TEXT);
  $makeProjectStmt->bindValue(3, $projectDesc, SQLITE3_TEXT);
  $makeProjectStmt->bindValue(4, $privateToF == "true" ? 1 : 0, SQLITE3_INTEGER);
  $makeProjectStmt->bindValue(5, $strUserIds, SQLITE3_TEXT);

  $makeProjectStmt->execute();
  echo $_SESSION['sess_id'];

  $db->close();

?>
