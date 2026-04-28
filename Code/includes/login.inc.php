<?php

// Backend to login.php

// Check if user has submitted form.
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

  // Has user filled in all fields of form?
  if (empty($_POST["username"]) || empty($_POST["password"])) {
    header("location: ../index.php?error=emptyinput");
    exit();
  }

  $username = trim($_POST["username"]);
  $password = trim($_POST["password"]);

  require_once 'functions.inc.php';

  loginUser($username, $password);

  // If user tries to access this page without submitting form, send them back to login page.
} else {
  header("location: ../login.php");
  exit();
}
