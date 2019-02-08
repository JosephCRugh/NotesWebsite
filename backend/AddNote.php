<?php

  require 'EnsureNoteEditAccess.php';

  $projectName = $_POST['projectName'];
  checkNoteEditStatus($projectName);

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

  $largestNoteIdStmt = $db->Prepare("SELECT MAX(id) FROM notes WHERE owner_id=? AND project_name=?");
  $largestNoteIdStmt->bindValue(1, $_SESSION['pageOwnerId'], SQLITE3_INTEGER);
  $largestNoteIdStmt->bindValue(2, $projectName, SQLITE3_TEXT);

  $largestNoteIdResult = $largestNoteIdStmt->execute();
  $maxId = $largestNoteIdResult->fetchArray()[0];

  $insertStr = "INSERT INTO notes (id, owner_id, title, content, project_name, offset_x, offset_y) VALUES (?, ?, ?, ?, ?, ?, ?)";

  // Checking to see if the user currently has any notes.
  $newNoteStmt = $db->prepare($insertStr);
  if (isset($maxId)) {

    echo ($maxId + 1);
    $newNoteStmt->bindValue(1, $maxId + 1, SQLITE3_INTEGER);

  } else {

    echo "0";
    $newNoteStmt->bindValue(1, 0, SQLITE3_INTEGER);

  }

  $newNoteStmt->bindValue(2, intval($_SESSION['sess_id']), SQLITE3_INTEGER);
  $newNoteStmt->bindValue(3, $title, SQLITE3_TEXT);
  $newNoteStmt->bindValue(4, "", SQLITE3_TEXT);
  $newNoteStmt->bindValue(5, $projectName, SQLITE3_TEXT);
  $newNoteStmt->bindValue(6, $posX, SQLITE3_FLOAT);
  $newNoteStmt->bindValue(7, $posY, SQLITE3_FLOAT);

  $newNoteStmt->execute();

  $db->close();

?>
