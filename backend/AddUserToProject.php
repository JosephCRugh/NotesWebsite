<?php

  require 'EnforceSession.php';

  $projectId = $_POST['projectId'];
  $userId = $_POST['userId'];

  if ($userId < 0) {
    return;
  }

  require 'GetProjectUserIds.php';
  $currentUserIds = getProjectProjectIds($projectId);

  // There is no reason to add the user more than once to the array.
  if (in_array($userId, explode(',',  $currentUserIds))) {
    return;
  }

  if (empty($currentUserIds)) {
      $currentUserIds = $userId;
  } else {
    $currentUserIds .= "," . $userId;
  }

  require 'UpdateUserProject.php';
  updateProject($projectId, "SET added_user_ids=?", $currentUserIds, SQLITE3_TEXT);

?>
