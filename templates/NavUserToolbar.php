<div id="nav-user-toolbar">
  &#9660;
  <?php echo $_SESSION['first_name'] . " " . $_SESSION['last_name']; ?>
  <?php
        $emailSplit = explode("@", $email);
        $portraitPath = "backend/images/" . $emailSplit[0] . $emailSplit[1] . ".png";
        if (!file_exists($portraitPath)) {
          $portraitPath = "backend/images/default.png";
        }
  ?>
  <img width="20" height="20" src="<?php echo $portraitPath; ?>">
</div>
