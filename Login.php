<?php

  session_start();

  if (isset($_SESSION['email'])) {
    header( 'Location: HomePage.php' );
  }
?>

<html>
  <head>

    <?php require 'templates/HeaderTemplate.php' ?>

    <link rel="stylesheet" type="text/css" href="css/SharedHome.css">
    <link rel="stylesheet" type="text/css" href="css/Login.css">
    <script src="js/SharedForm.js"></script>
    <script src="js/Login.js"></script>

    <title>Notes Login</title>
  </head>
  <body class="background-gradient">

    <div class="container">
        <div class="row justify-content-center align-items-center" style="height:90vh">
          <div class="col-3">
            <form class="form-look" autocomplete="off">
              <div>
                <label>SIGN IN</label>
              </div>
              <div class="form-group">
                <input type="text" class="form-control" placeholder="Email" id="login-email">
              </div>
              <div class="form-group">
                <input type="password" class="form-control" name="password" placeholder="Password" id="login-password">
              </div>
              <button type="button" class="btn btn-primary" id="process-login-button">LOG IN</button>
              <hr class="seperation-bar">
              <p>Do not have an account?</p>
              <a href="Register.php">Click Here</a>
            </form>
          </div>
        </div>
      </div>

  </body>
</html>
