<?php
{
  $pageOwnerId = $_GET['uid'];
  $projectId = $_GET['pid'];

  require 'backend/EnforceSqliteConnection.php';

  $validatePageStmt = $db->prepare("SELECT EXISTS(SELECT 1 FROM user_projects WHERE user_id=? AND project_id=?)");
  $validatePageStmt->bindValue(1, $pageOwnerId, SQLITE3_INTEGER);
  $validatePageStmt->bindValue(2, $projectId, SQLITE3_INTEGER);

  $validatePageRes = $validatePageStmt->execute();
  if (!$validatePageRes->fetchArray()[0]) {
    header( 'Location: Login.php' );
  }

  $db->close();
}
?>
