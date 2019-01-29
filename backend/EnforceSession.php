<?php

  session_start();

  // No reason to process any further since they are not
  // logged in.
  if (isset($_SESSION['email'])) {
    return;
  }

?>
