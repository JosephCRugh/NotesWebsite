<?php require 'templates/RequireSession.php' ?>

<html>
  <head>

    <?php require 'templates/HeaderTemplate.php' ?>
    <link href="//netdna.bootstrapcdn.com/bootstrap/3.0.0/css/bootstrap-glyphicons.css" rel="stylesheet">

    <script src="js/SharedForm.js"></script>
    <script src="js/UserNavModel.js"></script>
    <script src="js/UserSettings.js"></script>

    <link rel="stylesheet" type="text/css" href="css/UserNavModel.css">
    <link rel="stylesheet" type="text/css" href="css/SharedNav.css">
    <link rel="stylesheet" type="text/css" href="css/UserSettings.css">

    <title><?php echo "User Settings"; ?></title>

  </head>

  <nav class="navbar navbar-expand-lg navbar-light nav-style">

    <!-- Content on the left side of the navbar -->
    <a id="nav-logo" class="navbar-brand">Notes</a>
    <div class="collapse navbar-collapse">
      <ul class="navbar-nav">
        <li class="nav-item active">
          <a id="nav-link-color" class="nav-link" href="index.php"><span class="glyphicon glyphicon-home"></span> Home<span class="sr-only">(current)</span></a>
        </li>
      </ul>
    </div>

    <?php include 'templates/NavUserToolbar.php' ?>
  </nav>

  <div class="container">
    <div class="row">
      <div class="col-4">
        <h2>Change User Settings</h2><br>
        <form>
          <div class="form-group">
            <?php require 'templates/GetUserPicture.php'; ?>
            <image id="display-picture" width="100" height="100" src="<?php echo $portraitPath; ?>"></image>
            <input id="picture-upload" style="display: none;" class="form-control-file" type="file" name="pic" accept="image/*">
            <input class="btn btn-primary btn-save" type="button" value="Upload Picture" onclick="document.getElementById('picture-upload').click();"></input>
          </div>
          <hr>
          <div class="form-group">
            <label>Change Password</label>
            <input id="current-pass-input" type="password" class="form-control input-field-look input-look" placeholder="Current Password"></input>
            <input id="new-pass-input" type="password" class="form-control input-field-look input-look" placeholder="New Password"></input>
            <button id="submit-new-password" type="button" class="btn btn-primary btn-save">Save</button>
            <span id="saved-pass-span" class="glyphicon glyphicon-ok saved-label" hidden><label id="saved-pass-label" hidden>Saved</label>
          </div>
        </form>
      </div>
    </div>
  </div>

</html>
