<?php

  require 'EnforceSession.php';

  $currentPass = $_POST['currentPass'];
  $newPass = $_POST['newPass'];

  if (empty($currentPass) || empty($newPass)) {
    return;
  }

  if (strlen($currentPass) > 50 || empty($newPass) > 50) {
    return;
  }

  if ($currentPass === $newPass) {
    return;
  }

  require 'EnforceSqliteConnection.php';

  $getPassStmt = $db->prepare("SELECT password FROM user_credentials WHERE email=?");
  $getPassStmt->bindValue(1, $_SESSION['email'], SQLITE3_TEXT);

  $result = $getPassStmt->execute();
  $hashedPass = $result->fetchArray()[0];

  if (!password_verify($currentPass, $hashedPass)) {
    echo "fail";
    $db->close();
    return;
  }

  // The hashed and salted password to be stored in the database.
  $newPassHash = password_hash($newPass, PASSWORD_DEFAULT);

  $updatePassStmt = $db->prepare("UPDATE user_credentials SET password=? WHERE email=?");
  $updatePassStmt->bindValue(1, $newPassHash, SQLITE3_TEXT);
  $updatePassStmt->bindValue(2, $_SESSION['email'], SQLITE3_TEXT);

  $updatePassStmt->execute();

  echo "success";
  $db->close();

?>
