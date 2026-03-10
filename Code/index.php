<?php
$pageTitle = "Flash Cartel login page";
include 'header.php';
?>

<body>
  <br />
  <div class="centre">
    <img
      src="images/logo.jpg"
      alt="Flash Cartel logo"
      width="360"
      height="180" />
  </div>
  <h2>LOGIN</h2>
  <br>
  <form action="includes/login.inc.php" method="post">
    <label for="username">Username:</label><br />
    <input type="text" id="username" name="username" placeholder="Username..." /><br />
    <label for="pwd">Password:</label><br />
    <input type="password" id="pwd" name="pwd" placeholder="Password..." />
    <input type="submit" value="Submit" />
  </form>
  <br>
  <p>or click <a href="forgot.php">I forgot my password</a></p>
  <br>
  <p>or <a href="register.php">register</a>.</p>
  <br>

  <?php
  if (isset($_GET["error"])) {
    if (isset($_GET["error"]) === "emptyinput") {
      echo "<p> Please fill in the form carefully! </p>";
    } else if (isset($_GET["error"]) === "wronglogin") {
      echo "<p> Incorrect login information! </p>";
    }
  }
  ?>
</body>

</html>