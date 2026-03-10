<?php
$pageTitle = "Flash Cartel registration page";
include 'header.php';
?>

<body>
  <h2>Sign up to Flash Cartel:</h2>
  <br>
  <form action="includes/register.inc.php" method="post">
    <label for="username">Please choose a username:</label><br />
    <input type="text" id="username" name="username" placeholder="Username..." /><br />
    <label for="email">Please enter your email address:</label><br />
    <input type="text" id="email" name="email" placeholder="Email..." /><br />
    <label for="pwd">Please choose a unique password:</label><br />
    <input type="password" id="pwd" name="pwd" placeholder="Password..." /><br />
    <label for="pwdRepeat">Please re-enter your password:</label><br />
    <input type="password" id="pwdRepeat" name="pwdRepeat" placeholder="Password..." /><br />
    <input type="submit" value="Submit" />
  </form>

  <?php
  if (isset($_GET["error"])) {
    if (isset($_GET["error"]) === "emptyinput") {
      echo "<p> Please fill in the form carefully! </p>";
    } else if (isset($_GET["error"]) === "invaliduid") {
      echo "<p> Please use a unique username and email! </p>";
    } else if (isset($_GET["error"]) === "invalidemail") {
      echo "<p> Please enter a valid email address! </p>";
    } else if (isset($_GET["error"]) === "passwordsdontmatch") {
      echo "<p> Your passwords do not match! </p>";
    } else if (isset($_GET["error"]) === "pwdnotstrong") {
      echo "<p> Passwords need at least 8 characters & include an uppercase letter, a lowercase letter & a number! </p>";
    } else if (isset($_GET["error"]) === "none") {
      echo "<p> You're now registered! </p>";
    }
  }
  ?>
</body>

</html>