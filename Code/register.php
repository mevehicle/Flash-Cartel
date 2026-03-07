<?php
$pageTitle = "Flash Cartel registration page";
include 'header.php';
?>

<body>
  <h2>Sign up to Flash Cartel:</h2>
  <br>
  <form action="register.inc.php">
    <label for="username">Please choose a username:</label><br />
    <input type="text" id="username" name="username" placeholder="Username..." /><br />
    <label for="email">Please enter your email address:</label><br />
    <input type="text" id="email" name="email" placeholder="Email..." /><br />
    <label for="pwd">Please choose a unique password:</label><br />
    <input type="password" id="pwd" name="pwd" placeholder="Password" />
    <input type="submit" value="Submit" />
  </form>
</body>

</html>