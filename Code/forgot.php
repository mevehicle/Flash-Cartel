<?php
$pageTitle = "Flash Cartel forgot password page";
include 'header.php';
?>

<body>
  <h2>Forgotten your password?</h2>
  <p>Please enter your email address below and we will send you instructions on how to reset your password.</p>
  <form action="forgot.inc.php">
    <label for="email">Email address:</label><br />
    <input type="text" id="email" name="email" placeholder="Email" /><br />
    <input type="submit" value="Submit" />
</body>