<?php
$pageTitle = "Create Card";
include "header.php";

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
  </div>





  <a class="home-link" href="includes/logout.inc.php">Logout</a>

  <?php
  include "footer.php";
