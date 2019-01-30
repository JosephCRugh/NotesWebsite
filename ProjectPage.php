<?php require 'templates/RequireSession.php' ?>

<?php

  // Validating that the and project name exist.
  {
    $pageOwnerId = $_GET['id'];
    $projectName = $_GET['name'];

    require 'backend/EnforceSqliteConnection.php';

    $validatePageStmt = $db->prepare("SELECT EXISTS(SELECT 1 FROM user_projects WHERE id=? AND name=?)");
    $validatePageStmt->bindValue(1, $pageOwnerId, SQLITE3_INTEGER);
    $validatePageStmt->bindValue(2, $projectName, SQLITE3_TEXT);

    $validatePageRes = $validatePageStmt->execute();
    if (!$validatePageRes->fetchArray()[0]) {
      header( 'Location: Login.php' );
    }

    $db->close();
  }

  // Making sure the user has access to view this page.
  {
    require 'backend/RetrieveProjectsInfo.php';

    $retrievalData = getUserProjectInfo($pageOwnerId);
    $projectsSearchResult = $retrievalData['projectSearchResult']->fetchArray();

    $sessionId = $_SESSION['sess_id'];
    if ($sessionId !== $projectsSearchResult[0]) {
      if ($projectsSearchResult[3] === 1) {
        if (!in_array($sessionId, explode(",", $projectsSearchResult[4]))) {
          header( 'Location: NoAccessToPage.php' );
        }
      }
    }

    $retrievalData['database']->close();
  }

?>

<html>
  <head>

    <?php require 'templates/HeaderTemplate.php' ?>
    <link href="//netdna.bootstrapcdn.com/bootstrap/3.0.0/css/bootstrap-glyphicons.css" rel="stylesheet">

    <script src="js/UserNavModel.js"></script>

    <link rel="stylesheet" type="text/css" href="css/UserNavModel.css">
    <link rel="stylesheet" type="text/css" href="css/SharedNav.css">
    <link rel="stylesheet" type="text/css" href="css/ProjectPage.css">

  </head>
  <body class="home-background-color">

    <nav class="navbar navbar-expand-lg navbar-light nav-style">

      <!-- Content on the left side of the navbar -->
      <a id="nav-logo" class="navbar-brand">Notes</a>

      <div class="collapse navbar-collapse">
        <ul class="navbar-nav">
          <li class="nav-item active">
            <a id="nav-homepage" class="nav-link" href="HomePage.php"><span class="glyphicon glyphicon-home"></span> Home<span class="sr-only">(current)</span></a>
          </li>
        </ul>
      </div>

      <?php include 'templates/NavUserToolbar.php' ?>

    </nav>

  </body>
</html>
