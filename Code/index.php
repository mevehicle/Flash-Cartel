<?php
$pageTitle = "Flash Cartel login page";
include 'header.php';

// Check if user is already signed in, and if so, send them to home page.
if (array_key_exists("user_id", $_SESSION)) {
  header("location: home.php");
  exit();
}

// Error handling for returned login form.
if (isset($_GET["error"])) {
  if ($_GET["error"] === "emptyinput") {
    echo "<p> Please fill in the form carefully! </p>";
  } else if ($_GET["error"] === "wronglogin") {
    echo "<p> Incorrect login information! </p>";
  }
}
?>

<body>
  <form action="includes/login.inc.php" method="post">
    <br />
    <div class="centre">
      <img src="images/logo.jpg" alt="Flash Cartel logo" class="logo" />
    </div>
    <h2>LOGIN</h2>
    <br>
    <label for="username">Username:</label><br />
    <input type="text" id="username" name="username" placeholder="Username..." /><br />
    <label for="pwd">Password:</label><br />
    <input type="password" id="pwd" name="pwd" placeholder="Password..." />
    <input type="submit" name="submit" value="Submit" />
    <br>
    <p><a href="forgot.php">I forgot my password</a></p>
    <br>
    <p><a href="register.php">Register</a>.</p>
  </form>

  <br>

  <?php
  include "footer.php";
