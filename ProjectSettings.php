<?php require 'templates/RequireSession.php' ?>

<?php

  require 'templates/ValidateProjectExist.php';

  // Making sure this is the user's setting's page.
  {
    if ($_SESSION['user_id'] != $_GET['uid']) {
      header( 'Location: NoAccessToPage.php' );
    }

    $_SESSION['pageOwnerId'] = $_GET['uid'];
  }

?>

<html>
  <head>

    <?php require 'templates/HeaderTemplate.php' ?>
    <link href="//netdna.bootstrapcdn.com/bootstrap/3.0.0/css/bootstrap-glyphicons.css" rel="stylesheet">

    <script src="js/SharedForm.js"></script>
    <script src="js/UserSearchBar.js"></script>
    <script src="js/UserNavModel.js"></script>
    <script src="js/ProjectSettings.js"></script>

    <link rel="stylesheet" type="text/css" href="css/ProjectSettingsShared.css">
    <link rel="stylesheet" type="text/css" href="css/UserNavModel.css">
    <link rel="stylesheet" type="text/css" href="css/SharedNav.css">
    <link rel="stylesheet" type="text/css" href="css/ProjectSettings.css">

    <?php
      require 'backend/RetrieveProjectsInfo.php';
      $retrievalData = getUserProjectInfoByIds($pageOwnerId, $_GET['pid']);
      $projectsSearchResult = $retrievalData['projectSearchResult']->fetchArray();
    ?>
    <title><?php echo $projectsSearchResult[1] . " Settings"; ?></title>

  </head>
  <body id="project-id-<?php echo $_GET['uid']; ?>" value="project-id-<?php echo $_GET['pid'] ?>" >

    <nav class="navbar navbar-expand-lg navbar-light nav-style">

      <!-- Content on the left side of the navbar -->
      <a id="nav-logo" class="navbar-brand">Notes</a>
      <div class="collapse navbar-collapse">
        <ul class="navbar-nav">
          <li class="nav-item active">
            <a id="nav-link-color" class="nav-link" href="index.php"><span class="glyphicon glyphicon-home"></span> Home<span class="sr-only">(current)</span></a>
          </li>
          <li class="nav-item active">
            <a id="nav-link-color" class="nav-link" href="ProjectPage.php?pid=<?php echo $_GET['pid']; ?>&uid=<?php echo $_GET['uid']; ?>"> Project<span class="sr-only">(current)</span></a>
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
              <h4>Project Name</h4>
              <input id="form-project-name" class="form-control input-field-look" placeholder="Change Project Name" value="<?php echo $projectsSearchResult[1]; ?>"></input>
              <button id="save-name-btn" type="button" class="btn btn-primary button-saves">Save</button>
              <span id="save-name-label-success" class="glyphicon glyphicon-ok saved-label" hidden></span><label id="save-name-span-success" class="saved-label" <?php if (!$showProjectNameSave) echo "hidden"; ?>>&nbsp;Saved</label>
            </div>
            <div class="form-group">
              <h4>Project Description</h4>
              <input id="form-project-description" class="form-control input-field-look" placeholder="Change Project Description" value="<?php echo $projectsSearchResult[2]; ?>"></input>
              <button id="save-desc-btn" type="button" class="btn btn-primary button-saves">Save</button>
              <span id="save-desc-span-success" class="glyphicon glyphicon-ok saved-label" hidden></span><label id="save-desc-label-success" class="saved-label" hidden>&nbsp;Saved</label>
            </div>
            <div class="form-group">
              <div class="radio-btn-look">
                <input type="radio" name="defaultExampleRadios" <?php echo ($projectsSearchResult[3] == 1 ? "" : "checked"); ?>>
                <label for="defaultUnchecked">Public</label>
              </div>
              <div class="radio-btn-look">
                <input id="form-private-select" type="radio" name="defaultExampleRadios" <?php echo ($projectsSearchResult[3] == 1 ? "checked" : ""); ?>>
                <label for="defaultUnchecked">private</label>
              </div>
            </div>
            <div class="form-group">
              <hr>
            </div>
            <div class="form-group">
              <label >Add/Remove users from the project</label>
            </div>
            <div class="form-group">
              <input id="form-search-users" class="form-control input-field-look user-search-margin" type="text" placeholder="Search For User">
              <div id="form-users-name-displays">
                <ul id="form-names-ul-lists">
                </ul>
              </div>
              <div id="form-added-users">
                <ul>
                  <?php

                    require 'backend/EnforceSqliteConnection.php';

                    if (!empty($projectsSearchResult[4])) {
                      $userAddedIds = explode(",", $projectsSearchResult[4]);
                      foreach ($userAddedIds as &$userId) {

                        $userStmt = $db->prepare("SELECT first_name, last_name FROM user_credentials WHERE user_id=?");
                        $userStmt->bindValue(1, $userId, SQLITE3_INTEGER);

                        $result = $userStmt->execute();
                        $userName = $result->fetchArray();

                        $conjoinedName = $userName[0] . " " . $userName[1];
                        $glyphRemove = '<span class="glyphicon glyphicon-remove"></span>';
                        echo '<li name="' . $conjoinedName . '" id="user-id-' . $userId . '"><label>' . $conjoinedName . '</label>' . $glyphRemove . '</li>';
                      }
                  }
                  $db->close();

                  ?>
                </ul>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>

    <?php $retrievalData['database']->close(); ?>

  </body>
</html>
