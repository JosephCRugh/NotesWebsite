<?php

  function isProjectNameTaken($db, $projectName) {
    $projectNameTakenStmt = $db->prepare("SELECT EXISTS(SELECT 1 FROM user_projects WHERE id=? AND name=?)");
    $projectNameTakenStmt->bindValue(1, $_SESSION['sess_id'], SQLITE3_INTEGER);
    $projectNameTakenStmt->bindValue(2, $projectName, SQLITE3_TEXT);

    $result = $projectNameTakenStmt->execute();

    if ($result->fetchArray()[0]) {
      return true;
    } else {
      return false;
    }
  }

?>
