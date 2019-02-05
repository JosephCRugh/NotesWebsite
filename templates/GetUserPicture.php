<?php

    $emailSplitAddr = explode("@", $_SESSION['email']);
    $emailSplit =  explode(".", $emailSplitAddr[1]);
    $defaultPath = "backend/images/" . $emailSplitAddr[0] . "$" . $emailSplit[0];

    $portraitPath = "backend/images/default.png";
    if (file_exists($defaultPath . ".png")) {
      $portraitPath = $defaultPath . ".png";
    }
    if (file_exists($defaultPath . ".jpg")) {
      $portraitPath = $defaultPath . ".jpg";
    }
    if (file_exists($defaultPath . ".jpeg")) {
      $portraitPath = $defaultPath . ".jpeg";
    }
?>
