<?php

  function getProjectProjectIds($projectId) {
    require 'EnforceSqliteConnection.php';

    $getUserIdsStmt = $db->prepare("SELECT added_user_ids FROM user_projects WHERE project_id=? AND user_id=?");
    $getUserIdsStmt->bindValue(1, $projectId, SQLITE3_INTEGER);
    $getUserIdsStmt->bindValue(2, $_SESSION['user_id'], SQLITE3_INTEGER);

    $userIdsResult = $getUserIdsStmt->execute();
    $currentUserIds = $userIdsResult->fetchArray()[0];

    $db->close();
    return $currentUserIds;
  }

?>
