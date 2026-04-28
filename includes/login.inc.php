<?php

// Backend to login.php

// Check if user has submitted form.
if (isset($_POST["submit"])) {

  // Has user filled in all fields of form?
  if //(!array_key_exists("username", $_POST) || !array_key_exists("pwd", $_POST)) {
  (empty($_POST["username"]) || empty($_POST["pwd"])) {
    header("location: ../index.php?error=emptyinput");
    exit();
  }

  $username = trim($_POST["username"]);
  $password = trim($_POST["pwd"]);

  require_once 'functions.inc.php';

  loginUser($username, $password);

  // If user tries to access this page without submitting form, send them back to login page.
} else {
  header("location: ../login.php");
  exit();
}
