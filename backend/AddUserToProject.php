<?php

  require 'EnforceSession.php';

  $projectName = $_POST['projectName'];
  $userId = $_POST['userId'];

  if ($userId < 0) {
    return;
  }

  require 'GetProjectUserIds.php';
  $currentUserIds = getProjectProjectIds($projectName);

  // There is no reason to add the user more than once to the array.
  if (in_array($userId, explode(',',  $currentUserIds))) {
    return;
  }

  $currentUserIds .= "," . $userId;

  require 'UpdateUserProject.php';
  updateProject($projectName, "SET user_ids=?", $currentUserIds, SQLITE3_TEXT);

?>
