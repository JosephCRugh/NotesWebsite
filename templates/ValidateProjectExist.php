<?php
{
  $pageOwnerId = $_GET['id'];
  $projectName = $_GET['name'];

  require 'backend/EnforceSqliteConnection.php';

  $validatePageStmt = $db->prepare("SELECT EXISTS(SELECT 1 FROM user_projects WHERE id=? AND name=?)");
  $validatePageStmt->bindValue(1, $pageOwnerId, SQLITE3_INTEGER);
  $validatePageStmt->bindValue(2, $projectName, SQLITE3_TEXT);

  $validatePageRes = $validatePageStmt->execute();
  if (!$validatePageRes->fetchArray()[0]) {
    header( 'Location: Login.php' );
  }

  $db->close();
}
?>
