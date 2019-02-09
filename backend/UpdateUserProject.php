<?php

  function updateProject($projectId, $instmt, $value, $type) {
    require 'EnforceSqliteConnection.php';

    $statement = $db->prepare("UPDATE user_projects " . $instmt . " WHERE project_id=? AND user_id=?");
    $statement->bindValue(1, $value, $type);
    $statement->bindValue(2, $projectId, SQLITE3_TEXT);
    $statement->bindValue(3, $_SESSION['user_id'], SQLITE3_INTEGER);

    $statement->execute();

    $db->close();
  }

?>
