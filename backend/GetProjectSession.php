<?php

  foreach (getallheaders() as $name => $value) {
    if ($name === "inSessionId") {
      session_id($value);
      session_start();
      echo json_encode(array(
        "sess_id" => $_SESSION['sess_id'],
        "pageOwnerId" => $_SESSION['pageOwnerId'],
        "currentProject" => $_SESSION['currentProject'],
        "hasWriteAccess" => $_SESSION['hasWriteAccess']
      ));
    }
  }

?>
