<?php

  require 'EnforceSession.php';

  $projectName = $_POST['projectName'];
  $userId = $_POST['userId'];

  if ($userId < 0) {
    return;
  }

  require 'GetProjectUserIds.php';
  $currentUserIds = getProjectProjectIds($projectName);
  $currentUserIdsArr = explode(',', $currentUserIds);

  unset($currentUserIdsArr[array_search($userId, $currentUserIdsArr)]);

  // Converting the array into text for the database.
  $newUserIds = "";
  foreach ($currentUserIdsArr as &$newUserId) {
    $newUserIds .= strval($newUserId) . ",";
  }
  $newUserIds = rtrim($newUserIds, ",");

  require 'UpdateUserProject.php';
  updateProject($projectName, "SET user_ids=?", $newUserIds, SQLITE3_TEXT);

?>
