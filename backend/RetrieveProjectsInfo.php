<?php

  require 'EnforceSession.php';
  require 'EnforceSqliteConnection.php';

  $projectsSearchStmt = $db->prepare("SELECT * FROM user_projects WHERE id=?");
  $projectsSearchStmt->bindValue(1, $_SESSION['sess_id'], SQLITE3_INTEGER);

  $projectsSearchResult = $projectsSearchStmt->execute();

?>
