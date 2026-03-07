<?php

if (isset($_POST['submit'])) {
  $username = $_POST['username'];
  $email = $_POST['email'];
  $password = $_POST['pwd'];
  $passwordRepeat = $_POST['pwdRepeat'];

  require_once 'dbh.inc.php';
  require_once 'functions.inc.php';

  if (emptyInputSignup($username, $email, $password, $passwordRepeat) !== false) {
    header("location: ../register.php?error=emptyinput");
    exit();
  }

  if (invalidUid($username) !== false) {
    header("location: ../register.php?error=invaliduid");
    exit();
  }

  if (invalidEmail($email) !== false) {
    header("location: ../register.php?error=invalidemail");
    exit();
  }

  if (pwdMatch($password, $passwordRepeat) !== false) {
    header("location: ../register.php?error=passwordsdontmatch");
    exit();
  }

  if (pwdNotStrong($password) !== false) {
    header("location: ../register.php?error=passwordnotstrong");
    exit();
  }

  if (uidExists($conn, $username, $email) !== false) {
    header("location: ../register.php?error=usernametaken");
    exit();
  }

  createUser($conn, $username, $email, $password);
} else {
  header("location: ../register.php");
}
