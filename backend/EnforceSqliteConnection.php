<?php

  $db = new SQLite3("/srv/http/NotesSite/Notes.db");

  if (!$db) {
    // Failed to establish a connection for some reason.
    // Normally a failer to make a connection will cause a 500 error though.
    return;
  }

?>
