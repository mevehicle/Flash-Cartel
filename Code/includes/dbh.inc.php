<?php
$servername = "localhost";
$username = "root";
$password = "";

// COMMENT OUT THE BELOW LINES TO CONNECT TO THE PRODUCTION DATABASE
try {
  $conn = new PDO("mysql:host=$servername;dbname=users", $username, $password);
  // set the PDO error mode to exception
  $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
  echo "Connection failed: " . $e->getMessage();
}
