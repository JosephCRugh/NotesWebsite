<?php

  session_start();

  if (isset($_SESSION['email'])) {
    header( 'Location: index.php' );
  }
?>

<html>
  <head>

    <?php require 'templates/HeaderTemplate.php' ?>

    <link rel="stylesheet" type="text/css" href="css/SharedHome.css">
    <script src="js/SharedForm.js"></script>
    <script src="js/Register.js"></script>

    <title>Notes Register</title>
  </head>

  <body class="background-gradient">

    <div class="container">
      <div class="row justify-content-center align-items-center" style="height:100vh">
        <div class="col-3">
          <form class="form-look" autocomplete="off">
            <div>
              <label>REGISTER</label>
            </div>
            <div class="form-group">
              <input type="text" class="form-control" placeholder="First Name" id="register-first-name">
            </div>
            <div class="form-group">
              <input type="text" class="form-control" placeholder="Last Name" id="register-last-name">
            </div>
            <div class="form-group">
              <input type="text" class="form-control" placeholder="Email" id="register-email">
            </div>
            <div class="form-group">
              <input type="password" class="form-control" placeholder="Password" id="register-password">
            </div>
            <div class="form-group">
              <input type="password" class="form-control" placeholder="Retype Password" id="register-retyped-password">
            </div>
            <button type="button" class="btn btn-primary form-elements-look" id="process-register-button">Submit</button>
            <hr class="seperation-bar">
            <p>Already Have an account?</p>
            <a href="Login.php">Click Here</a>
          </form>
        </div>
      </div>
    </div>

  </body>

</html>
