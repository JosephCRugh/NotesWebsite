<?php require 'templates/RequireSession.php' ?>

<html>
  <head>

    <?php require 'templates/HeaderTemplate.php' ?>
    <link href="//netdna.bootstrapcdn.com/bootstrap/3.0.0/css/bootstrap-glyphicons.css" rel="stylesheet">

    <script src="js/HomePage.js"></script>
    <script src="js/UserNavModel.js"></script>

    <link rel="stylesheet" type="text/css" href="css/UserNavModel.css">
    <link rel="stylesheet" type="text/css" href="css/SharedNav.css">
    <link rel="stylesheet" type="text/css" href="css/HomePage.css">

  </head>
  <body class="home-background-color">

    <nav class="navbar navbar-expand-lg navbar-light nav-style">

      <!-- Content on the left side of the navbar -->
      <a id="nav-logo" class="navbar-brand">Notes</a>

      <!-- The navigation links to project information -->
      <div class="collapse navbar-collapse">
        <ul class="navbar-nav">
          <li class="nav-item active">
            <a id="nav-link-color" class="nav-link" href="NewProject.php"><span class="glyphicon glyphicon-plus"></span> New Project<span class="sr-only">(current)</span></a>
          </li>
        </ul>
      </div>

      <?php include 'templates/NavUserToolbar.php' ?>
    </nav>

    <div id="projects-pane" class="container">
      <div class="row">
        <div class="arrow-container" name="left-arrow">
          <span class="glyphicon glyphicon-chevron-left"></span>
        </div>
        <?php
          // Loading in the users projects.

          require "backend/RetrieveProjectsInfo.php";

          $retrievalData = getUserProjectInfo($_SESSION['user_id']);
          $projectsSearchResult = $retrievalData['projectSearchResult'];

          // TODO: Add functionality to allow the user to see the hidden projects
          // They have.
          $count = 0;
          while ($row = $projectsSearchResult->fetchArray()) {
            echo '<div name="existing-project" id=form-user-' . $row[5] . '-' . $row[0] . ' class="col-sm"'
            . ($count < 3 ? '' : 'hidden') . '>
              <div >' . $row[1] . '</div>
              <div class="trash-section">
                <span class="glyphicon glyphicon-trash"></span>
              </div>
            </div>';
            $count++;
          }

          // Filling in with blank projects if the user does not currently have
          // at least 3 projects.
          for ($x = $count; $x < 3; $x++) {
            echo '<div name="new-project" class="col-sm">
              <div >New Project<br><span class="glyphicon glyphicon-plus"></span></div>
            </div>';
          }

          $retrievalData['database']->close();

        ?>
        <div class="arrow-container" name="right-arrow">
          <span class="glyphicon glyphicon-chevron-right"></span>
        </div>
      </div>
    </div>

    <div class="gray-overlay" hidden></div>

    <div id="delete-option" hidden>
      <button name="do-delete" type="button" class="btn btn-danger">Yes Delete</button>
      <button name="no-delete" type="button" class="btn btn-primary">No Don't!</button>
    </div>

  </body>
</html>
