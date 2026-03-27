<?php
$pageTitle = "Edit Account";
include "header.php";

// Check that user is signed in
if (!array_key_exists("user_id", $_SESSION)) {
  header("location: index.php");
  exit();
}
?>

<body class="fabric">
  <div class="tab">
    <a href="home.php">
      <img src="images/logo.jpg" alt="Flash Cartel logo" />
    </a>







    <a class="home-link" href="includes/logout.inc.php">Logout</a>
  </div>
  <?php
  include "footer.php";
