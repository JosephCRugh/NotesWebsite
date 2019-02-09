<?php

  function checkNoteEditStatus($projectId) {
    require 'RetrieveProjectsInfo.php';

    if (!isset($_SESSION)) {
      session_start();
    }

    // Just double ensuring a session exist for the user.
    if (isset($_SESSION['email'])) {
      return;
    }

    $retrievalData = getUserProjectInfoByIds($_SESSION['pageOwnerId'], $projectId);
    $projectsSearchResult = $retrievalData['projectSearchResult']->fetchArray();

    // If the page is private or public does not matter. They still require access
    // to the page.
    if (!in_array($_SESSION['user_id'], explode(",", $projectsSearchResult[4]))) {
      header( 'Location: ../NoAccessToPage.php' );
    }

    $retrievalData['database']->close();
  }

?>
