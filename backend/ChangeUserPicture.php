<?php

  require 'EnforceSession.php';

  $fileName = $_FILES['file']['name'];
  $splitFileName = explode('.', $fileName);
  $fileExt = strtolower(end($splitFileName));

  if (!in_array($fileExt, array('jpg', 'jpeg', 'png'))) {
    echo "fail";
    return;
  }

  if ($_FILES['file']['error'] !== 0) {
    echo "fail";
    return;
  }

  if ($_FILES['file']['size'] > 1000000) {
    echo "fail";
    return;
  }

  $emailSplitAddr = explode("@", $_SESSION['email']);
  $emailSplit =  explode(".", $emailSplitAddr[1]);
  $defaultPath = "images/" . $emailSplitAddr[0] . "$" . $emailSplit[0];
  $newFilePath = $defaultPath . "." . $fileExt;

  if (file_exists($defaultPath . ".png")) {
    unlink($defaultPath . ".png");
  }
  if (file_exists($defaultPath . ".jpg")) {
    unlink($defaultPath . ".jpg");
  }
  if (file_exists($defaultPath . ".jpeg")) {
    unlink($defaultPath . ".jpeg");
  }

  move_uploaded_file($_FILES['file']['tmp_name'], $newFilePath);

  echo $newFilePath;

?>
