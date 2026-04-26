<?php
session_start();

// Backend to edit_account.php form

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

  require "functions.inc.php";

  // Has password been entered?
  if (empty($_POST['password'])) {
    header("location: ../edit_account.php?error=emptypassword");
    exit();
  } else {
    // Check password is correct
    $password = trim($_POST['password']);
    $username = $_SESSION['username'];
    $userData = checkPassword($username, $password);
    if ($userData === false) {
      header("location: ../edit_account.php?error=wrongpassword");
      exit();
    }
  }

  // If password is entered and correct, has other data been entered?
  if (
    empty($_POST['newUsername']) && empty($_POST['newEmail']) &&
    (empty($_POST['newPassword']) || empty($_POST['newPasswordRepeat']))
  ) {
    header("location: ../edit_account.php?error=emptyinput");
    exit();
  }
  // Transfer values from form into PHP variables

  // Has a new username been entered?
  if (!empty($_POST["newUsername"])) {
    $newUsername = trim($_POST["newUsername"]);
    // Check if newUsername is valid and doesn't already exist in database.
    if (invalidUid($newUsername) !== false) {
      header("location: ../edit_account.php?error=invaliduid");
      exit();
    } else {
      // Alter username in database
      editUsername($userData["user_id"], $newUsername);
      $_SESSION['username'] = $newUsername;
    }
  }

  // Has a new email address been entered?
  if (!empty($_POST["newEmail"])) {
    $newEmail = trim($_POST["newEmail"]);

    // Check if newEmail is valid and doesn't already exist in database.
    if (invalidEmail($newEmail) !== false) {
      header("location: ../edit_account.php?error=invalidemail");
      exit();
    } else {
      // Alter user's email in database
      editEmail($userData["user_id"], $newEmail);
    }
  }

  // Check if a new password has been entered
  if (!empty($_POST["newPassword"])) {
    $newPassword = trim($_POST["newPassword"]);
    $newPasswordRepeat = trim($_POST["newPasswordRepeat"]);
    // Check if passwords match.
    if (pwdMatch($newPassword, $newPasswordRepeat) !== false) {
      header("location: ../edit_account.php?error=passwordsdontmatch");
      exit();
    }
    // Check if newPassword is strong enough.
    if (pwdNotStrong($newPassword) !== false) {
      header("location: ../edit_account.php?error=passwordnotstrong");
      exit();
    } else {
      // If all checks have been passed, alter user in database.
      editPassword($userData['user_id'], $newPassword);
    }
  }
  header("location: ../edit_account.php?error=none");
  exit();
} else {
  // If user tries to access this page without submitting form, send them back to registration page.
  header("location: ../edit_account.php?error=formnotsubmitted");
  exit();
}
