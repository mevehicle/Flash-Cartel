<?php
$pageTitle = "Edit Account";
include "header.php";

// Check that user is signed in
if (!array_key_exists("user_id", $_SESSION)) {
  header("location: index.php");
  exit();
}

require 'includes/functions.inc.php';

?>

<body class="fabric">
  <div class="tab">
    <a href="home.php">
      <img src="images/logo.jpg" alt="Flash Cartel logo" />
    </a>


    <form class="login" action="includes/edit_account.inc.php" method="post">

      <?php
      // Error handling for returned form.
      if (isset($_GET["error"])) {
        if ($_GET["error"] === "emptypassword") {
          echo "<p class=\"large\"> You need to enter your current password! </p>";
        } else if ($_GET["error"] === "wrongpassword") {
          echo "<p class=\"large\"> Please enter your password carefully. </p>";
        } else if ($_GET["error"] === "emptyinput") {
          echo "<p class=\"large\"> Please fill in the form carefully! </p>";
        } else if ($_GET["error"] === "invaliduid") {
          echo "<p class=\"large\"> Please choose an appropriate and unique new username! </p>";
        } else if ($_GET["error"] === "invalidemail") {
          echo "<p class=\"large\"> Please enter a valid new email address! </p>";
        } else if ($_GET["error"] === "passwordsdontmatch") {
          echo "<p class=\"large\"> Your new passwords do not match! </p>";
        } else if ($_GET["error"] === "passwordnotstrong") {
          echo "<p class=\"large\"> Passwords need at least 8 characters & must include an uppercase letter,
          a lowercase letter and a number. No symbols, though! </p>";
        } else if ($_GET["error"] === "none") {
          echo "<p class=\"large\"> Your account has been edited! </p>";
        }
      }
      ?>
      <label for="newUsername">Enter a new username if desired:</label><br />
      <input type="text" id="newUsername" name="newUsername"
        placeholder=<?php $username = $_SESSION["username"];
                    echo $username; ?> /><br />
      <label for="newEmail">Enter a new email if desired:</label><br>
      <input type="text" id="newEmail" name="newEmail"
        placeholder=<?php $user_id = $_SESSION["user_id"];
                    echo findUserEmail($user_id); ?> /><br>
      <label for="newPassword">Enter a new password if desired:</label><br>
      <input type="password" id="newPassword" name="newPassword"
        placeholder="New password..." /><br />
      <label for="newPasswordRepeat">Re-enter your new password:</label><br />
      <input type="password" id="newPasswordRepeat" name="newPasswordRepeat"
        placeholder="New password..." /><br />
      <label for="password">Enter your current password:</label><br />
      <input type="password" id="password" name="password" placeholder="Password..." /><br />
      <input type="submit" name="submit" value="Submit" /><br>

    </form>

    <a class="home-link" href="includes/logout.inc.php">Logout</a>
  </div>
  <?php
  include "footer.php";
