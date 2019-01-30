<?php

    function getUserProjectInfo($sessId) {

      require 'EnforceSession.php';
      require 'EnforceSqliteConnection.php';

      $projectsSearchStmt = $db->prepare("SELECT * FROM user_projects WHERE id=?");
      $projectsSearchStmt->bindValue(1, $sessId, SQLITE3_INTEGER);

      return array(
        "projectSearchResult" => $projectsSearchStmt->execute(),
        "database" => $db
      );
    }

?>
