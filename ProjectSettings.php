<?php require 'templates/RequireSession.php' ?>

<?php

  require 'templates/ValidateProjectExist.php';

  // Making sure this is the user's setting's page.
  {
    if ($_SESSION['sess_id'] != $_GET['id']) {
      header( 'Location: NoAccessToPage.php' );
    }
  }

?>

<html>
  <head>

    <?php require 'templates/HeaderTemplate.php' ?>
    <link href="//netdna.bootstrapcdn.com/bootstrap/3.0.0/css/bootstrap-glyphicons.css" rel="stylesheet">

    <script src="js/SharedForm.js"></script>
    <script src="js/UserNavModel.js"></script>

    <link rel="stylesheet" type="text/css" href="css/UserNavModel.css">
    <link rel="stylesheet" type="text/css" href="css/SharedNav.css">

    <title><?php echo $_GET['name']; ?></title>

  </head>
  <body>

    <nav class="navbar navbar-expand-lg navbar-light nav-style">

      <!-- Content on the left side of the navbar -->
      <a id="nav-logo" class="navbar-brand">Notes</a>
      <div class="collapse navbar-collapse">
        <ul class="navbar-nav">
          <li class="nav-item active">
            <a id="nav-link-color" class="nav-link" href="HomePage.php"><span class="glyphicon glyphicon-home"></span> Home<span class="sr-only">(current)</span></a>
          </li>
          <li class="nav-item active">
            <a id="nav-link-color" class="nav-link" href="ProjectPage.php?name=<?php echo $_GET['name']; ?>&id=<?php echo $_GET['id']; ?>"> Project<span class="sr-only">(current)</span></a>
          </li>
        </ul>
      </div>

      <?php include 'templates/NavUserToolbar.php' ?>
    </nav>

    <div class="container">
      <div class="row">
        <div class="col-4">
          <h2>Change Project Settings</h2><br>
          <form>
            <div class="form-group">
              <input class="form-control" placeholder="Change Project Name"></input>
              <button type="button" class="btn btn-primary">Save</button>
            </div>
          </form>
        </div>
      </div>
    </div>

  </body>
</html>
