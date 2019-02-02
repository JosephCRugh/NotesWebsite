<?php

    function getUserProjectInfoByName($sessId, $projectName) {

      require 'EnforceSession.php';
      require 'EnforceSqliteConnection.php';

      $projectsSearchStmt = $db->prepare("SELECT * FROM user_projects WHERE id=? AND name=?");
      $projectsSearchStmt->bindValue(1, $sessId, SQLITE3_INTEGER);
      $projectsSearchStmt->bindValue(2, $projectName, SQLITE3_TEXT);

      return array(
        "projectSearchResult" => $projectsSearchStmt->execute(),
        "database" => $db
      );
    }

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
