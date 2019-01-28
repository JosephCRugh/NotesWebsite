<?php

  session_start();

  // No reason to process a login request if the user is
  // already logged in.
  if (isset($_SESSION['email'])) {
    return;
  }

  $email = $_POST["email"];
  $password = $_POST["password"];

  $db = new SQLite3("/srv/http/NotesSite/Notes.db");

  if (!$db) {
    // Failed to establish a connection for some reason.
    // Normally a failer to make a connection will cause a 500 error though.
    return;
  }

  // This grabs the results from the user_credentials table but there is no data
  // currently inside the table. This should be handled by the register page!
  $statement = $db->prepare("SELECT email, password, first_name, last_name FROM user_credentials WHERE email=?");
  $statement->bindValue(1, $email, SQLITE3_TEXT);

  $result = $statement->execute();
  $userCredentials = $result->fetchArray();

  // Email does not exist.
  if (empty($userCredentials[0])) {
    echo "fail";
    return;
  }

  if (!password_verify($password, $userCredentials[1])) {
    echo "fail";
    return;
  }

  $db->close();

  $_SESSION['email'] = $email;
  $_SESSION['first_name'] = $userCredentials[2];
  $_SESSION['last_name'] = $userCredentials[3];
  echo "success";

?>
