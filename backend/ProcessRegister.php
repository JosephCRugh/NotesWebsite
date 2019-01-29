<?php

  require 'EnforceSession.php';

  $firstName = $_POST["firstName"];
  $lastName = $_POST["lastName"];
  $email = $_POST["email"];
  $password = $_POST["password"];

  if (empty($firstName)) {
    return;
  }

  if (empty($lastName)) {
    return;
  }

  if (empty($email)) {
    return;
  }

  if (empty($password)) {
    return;
  }

  if (strlen($firstName) > 20) {
    return;
  }

  if (strlen($lastName) > 20) {
    return;
  }

  if (strlen($email) > 250) {
    return;
  }

  if (strlen($password) > 50) {
    return;
  }

  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    return;
  }

  if (!preg_match("/^[a-zA-Z]+$/", $firstName)){
    return;
  }

  if (!preg_match("/^[a-zA-Z]+$/", $lastName)){
    return;
  }

  require 'EnforceSqliteConnection.php';

  // Making sure the email isn't taken.
  $emailSearchStmt = $db->prepare("SELECT EXISTS(SELECT 1 FROM user_credentials WHERE email=?)");
  $emailSearchStmt->bindValue(1, $email, SQLITE3_TEXT);

  $result = $emailSearchStmt->execute();

  if ($result->fetchArray()[0]) {
    // Send information to the user telling them the email is taken.
    echo "fail";
    $db->close();
    return;
  }

  // The hashed and salted password to be stored in the database.
  $passHash = password_hash($password, PASSWORD_DEFAULT);

  $newUserStatement = $db->prepare("INSERT INTO user_credentials (email, password, first_name, last_name) VALUES (?, ?, ?, ?)");
  $newUserStatement->bindValue(1, $email, SQLITE3_TEXT);
  $newUserStatement->bindValue(2, $passHash, SQLITE3_TEXT);
  $newUserStatement->bindValue(3, $firstName, SQLITE3_TEXT);
  $newUserStatement->bindValue(4, $lastName, SQLITE3_TEXT);

  $newUserStatement->execute();

  $selectIdStatement = $db->prepare("SELECT id FROM user_credentials WHERE email=?");
  $selectIdStatement->bindValue(1, $email, SQLITE3_TEXT);

  $idResult = $selectIdStatement->execute();

  $_SESSION['email'] = $email;
  $_SESSION['first_name'] = $firstName;
  $_SESSION['last_name'] = $lastName;
  $_SESSION['sess_id'] = $idResult->fetchArray()[0];

  $db->close();
  echo "success";

?>
