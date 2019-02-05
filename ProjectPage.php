<?php require 'templates/RequireSession.php' ?>

<?php

  require 'templates/ValidateProjectExist.php';

  $projectDesc = "";
  // Making sure the user has access to view this page.
  {
    require 'backend/RetrieveProjectsInfo.php';

    $retrievalData = getUserProjectInfoByName($pageOwnerId, $_GET['name']);
    $projectsSearchResult = $retrievalData['projectSearchResult']->fetchArray();

    $sessionId = $_SESSION['sess_id'];
    $hasWriteAccess = false;
    if ($sessionId !== $projectsSearchResult[0]) {

      $isAddedToProject = in_array($sessionId, explode(",", $projectsSearchResult[4]));

      if ($projectsSearchResult[3] == "1") {
        if (!$isAddedToProject) {
          header( 'Location: NoAccessToPage.php' );
        }
        $hasWriteAccess = true;
      } else {
        $hasWriteAccess = $isAddedToProject;
      }
    } else {
      $hasWriteAccess = true;
    }

    // Since they have access to the page we are going to store the page owner id.
    $_SESSION['pageOwnerId'] = $_GET['id'];
    $projectDesc = $projectsSearchResult[2];

    $retrievalData['database']->close();
  }

?>

<html>
  <head>

    <?php require 'templates/HeaderTemplate.php' ?>
    <link href="//netdna.bootstrapcdn.com/bootstrap/3.0.0/css/bootstrap-glyphicons.css" rel="stylesheet">

    <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/jquery-ui.min.js" type="text/javascript"></script>

    <script src="js/SharedForm.js"></script>
    <script src="js/UserNavModel.js"></script>
    <script src="js/ProjectPage.js"></script>

    <link rel="stylesheet" type="text/css" href="css/UserNavModel.css">
    <link rel="stylesheet" type="text/css" href="css/SharedNav.css">
    <link rel="stylesheet" type="text/css" href="css/ProjectPage.css">

    <title><?php echo $_GET['name']; ?></title>

  </head>
  <body class="home-background-color" value="<?php echo "has-write-access-" . ($hasWriteAccess ? "true" : "false"); ?>">

    <nav class="navbar navbar-expand-lg navbar-light nav-style">

      <!-- Content on the left side of the navbar -->
      <a id="nav-logo" class="navbar-brand">Notes</a>
      <div class="collapse navbar-collapse">
        <ul class="navbar-nav">
          <li class="nav-item active">
            <a id="nav-link-color" class="nav-link" href="HomePage.php"><span class="glyphicon glyphicon-home"></span> Home<span class="sr-only">(current)</span></a>
          </li>
          <?php
            // Display a settings option for the owner.
            if ($_SESSION['pageOwnerId'] == $_SESSION['sess_id']) {
              echo '<li class="nav-item active">' .
                      '<a id="nav-link-color" class="nav-link" href="ProjectSettings.php?name=' . $_GET['name'] . '&id=' . $_GET['id'] . '"> Project Settings</a>' .
                    '</li>';
            }
          ?>
        </ul>
      </div>

      <?php include 'templates/NavUserToolbar.php' ?>
    </nav>

    <div id="notes-action-buttons">
      <button id="add-note-button" type="button" class="btn btn-primary">Add Note</button>
      <input id="note-search-input" type="text" class="form-control" placeholder="Search For Note"></input>
      <p style="display: inline;"><?php echo $projectDesc; ?></p>
    </div>

    <div id="notes-container">
      <?php

        // Loading in the already existing notes for the page.
        require 'backend/EnforceSqliteConnection.php';

        $notesQueryStmt = $db->prepare("SELECT * FROM notes WHERE owner_id=? AND project_name=?");
        $notesQueryStmt->bindValue(1, $pageOwnerId, SQLITE3_INTEGER);
        $notesQueryStmt->bindValue(2, $_GET['name'], SQLITE3_TEXT);

        $notesResult = $notesQueryStmt->execute();

        while ($row = $notesResult->fetchArray()) {
          echo '<div class="notes-style" style="left: ' . $row[5] . '; top: ' . $row[6] . ';" id="note-' . $row[0] . '">' .
            '<div>' .
              '<h3>' . $row[2] . '</h3>' .
              '<span name="title-edit" class="glyphicon glyphicon-pencil"></span>' .
              '<input type="text" class="form-control"></input>' .
            '</div>' .
            '<textarea class="form-control z-depth-1" ' . ($hasWriteAccess ? '' : 'disabled="disabled"') . '>' . $row[3] . '</textarea>' .
            '<div class="notes-bottom">' .
              '<button type="text" class="btn btn-primary" style="width: 100%;" hidden>Finish Edit</button>' .
              '<span name="notes-delete" class="glyphicon glyphicon-trash"></span>' .
            '</div>' .
          '</div>';
        }

        $db->close();

      ?>
    </div>

    <div class="gray-overlay" hidden></div>

    <!-- Deletion selection div -->
    <div id="delete-option" hidden>
      <button name="do-delete" type="button" class="btn btn-danger">Yes Delete</button>
      <button name="no-delete" type="button" class="btn btn-primary">No Don't!</button>
    </div>

  </body>
</html>
