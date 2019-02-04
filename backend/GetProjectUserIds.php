<?php

  function getProjectProjectIds($projectName) {
    require 'EnforceSqliteConnection.php';

    $getUserIdsStmt = $db->prepare("SELECT user_ids FROM user_projects WHERE name=? AND id=?");
    $getUserIdsStmt->bindValue(1, $projectName, SQLITE3_TEXT);
    $getUserIdsStmt->bindValue(2, $_SESSION['sess_id'], SQLITE3_INTEGER);

    $userIdsResult = $getUserIdsStmt->execute();
    $currentUserIds = $userIdsResult->fetchArray()[0];

    $db->close();
    return $currentUserIds;
  }

?>
