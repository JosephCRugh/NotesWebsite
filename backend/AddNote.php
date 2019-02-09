<?php

  require 'EnsureNoteEditAccess.php';

  $projectId = $_POST['projectId'];
  checkNoteEditStatus($projectId);

  $title = $_POST['title'];
  $posX = $_POST['posX'];
  $posY = $_POST['posY'];

  if (!isset($title) || empty($title)) {
    return;
  }

  if (strlen($title) > 20) {
    return;
  }

  require 'EnforceSqliteConnection.php';

  $newNoteStmt = $db->prepare("INSERT INTO user_notes (title, content, pos_x, pos_y, project_id, user_id) VALUES (?, ?, ?, ?, ?, ?) ");

  $newNoteStmt->bindValue(1, $title, SQLITE3_TEXT);
  $newNoteStmt->bindValue(2, "", SQLITE3_TEXT);
  $newNoteStmt->bindValue(3, $posX, SQLITE3_FLOAT);
  $newNoteStmt->bindValue(4, $posY, SQLITE3_FLOAT);
  $newNoteStmt->bindValue(5, $projectId, SQLITE3_INTEGER);
  $newNoteStmt->bindValue(6, $_SESSION['user_id'], SQLITE3_INTEGER);

  $newNoteStmt->execute();

  echo $db->lastInsertRowid();

  $db->close();

?>
