<?php
session_start();
if (!isset($_SESSION["user_id"])) {
  header("location: index.php");
  exit();
}
$pageTitle = "Home";
include "header.php";
?>

<body>
  <h1>Welcome to Flash Cartel, <?php echo $_SESSION["username"]; ?>!</h1>
  <p>This is the home page.</p>
  <a href="includes/logout.inc.php">Logout</a>
</body>