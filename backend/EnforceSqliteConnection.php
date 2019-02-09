<?php

  $db = new SQLite3("/srv/http/NotesSite/Notes.db");
  // Sqlite refuses to keep foreign keys on.
  $db->exec("PRAGMA foreign_keys = ON");

  if (!$db) {
    // Failed to establish a connection for some reason.
    // Normally a failer to make a connection will cause a 500 error though.
    return;
  }

?>
