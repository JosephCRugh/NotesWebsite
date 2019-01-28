<?php

  session_start();

  $email = $_SESSION['email'];
  if (!isset($email)) {
    header( 'Location: Login.php' );
  }
?>
