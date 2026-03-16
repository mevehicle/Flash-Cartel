<?php

// Backend to register.php form

if (isset($_POST["submit"])) {

  require 'functions.inc.php';

  // Has user filled in all fields of form?
  if (
    empty($_POST["username"]) || empty($_POST["email"]) || empty($_POST["pwd"])
    || empty($_POST["pwdRepeat"])
  ) {
    header("location: ../register.php?error=emptyinput");
    exit();
  }

  $username = trim($_POST["username"]);
  $email = trim($_POST["email"]);
  $password = trim($_POST["pwd"]);
  $passwordRepeat = trim($_POST["pwdRepeat"]);

  // Check if username is valid and doesn't already exist in database.
  if (invalidUid($username) !== false) {
    header("location: ../register.php?error=invaliduid");
    exit();
  }

  // Check if email is valid and doesn't already exist in database.
  if (invalidEmail($email) !== false) {
    header("location: ../register.php?error=invalidemail");
    exit();
  }

  // Check if passwords match.
  if (pwdMatch($password, $passwordRepeat) !== false) {
    header("location: ../register.php?error=passwordsdontmatch");
    exit();
  }

  // Check if password is strong enough.
  if (pwdNotStrong($password) !== false) {
    header("location: ../register.php?error=passwordnotstrong");
    exit();
  }

  // Check if username is already taken.
  if (uidExists($conn, $username) !== false) {
    header("location: ../register.php?error=usernametaken");
    exit();
  }

  // If all the above functions return false, create user in database.
  createUser($username, $email, $password);
  header("location: ../register.php?error=none");
  exit();
} else {
  // If user tries to access this page without submitting form, send them back to registration page.
  header("location: ../register.php");
}
