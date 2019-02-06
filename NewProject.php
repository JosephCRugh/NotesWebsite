<?php require 'templates/RequireSession.php' ?>

<html>
  <head>

    <?php require 'templates/HeaderTemplate.php' ?>
    <link href="//netdna.bootstrapcdn.com/bootstrap/3.0.0/css/bootstrap-glyphicons.css" rel="stylesheet">

    <script src="js/SharedForm.js"></script>
    <script src="js/UserSearchBar.js"></script>
    <script src="js/UserNavModel.js"></script>
    <script src="js/NewProject.js"></script>

    <link rel="stylesheet" type="text/css" href="css/ProjectSettingsShared.css">
    <link rel="stylesheet" type="text/css" href="css/UserNavModel.css">
    <link rel="stylesheet" type="text/css" href="css/SharedNav.css">
    <link rel="stylesheet" type="text/css" href="css/NewProject.css">

    <title>New Project</title>
  </head>
  <body>

    <nav class="navbar navbar-expand-lg navbar-light nav-style">

      <!-- Content on the left side of the navbar -->
      <a id="nav-logo" class="navbar-brand">Notes</a>

      <!-- Navigation link to home page -->
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
          <form>
            <div class="form-group">
              <label id="create-project-header">Create New Project</label>
            </div>
            <div class="form-group">
              <input class="form-control input-field-look" id="form-project-name" type="text" placeholder="Project Name">
            </div>
            <div class="form-group">
              <input class="form-control input-field-look" id="form-project-description" type="text" placeholder="Project Description (Optional)">
            </div>
            <div class="radio-btn-look">
              <input type="radio" name="defaultExampleRadios">
              <label for="defaultUnchecked">Public</label>
            </div>
            <div class="radio-btn-look">
              <input id="form-private-select" type="radio" name="defaultExampleRadios" checked>
              <label for="defaultUnchecked">Private</label>
            </div>
            <div class="form-group">
              <hr>
            </div>
            <div class="form-group">
              <label >Add Other Users To The Project</label>
            </div>
          </form>
          <div class="form-group">
            <input id="form-search-users" class="form-control input-field-look" type="text" placeholder="Search For User">
          </div>
          <div id="form-bottom" class="row">
            <div>
              <div id="form-users-name-displays">
                <ul id="form-names-ul-lists">
                </ul>
              </div>
              <div id="form-added-users">
                <ul></ul>
              </div>
              <button id="submit-create-project" type="button" class="btn btn-primary">Create</button>
            </div>
          </div>
        </div>
      </div>
    </div>

  </body>
</html>
