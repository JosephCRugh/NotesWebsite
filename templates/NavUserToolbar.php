<div id="nav-user-toolbar">
  &#9660;
  <?php echo $_SESSION['first_name'] . " " . $_SESSION['last_name']; ?>
  <?php require 'GetUserPicture.php'; ?>
  <img width="20" height="20" src="<?php echo $portraitPath; ?>">
  <ul id="nav-user-toolbar-list">
    <li><a href="UserSettings.php">Settings</a></li>
    <li id="logout-link"><a href="backend/SessionClose.php">Logout</a></li>
  </ul>
</div>
