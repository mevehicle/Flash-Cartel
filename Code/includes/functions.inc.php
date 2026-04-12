<?php
// This file contains functions that are used in the login and registration processes.


// Check entered password valid
function checkPassword($username, $password)
{
  // Check if username exists in database.
  $conn = getConnection();
  $userData = uidExists($conn, $username);
  $conn = null;

  // if username doesn't exist, return false.
  if ($userData === false) {
    return false;
  }

  // if username exists, check if password is correct against hashed password in database.
  $hashedPassword = $userData["password"];
  $checkPassword = password_verify($password, $hashedPassword);

  // if password incorrect, return false
  if ($checkPassword === false) {
    return false;
    // if password correct, return array of data for user.
  } else if ($checkPassword === true) {
    return $userData;
  }
}



// Create user in database.
function createUser($username, $email, $password)
{
  $conn = getConnection();
  $stmt = $conn->prepare("INSERT INTO users (username, email, password)
   VALUES (:username, :email, :password);");

  $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

  $stmt->bindValue(':username', $username);
  $stmt->bindValue(':email', $email);
  $stmt->bindValue(':password', $hashedPassword);
  $stmt->execute();

  $conn = null;
}


// Edit user's email'
function editEmail($user_id, $newEmail)
{
  $conn = getConnection();
  $stmt = $conn->prepare("UPDATE users SET email = :email
    WHERE user_id = :user_id;");

  $stmt->bindValue(':user_id', $user_id);
  $stmt->bindValue(':email', $newEmail);
  $stmt->execute();

  $conn = null;
}


// Edit user's password
function editPassword($user_id, $newPassword)
{
  $conn = getConnection();
  $stmt = $conn->prepare("UPDATE users SET password = :password WHERE user_id = :user_id;");

  $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

  $stmt->bindValue(':user_id', $user_id);
  $stmt->bindValue(':password', $hashedPassword);
  $stmt->execute();

  $conn = null;
}


// Edit username
function editUsername($user_id, $newUsername)
{
  $conn = getConnection();
  $stmt = $conn->prepare("UPDATE users SET username = :username
    WHERE user_id = :user_id;");

  $stmt->bindValue(':user_id', $user_id);
  $stmt->bindValue(':username', $newUsername);
  $stmt->execute();

  $conn = null;
}


// Find ID number of a deck, given deck name and user ID
function findDeckId($deck_name, $user_id)
{
  $conn = getConnection();
  $stmt = $conn->prepare("SELECT deck_id FROM decks WHERE deck_name = :deck_name 
                          AND user_id = :user_id");
  $stmt->bindValue(':deck_name', $deck_name);
  $stmt->bindValue(':user_id', $user_id);
  $stmt->execute();
  $row = $stmt->fetch();
  $conn = null;
  return $row;
}


// Find email address of user for Edit Account page
function findUserEmail($user_id)
{
  $conn = getConnection();
  $stmt = $conn->prepare("SELECT email FROM users WHERE user_id = :user_id");
  $stmt->bindValue(':user_id', $user_id);
  $stmt->execute();
  $row = $stmt->fetch();
  $conn = null;
  $email = (string) $row["email"];
  return $email;
}


// Connect to database
function getConnection(): PDO
{
  // Read .ini file and create associative array with database connection details.
  $db = parse_ini_file("config.ini", true);

  // Extract database connection details from associative array and create DSN string.
  $dbUser = $db["database"]["username"];
  $dbPassword = $db["database"]["password"];
  $servername = $db["database"]["hostname"];
  $dbName = $db["database"]["dbname"];

  $dsn = "mysql:host=$servername;dbname=$dbName;charset=utf8mb4";
  // COMMENT OUT THE ERROR HANDLING LINES TO CONNECT TO THE PRODUCTION DATABASE
  try {
    $conn = new PDO($dsn, $dbUser, $dbPassword);
    // set the PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  } catch (PDOException $exception) {
    echo "Connection failed: " . $exception->getMessage();
  }
  return $conn;
}


// 'Download' a deck of cards
function getCards($deck_id): array
{
  $conn = getConnection();
  $stmt = $conn->prepare("SELECT * FROM cards WHERE deck_id = :deck_id");
  $stmt->bindValue(':deck_id', $deck_id);
  $stmt->execute();
  $cards = $stmt->fetchAll();
  return $cards;
}


// Find how many cards in a deck
function getCardsInDeck($deck_id): int
{
  $conn = getConnection();
  $stmt = $conn->prepare("SELECT COUNT(card_id) AS total FROM cards WHERE deck_id = :deck_id");
  $stmt->bindValue(':deck_id', $deck_id);
  $stmt->execute();
  $numberOfCards = $stmt->fetch()['total'];
  return $numberOfCards;
}


// Get deck_id number associated with a deck name and user_id
function getDeckId($user_id, $deck_name)
{
  $conn = getConnection();
  $stmt = $conn->prepare("SELECT deck_id FROM decks WHERE user_id = :user_id
   AND deck_name = :deck_name");
  $stmt->bindValue(':user_id', $user_id);
  $stmt->bindValue(':deck_name', $deck_name);
  $stmt->execute();
  $row = $stmt->fetch();
  return $row["deck_id"];
}


// Get the names of the user's card decks
function getDecks($user_id): array
{
  $conn = getConnection();
  $stmt = $conn->prepare("SELECT * FROM decks WHERE user_id = :user_id
   ORDER BY deck_name");
  $stmt->bindValue(":user_id", $user_id);
  $stmt->execute();
  $decks = $stmt->fetchAll();
  return $decks;
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
  $result = false;
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
  // if password incorrect, $userData will contain false
  // if password correct, $userData will be an array containing data for user
  $userData = checkPassword($username, $password);

  // if password incorrect, send user back to login screen with error message.
  // if password correct, establish session and send user back to login page.
  if ($userData === false) {
    header("location: ../index.php?error=wronglogin");
    exit();
  } else {
    session_start();
    $_SESSION["user_id"] = $userData["user_id"];
    $_SESSION["username"] = $userData["username"];
    header("location: ../index.php");
    exit();
  }
}


// Check if passwords match.
function pwdMatch($password, $passwordRepeat)
{
  // $result will be true if password isn't same as passwordRepeat
  $result = false;
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
  $result = false;
  if (!preg_match("/(^[.]{0,7})/", $password)) {
    $result = true;
  } else if (!preg_match("/[.]*[A-Z]+[.]*/", $password)) {
    $result = true;
  } else if (!preg_match("/[.]*[a-z]+[.]*/", $password)) {
    $result = true;
  } else if (!preg_match("/[.]*[0-9]+[.]*/", $password)) {
    $result = true;
  } else if (preg_match("/[.]*[^\w]+[.]*/", $password)) {
    $result = true;
  } else {
    $result = false;
  }
  return $result;
}


/* Function to check if username already exists in database.
   Returns the data for that user if they exist already,
   Returns false if they don't. */
function uidExists($conn, $username)
{
  $conn = getConnection();
  $stmt = $conn->prepare("SELECT * FROM users WHERE username = :username");
  $stmt->bindValue(':username', $username);
  $stmt->execute();

  $resultSet = $stmt->fetch();
  $conn = null;
  if ($resultSet) {
    return $resultSet;
  } else {
    return false;
  }
}
