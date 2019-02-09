<?php

  foreach (getallheaders() as $name => $value) {
    if ($name === "inSessionId") {
      session_id($value);
      session_start();
      echo json_encode(array(
        "user_id" => $_SESSION['user_id'],
        "pageOwnerId" => $_SESSION['pageOwnerId'],
        "currentProjectId" => $_SESSION['currentProjectId'],
        "hasWriteAccess" => $_SESSION['hasWriteAccess']
      ));
    }
  }

?>
