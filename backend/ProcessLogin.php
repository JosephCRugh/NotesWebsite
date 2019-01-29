<?php

  require 'EnforceSession.php';

  $email = $_POST["email"];
  $password = $_POST["password"];

  require 'EnforceSqliteConnection.php';

  // This grabs the results from the user_credentials table but there is no data
  // currently inside the table. This should be handled by the register page!
  $statement = $db->prepare("SELECT id, email, password, first_name, last_name FROM user_credentials WHERE email=?");
  $statement->bindValue(1, $email, SQLITE3_TEXT);

  $result = $statement->execute();
  $userCredentials = $result->fetchArray();

  // Email does not exist.
  if (empty($userCredentials[1])) {
    echo "fail";
    $db->close();
    return;
  }

  if (!password_verify($password, $userCredentials[2])) {
    echo "fail";
    $db->close();
    return;
  }

  $db->close();

  $_SESSION['email'] = $email;
  $_SESSION['first_name'] = $userCredentials[3];
  $_SESSION['last_name'] = $userCredentials[4];
  $_SESSION['sess_id'] = $userCredentials[0];

  echo "success";

?>
