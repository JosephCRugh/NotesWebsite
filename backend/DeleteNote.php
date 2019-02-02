<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

  require 'EnsureNoteEditAccess.php';

  $projectName = $_POST['projectName'];
  checkNoteEditStatus($projectName);

  $noteId = $_POST['noteId'];

  require 'EnforceSqliteConnection.php';

  echo "deleting a note with id = " . $noteId . " and a name of = " . $projectName;

  $changeNoteStmt = $db->prepare("DELETE FROM notes WHERE id=? AND owner_id=? AND project_name=?");
  $changeNoteStmt->bindValue(1, $noteId, SQLITE3_INTEGER);
  $changeNoteStmt->bindValue(2, $_SESSION['pageOwnerId'], SQLITE3_INTEGER);
  $changeNoteStmt->bindValue(3, $projectName, SQLITE3_TEXT);

  $changeNoteStmt->execute();

  $db->close();

?>
