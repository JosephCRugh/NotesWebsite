<?php

  require 'EnforceSession.php';

  $firstName = $_POST["firstName"];
  $lastName = $_POST["lastName"];

  if ($firstName.length > 20 || empty($firstName)) {
    return;
  }

  require 'EnforceSqliteConnection.php';

  $querySearch = "SELECT id, first_name, last_name FROM user_credentials ";
  // Not including self in search query.
  $notSelf = " NOT id=" . $db->escapeString($_SESSION['sess_id']);

  // If the last name was not set then only search for users based on first name.
  if ($lastName == "undefined" || empty($lastName)) {
    $querySearch .= "WHERE" .  $notSelf . " AND first_name LIKE '" . $db->escapeString($firstName) . "%' LIMIT 5";
  } else {
    if ($lastName.length > 20) {
      return;
    }
    $querySearch .= "WHERE " . $notSelf .  " AND first_name='" . $db->escapeString($firstName) . "' COLLATE NOCASE AND last_name LIKE '" . $db->escapeString($lastName) . "%' LIMIT 5";
  }

  $result = $db->query($querySearch);

  if ($result->numColumns()) {
    while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
      echo json_encode($row) . " ";
    }
  }

  $db->close();

?>
