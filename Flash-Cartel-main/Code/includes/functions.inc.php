<?php
// This file contains functions that are used in the login and registration processes.


// Create user in database.
function createUser($username, $email, $password)
{
  $conn = getConnection();
  $stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (:username, :email, :password);");

  $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

  $stmt->bindValue(':username', $username);
  $stmt->bindValue(':email', $email);
  $stmt->bindValue(':password', $hashedPassword);
  $stmt->execute();

  // $resultSet = $stmt->fetch();
  $conn = null;
  // return $resultSet;
}


// Check if any input fields in login form are empty.
function emptyInputLogin($username, $password)
{
  $result;
  if (!array_key_exists("username", $_POST) || !array_key_exists("pwd", $_POST)) {
    $result = true;
  } else {
    $result = false;
  }
  return $result;
}


// Check if any input fields in registration form are empty. 
function emptyInputSignup($username, $email, $password, $passwordRepeat)
{
  $result;
  if (
    !array_key_exists("username", $_POST) || !array_key_exists("email", $_POST)
    || !array_key_exists("pwd", $_POST) || !array_key_exists("pwdRepeat", $_POST)
  ) {
    $result = true;
  } else {
    $result = false;
  }
  return $result;
}


function getConnection(): PDO
{
  require 'config.php';
  // COMMENT OUT THE ERROR HANDLING LINES TO CONNECT TO THE PRODUCTION DATABASE
  try {
    $conn = new PDO($dsn, $dbUsername, $dbPassword);
    // set the PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  } catch (PDOException $exception) {
    echo "Connection failed: " . $exception->getMessage();
  }
  return $conn;
}


// Check if email is valid.
function invalidEmail($email)
{
  // First, check if email already exists in database.
  $conn = getConnection();
  $stmt = $conn->prepare("SELECT * FROM users WHERE email = :email");
  $stmt->bindValue(':email', $email);
  $stmt->execute();

  $resultSet = $stmt->fetch();
  $conn = null;

  if ($resultSet) {
    $result = true;
    return $result;
  } else {
    // If email isn't already in database, check if it's a valid email address.
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
      $result = true;
    } else {
      $result = false;
    }
    return $result;
  }
}


// Check if username contains only letters and numbers.
function invalidUid($username)
{
  $result;
  if (!preg_match("/^[a-zA-Z0-9]{3,20}$/", $username)) {
    $result = true;
  } else {
    $result = false;
  }
  return $result;
}


// Login function.
function loginUser($username, $password)
{
  // Check if username exists in database.
  $conn = getConnection();
  $userExists = uidExists($conn, $username);

  // if username doesn't exist, send user back to login page with error message.
  if ($userExists === false) {
    header("location: ../index.php?error=wronglogin");
    exit();
  }

  // if username exists, check if password is correct against hashed password in database.
  $hashedPassword = $userExists["password"];
  $checkPwd = password_verify($password, $hashedPassword);

  // if password incorrect, send user back to login screen with error message.
  // if password correct, establish session and send user back to login page.
  if ($checkPwd === false) {
    header("location: ../index.php?error=wronglogin");
    exit();
  } else if ($checkPwd === true) {
    session_start();
    $_SESSION["user_id"] = $userExists["user_id"];
    $_SESSION["username"] = $userExists["username"];
    header("location: ../index.php");
    exit();
  }
}


// Check if passwords match.
function pwdMatch($password, $passwordRepeat)
{
  // $result will be true if password isn't same as passwordRepeat
  $result;
  if ($password !== $passwordRepeat) {
    $result = true;
  } else {
    $result = false;
  }
  return $result;
}


//   Function to check if password is strong enough (at least 8 characters, contains at least
//  one uppercase letter, one lowercase letter, one number and one special character).
function pwdNotStrong($password)
{
  // $result will be true if password DOESN'T match the password policy
  $result;
  if (!preg_match("/(.{0,7}|[^0-9]*|[^A-Z]*|[^a-z]*)/", $password)) {
    $result = true;
  } else {
    $result = false;
  }
  return $result;
}


// Function to check if username already exists in database.
function uidExists($conn, $username)
{
  $conn = getConnection();
  $stmt = $conn->prepare("SELECT * FROM users WHERE username = :username");
  $stmt->bindValue(':username', $username);
  $stmt->execute();

  $resultSet = $stmt->fetch();
  $conn = null;
  if ($resultSet) {
    $result = true;
    return $resultSet;
  } else {
    $result = false;
    return $result;
  }
}
