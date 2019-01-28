<?php

  session_start();

  if (!isset($_SESSION['email'])) {
    return;
  }

  $firstName = $_POST["firstName"];
  $lastName = $_POST["lastName"];

  if ($firstName.length > 20 || empty($firstName)) {
    return;
  }

  $db = new SQLite3("/srv/http/NotesSite/Notes.db");

  if (!$db) {
    // Failed to establish a connection for some reason.
    // Normally a failer to make a connection will cause a 500 error though.
    return;
  }

  $querySearch = "SELECT id, first_name, last_name FROM user_credentials ";

  // If the last name was not set then only search for users based on first name.
  if ($lastName == "undefined" || empty($lastName)) {
    $querySearch .= "WHERE first_name LIKE '" . $db->escapeString($firstName) . "%' LIMIT 5";
  } else {
    if ($lastName.length > 20) {
      return;
    }
    $querySearch .= "WHERE first_name = '" . $db->escapeString($firstName) . "' COLLATE NOCASE AND last_name LIKE '" . $db->escapeString($lastName) . "%' LIMIT 5";
  }

  $result = $db->query($querySearch);

  if ($result->numColumns()) {
    while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
      echo json_encode($row) . " ";
    }
  }

  $db->close();

?>
