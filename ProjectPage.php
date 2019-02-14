<?php require 'templates/RequireSession.php' ?>

<?php

  require 'templates/ValidateProjectExist.php';

  $projectDesc = "";
  // Making sure the user has access to view this page.
  {
    require 'backend/RetrieveProjectsInfo.php';

    $retrievalData = getUserProjectInfoByIds($pageOwnerId, $_GET['pid']);
    $projectsSearchResult = $retrievalData['projectSearchResult']->fetchArray();

    $sessionId = $_SESSION['user_id'];
    $hasWriteAccess = false;
    if ($sessionId !== $projectsSearchResult[5]) {

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
    $_SESSION['pageOwnerId'] = $_GET['uid'];
    $_SESSION['currentProjectId'] = $_GET['pid'];
    $_SESSION['hasWriteAccess'] = $hasWriteAccess;
    $projectDesc = $projectsSearchResult[2];
    $projectName = $projectsSearchResult[1];

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

    <title><?php echo $projectName; ?></title>

  </head>
  <body id="project-id-<?php echo $_SESSION['currentProjectId']; ?>" class="home-background-color" value="<?php echo "has-write-access-" . ($hasWriteAccess ? "true" : "false"); ?>">

    <div id="sock-address" value="<?php
      $addressFile = fopen("WebSocketAddress", "r") or die("Failed to read websocket address.");
      echo fread($addressFile, filesize("WebSocketAddress"));
      fclose($addressFile);
    ?>"></div>

    <nav class="navbar navbar-expand-lg navbar-light nav-style">

      <!-- Content on the left side of the navbar -->
      <a id="nav-logo" class="navbar-brand">Notes</a>
      <div class="collapse navbar-collapse">
        <ul class="navbar-nav">
          <li class="nav-item active">
            <a id="nav-link-color" class="nav-link" href="index.php"><span class="glyphicon glyphicon-home"></span> Home<span class="sr-only">(current)</span></a>
          </li>
          <?php
            // Display a settings option for the owner.
            if ($_SESSION['pageOwnerId'] == $_SESSION['user_id']) {
              echo '<li class="nav-item active">' .
                      '<a id="nav-link-color" class="nav-link" href="ProjectSettings.php?pid=' . $_GET['pid'] . '&uid=' . $_GET['uid'] . '"> Project Settings</a>' .
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

        $notesQueryStmt = $db->prepare("SELECT * FROM user_notes WHERE user_id=? AND project_id=?");
        $notesQueryStmt->bindValue(1, $pageOwnerId, SQLITE3_INTEGER);
        $notesQueryStmt->bindValue(2, $_GET['pid'], SQLITE3_INTEGER);

        $notesResult = $notesQueryStmt->execute();

        while ($row = $notesResult->fetchArray()) {
          echo '<div class="notes-style" style="left: ' . $row[3] . '; top: ' . $row[4] . ';" id="note-' . $row[0] . '">' .
            '<div>' .
              '<h3>' . $row[1] . '</h3>' .
              '<span name="title-edit" class="glyphicon glyphicon-pencil"></span>' .
              '<input type="text" class="form-control" hidden></input>' .
            '</div>' .
            '<textarea class="form-control z-depth-1" ' . ($hasWriteAccess ? '' : 'disabled="disabled"') . '>' . $row[2] . '</textarea>' .
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
