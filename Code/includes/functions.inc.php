<?php
// This file contains functions that are used in the login and registration processes.

// Check if any input fields in registration form are empty. 
if (emptyInputSignup($username, $email, $password, $passwordRepeat) !== false) {
  $result;
  if (empty($username) || empty($email) || empty($password) || empty($passwordRepeat)) {
    $result = true;
  } else {
    $result = false;
  }
  return $result;
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

// Check if email is valid.
function invalidEmail($email)
{
  $result;
  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $result = true;
  } else {
    $result = false;
  }
  return $result;
}

// Check if passwords match.
function pwdMatch($password, $passwordRepeat)
{
  $result;
  if ($password !== $passwordRepeat) {
    $result = true;
  } else {
    $result = false;
  }
  return $result;
}

// Check if password is strong enough (at least 8 characters, contains at least one uppercase letter, one lowercase letter, one number and one special character).
function pwdNotStrong($password)
{
  $result;
  if (!preg_match("/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/", $password)) {
    $result = true;
  } else {
    $result = false;
  }
  return $result;
}

function uidExists($conn, $username, $email)
{
  $stmt = $conn->prepare("SELECT * FROM users WHERE username = :username OR email = :email;");
  $stmt->bindValue(':username', $username);
  $stmt->bindValue(':email', $email);
  $stmt->execute();

  $resultSet = $stmt->fetch();
  $conn = null;
  if ($resultSet) {
    $result = true;
    return $result;
  } else {
    $result = false;
    return $result;
  }
}

function createUser($conn, $username, $email, $password)
{
  $stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (:username, :email, :password);");

  $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

  $stmt->bindValue(':username', $username);
  $stmt->bindValue(':email', $email);
  $stmt->bindValue(':password', $hashedPassword);
  $stmt->execute();

  $resultSet = $stmt->fetch();
  $conn = null;
  header("location: ../register.php?error=none");
}
