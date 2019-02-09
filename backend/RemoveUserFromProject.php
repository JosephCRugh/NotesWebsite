<?php

  require 'EnforceSession.php';

  $projectId = $_POST['projectId'];
  $userId = $_POST['userId'];

  if ($userId < 0) {
    return;
  }

  require 'GetProjectUserIds.php';
  $currentUserIds = getProjectProjectIds($projectId);
  $currentUserIdsArr = explode(',', $currentUserIds);

  unset($currentUserIdsArr[array_search($userId, $currentUserIdsArr)]);

  // Converting the array into text for the database.
  $newUserIds = "";
  foreach ($currentUserIdsArr as &$newUserId) {
    $newUserIds .= strval($newUserId) . ",";
  }
  $newUserIds = rtrim($newUserIds, ",");

  require 'UpdateUserProject.php';
  updateProject($projectId, "SET added_user_ids=?", $newUserIds, SQLITE3_TEXT);

?>
