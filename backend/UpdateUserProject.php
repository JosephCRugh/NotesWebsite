<?php

  function updateProject($projectName, $instmt, $value, $type) {
    require 'EnforceSqliteConnection.php';

    $statement = $db->prepare("UPDATE user_projects " . $instmt . " WHERE name=? AND id=?");
    $statement->bindValue(1, $value, $type);
    $statement->bindValue(2, $projectName, SQLITE3_TEXT);
    $statement->bindValue(3, $_SESSION['sess_id'], SQLITE3_INTEGER);

    $statement->execute();

    $db->close();
  }

?>
