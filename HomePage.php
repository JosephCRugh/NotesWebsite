<?php require 'templates/RequireSession.php' ?>

<html>
  <head>

    <?php require 'templates/HeaderTemplate.php' ?>
    <link href="//netdna.bootstrapcdn.com/bootstrap/3.0.0/css/bootstrap-glyphicons.css" rel="stylesheet">

    <script src="js/HomePage.js"></script>
    <link rel="stylesheet" type="text/css" href="css/SharedNav.css">
    <link rel="stylesheet" type="text/css" href="css/HomePage.css">

  </head>
  <body class="home-background-color">

    <nav class="navbar navbar-expand-lg navbar-light nav-style">

      <!-- Content on the left side of the navbar -->
      <a id="nav-logo" class="navbar-brand">Notes</a>

      <!-- The navigation links to project information -->
      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav">
          <li class="nav-item active">
            <a id="nav-new-project" class="nav-link" href="NewProject.php"><span class="glyphicon glyphicon-plus"></span> New Project<span class="sr-only">(current)</span></a>
          </li>
        </ul>
      </div>

      <?php include 'templates/NavUserToolbar.php' ?>
    </nav>

    <!-- TODO: Loading in the projects that the user already has -->
    <?php ?>

    <div id="projects-pane" class="container">
      <div class="row">
        <div name="new-project" class="col-sm">
          <div >New Project<br><span class="glyphicon glyphicon-plus"></span></div>
        </div>
        <div name="new-project" class="col-sm">
          <div >New Project<br><span class="glyphicon glyphicon-plus"></span></div>
        </div>
        <div name="new-project" class="col-sm">
          <div >New Project<br><span class="glyphicon glyphicon-plus"></span></div>
        </div>
      </div>
    </div>

  </body>
</html>
