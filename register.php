<?php
$pageTitle = "Flash Cartel registration page";
include 'header.php';

// Check if user is already signed in, and if so, send them to home page.
if (array_key_exists("user_id", $_SESSION)) {
  header("location: home.php");
  exit();
}
?>

<body>

  <form class="login" action="includes/register.inc.php" method="post">
    <?php
    // Error handling for returned registration form.
    if (isset($_GET["error"])) {
      if ($_GET["error"] === "emptyinput") {
        echo "<p> Please fill in the form carefully! </p>";
      } else if ($_GET["error"] === "invaliduid") {
        echo "<p class=\"large\"> Please choose an appropriate and unique username! </p>";
      } else if ($_GET["error"] === "invalidemail") {
        echo "<p class=\"large\"> Please enter a valid email address! </p>";
      } else if ($_GET["error"] === "passwordsdontmatch") {
        echo "<p class=\"large\"> Your passwords do not match! </p>";
      } else if ($_GET["error"] === "passwordnotstrong") {
        echo "<p class=\"large\"> Passwords need at least 8 characters & must include an uppercase letter,
          a lowercase letter and a number. No symbols, though! </p>";
      } else if ($_GET["error"] === "none") {
        echo "<p class=\"large\"> You're now registered! </p>";
      }
    }
    ?>
    <br>
    <a href="index.php">Login</a>
    <h2>Sign up to Flash Cartel:</h2>
    <br>
    <label for="username">Please choose a username:</label><br />
    <input type="text" id="username" name="username" placeholder="Username..." /><br />
    <label for="email">Please enter your email address:</label><br />
    <input type="text" id="email" name="email" placeholder="Email..." /><br />
    <label for="password">Please choose a unique password:</label><br />
    <input type="password" id="password" name="password" placeholder="Password..." /><br />
    <label for="passwordRepeat">Please re-enter your password:</label><br />
    <input type="password" id="passwordRepeat" name="passwordRepeat" placeholder="Password..." /><br />
    <input type="submit" name="submit" value="Submit" /><br>
  </form>

  <?php
  include "footer.php";
