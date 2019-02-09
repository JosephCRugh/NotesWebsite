<?php

    function getUserProjectInfoByIds($userId, $projectId) {

      require 'EnforceSession.php';
      require 'EnforceSqliteConnection.php';

      $projectsSearchStmt = $db->prepare("SELECT * FROM user_projects WHERE user_id=? AND project_id=?");
      $projectsSearchStmt->bindValue(1, $userId, SQLITE3_INTEGER);
      $projectsSearchStmt->bindValue(2, $projectId, SQLITE3_TEXT);

      return array(
        "projectSearchResult" => $projectsSearchStmt->execute(),
        "database" => $db
      );
    }

    function getUserProjectInfo($userId) {

      require 'EnforceSession.php';
      require 'EnforceSqliteConnection.php';

      $projectsSearchStmt = $db->prepare("SELECT * FROM user_projects WHERE user_id=?");
      $projectsSearchStmt->bindValue(1, $userId, SQLITE3_INTEGER);

      return array(
        "projectSearchResult" => $projectsSearchStmt->execute(),
        "database" => $db
      );
    }

?>
