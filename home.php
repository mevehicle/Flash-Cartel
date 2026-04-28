<?php
$pageTitle = "Home";
include "header.php";

if (!array_key_exists("user_id", $_SESSION)) {
  header("location: index.php");
  exit();
}
?>

<body>
  <h1>Welcome to Flash Cartel, <?php echo $_SESSION["username"]; ?>!</h1>
  <p>This is the home page.</p>
  <a href="includes/logout.inc.php">Logout</a>
  <?php
  include "footer.php";
