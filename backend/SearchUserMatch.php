<?php

  require 'EnforceSession.php';

  $firstName = $_POST["firstName"];
  $lastName = $_POST["lastName"];

  if (strlen($firstName) > 20 || empty($firstName)) {
    return;
  }

  require 'EnforceSqliteConnection.php';

  $querySearch = "SELECT user_id, first_name, last_name FROM user_credentials ";
  // Not including self in search query.
  $notSelf = " NOT user_id=" . $db->escapeString($_SESSION['user_id']);

  // Not selecting anyone by the IDs that are being limited.
  $notIds = "";
  if (isset($_POST['limitedIds'])) {
    $limitedIds = $_POST['limitedIds'];
    foreach ($limitedIds as &$limitedId) {
        $notIds .= " AND NOT user_id=" . $db->escapeString($limitedId);
    }
  }

  // If the last name was not set then only search for users based on first name.
  if ($lastName == "undefined" || empty($lastName)) {
    $querySearch .= "WHERE" .  $notSelf . $notIds . " AND first_name LIKE '" . $db->escapeString($firstName) . "%' LIMIT 5";
  } else {
    if (strlen($lastName) > 20) {
      return;
    }
    $querySearch .= "WHERE " . $notSelf . $notIds .  " AND first_name='" . $db->escapeString($firstName) . "' COLLATE NOCASE AND last_name LIKE '" . $db->escapeString($lastName) . "%' LIMIT 5";
  }

  $result = $db->query($querySearch);

  if ($result->numColumns()) {
    while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
      echo json_encode($row) . " ";
    }
  }

  $db->close();

?>
