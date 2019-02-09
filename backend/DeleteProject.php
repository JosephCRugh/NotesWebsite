<?php

  require 'EnforceSession.php';

  $projectId = $_POST["projectId"];

  require 'EnforceSqliteConnection.php';

  $statement = $db->prepare("DELETE FROM user_projects WHERE project_id=?");
  $statement->bindValue(1, $projectId, SQLITE3_INTEGER);

  $statement->execute();

  $db->close();

?>
