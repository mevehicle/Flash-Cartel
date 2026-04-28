<?php

// ─── Database Connection ───
function getConnection(): PDO
{
    if (!defined('DOCUMENT_ROOT')) {
        define('DOCUMENT_ROOT', dirname(__DIR__));
    }

    $iniPath = DOCUMENT_ROOT . DIRECTORY_SEPARATOR . "includes" . DIRECTORY_SEPARATOR . "config.ini";
    



    if (!file_exists($iniPath)) {
        die("config.ini not found at: $iniPath");
    }

    $db = parse_ini_file($iniPath, true);

    $dsn = "mysql:host={$db['database']['hostname']};dbname={$db['database']['dbname']};charset=utf8mb4";

    try {
        $conn = new PDO($dsn, $db['database']['username'], $db['database']['password']);
        $conn->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        die(json_encode(['error' => 'DB connection failed: ' . $e->getMessage()]));
    }

    return $conn;
}


// ─── User Auth ────────────────────────────────────────────────────────────────

/**
 * Returns user row if username exists, false otherwise.
 * BUG FIX: original passed $conn as param but then immediately overwrote it.
 */
function uidExists(string $username): array|false
{
    $conn = getConnection();
    $stmt = $conn->prepare("SELECT * FROM users WHERE username = :username");
    $stmt->bindValue(':username', $username);
    $stmt->execute();
    return $stmt->fetch() ?: false;
}

function emailExists(string $email): bool
{
    $conn = getConnection();
    $stmt = $conn->prepare("SELECT user_id FROM users WHERE email = :email");
    $stmt->bindValue(':email', $email);
    $stmt->execute();
    return (bool) $stmt->fetch();
}

/**
 * Returns user data array on success, false on failure.
 */
function checkPassword(string $username, string $password): array|false
{
    $userData = uidExists($username);
    if ($userData === false) return false;

    if (!password_verify($password, $userData['password'])) return false;

    return $userData;
}

/**
 * Creates a new user. Returns true on success.
 */
function createUser(string $username, string $email, string $password): bool
{
    $conn = getConnection();
    $stmt = $conn->prepare(
        "INSERT INTO users (username, email, password) VALUES (:username, :email, :password)"
    );
    $stmt->bindValue(':username', $username);
    $stmt->bindValue(':email', $email);
    $stmt->bindValue(':password', password_hash($password, PASSWORD_DEFAULT));
    return $stmt->execute();
}

/**
 * Login: sets session and redirects.
 */
function loginUser(string $username, string $password): void
{
    $userData = checkPassword($username, $password);

    if ($userData === false) {
        header("location: ../index.php?error=wronglogin");
        exit();
    }

    session_start();
    $_SESSION['user_id']  = $userData['user_id'];
    $_SESSION['username'] = $userData['username'];

    // Log activity for streak tracking
    logActivity($userData['user_id']);

    header("location: ../home.php");
    exit();
}


// ─── Validation ───────────────────────────────────────────────────────────────

/**
 * BUG FIX: Returns error string or false (clean).
 * Username: 3–20 alphanumeric chars only.
 */
function invalidUid(string $username): string|false
{
    if (!preg_match("/^[a-zA-Z0-9]{3,20}$/", $username)) {
        return "Username must be 3–20 letters/numbers only.";
    }
    if (uidExists($username) !== false) {
        return "That username is already taken.";
    }
    return false;
}

/**
 * BUG FIX: Returns error string or false.
 * Checks format AND uniqueness.
 */
function invalidEmail(string $email): string|false
{
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return "Please enter a valid email address.";
    }
    if (emailExists($email)) {
        return "That email address is already registered.";
    }
    return false;
}

/**
 * Returns true if passwords don't match (i.e., there IS a mismatch).
 * BUG FIX: name clarified; callers check `if (pwdMismatch(...))`.
 */
function pwdMismatch(string $a, string $b): bool
{
    return $a !== $b;
}

/**
 * BUG FIX: Original regex `(^[.]{0,7})` matched 0 chars — always passed.
 * Now correctly enforces: ≥8 chars, uppercase, lowercase, digit.
 * No special characters required (matches original spec).
 */
function pwdNotStrong(string $password): string|false
{
    if (strlen($password) < 8) {
        return "Password must be at least 8 characters.";
    }
    if (!preg_match('/[A-Z]/', $password)) {
        return "Password must contain at least one uppercase letter.";
    }
    if (!preg_match('/[a-z]/', $password)) {
        return "Password must contain at least one lowercase letter.";
    }
    if (!preg_match('/[0-9]/', $password)) {
        return "Password must contain at least one number.";
    }
    return false;
}


// ─── Account Editing ─────────────────────────────────────────────────────────

function editUsername(int $user_id, string $newUsername): bool
{
    $conn = getConnection();
    $stmt = $conn->prepare("UPDATE users SET username = :username WHERE user_id = :user_id");
    $stmt->bindValue(':username', $newUsername);
    $stmt->bindValue(':user_id', $user_id);
    return $stmt->execute();
}

function editEmail(int $user_id, string $newEmail): bool
{
    $conn = getConnection();
    $stmt = $conn->prepare("UPDATE users SET email = :email WHERE user_id = :user_id");
    $stmt->bindValue(':email', $newEmail);
    $stmt->bindValue(':user_id', $user_id);
    return $stmt->execute();
}

function editPassword(int $user_id, string $newPassword): bool
{
    $conn = getConnection();
    $stmt = $conn->prepare("UPDATE users SET password = :password WHERE user_id = :user_id");
    $stmt->bindValue(':password', password_hash($newPassword, PASSWORD_DEFAULT));
    $stmt->bindValue(':user_id', $user_id);
    return $stmt->execute();
}

function findUserEmail(int $user_id): string
{
    $conn = getConnection();
    $stmt = $conn->prepare("SELECT email FROM users WHERE user_id = :user_id");
    $stmt->bindValue(':user_id', $user_id);
    $stmt->execute();
    $row = $stmt->fetch();
    return $row ? $row['email'] : '';
}


// ─── Decks ────────────────────────────────────────────────────────────────────

function getDecks(int $user_id): array
{
    $conn = getConnection();
    $stmt = $conn->prepare("SELECT * FROM decks WHERE user_id = :user_id ORDER BY deck_name");
    $stmt->bindValue(':user_id', $user_id);
    $stmt->execute();
    return $stmt->fetchAll();
}

function getNumberOfDecks(int $user_id): int
{
    $conn = getConnection();
    $stmt = $conn->prepare("SELECT COUNT(deck_id) AS total FROM decks WHERE user_id = :user_id");
    $stmt->bindValue(':user_id', $user_id);
    $stmt->execute();
    return (int) $stmt->fetch()['total'];
}

/**
 * BUG FIX: Returns deck_id int or null (was returning raw row).
 */
function getDeckId(int $user_id, string $deck_name): ?int
{
    $conn = getConnection();
    $stmt = $conn->prepare(
        "SELECT deck_id FROM decks WHERE user_id = :user_id AND deck_name = :deck_name"
    );
    $stmt->bindValue(':user_id', $user_id);
    $stmt->bindValue(':deck_name', $deck_name);
    $stmt->execute();
    $row = $stmt->fetch();
    return $row ? (int) $row['deck_id'] : null;
}

function findDeckId(string $deck_name, int $user_id): array|false
{
    $conn = getConnection();
    $stmt = $conn->prepare(
        "SELECT deck_id FROM decks WHERE deck_name = :deck_name AND user_id = :user_id"
    );
    $stmt->bindValue(':deck_name', $deck_name);
    $stmt->bindValue(':user_id', $user_id);
    $stmt->execute();
    return $stmt->fetch() ?: false;
}

/**
 * Validates a deck_id belongs to a given user.
 */
function checkDeckId(int $user_id, int $deck_id): bool
{
    $conn = getConnection();
    $stmt = $conn->prepare(
        "SELECT COUNT(deck_id) AS total FROM decks WHERE deck_id = :deck_id AND user_id = :user_id"
    );
    $stmt->bindValue(':user_id', $user_id);
    $stmt->bindValue(':deck_id', $deck_id);
    $stmt->execute();
    return (int) $stmt->fetch()['total'] > 0;
}

function createDeck(int $user_id, string $deck_name): bool
{
    $conn = getConnection();
    $stmt = $conn->prepare(
        "INSERT INTO decks (deck_name, user_id) VALUES (:deck_name, :user_id)"
    );
    $stmt->bindValue(':deck_name', $deck_name);
    $stmt->bindValue(':user_id', $user_id);
    return $stmt->execute();
}

function deleteDeck(int $deck_id): bool
{
    $conn = getConnection();
    $stmt = $conn->prepare("DELETE FROM decks WHERE deck_id = :deck_id");
    $stmt->bindValue(':deck_id', $deck_id);
    return $stmt->execute();
}


// ─── Cards ────────────────────────────────────────────────────────────────────

function getCards(int $deck_id): array
{
    $conn = getConnection();
    $stmt = $conn->prepare("SELECT * FROM cards WHERE deck_id = :deck_id ORDER BY card_id");
    $stmt->bindValue(':deck_id', $deck_id);
    $stmt->execute();
    return $stmt->fetchAll();
}

function getCardsInDeck(int $deck_id): int
{
    $conn = getConnection();
    $stmt = $conn->prepare("SELECT COUNT(card_id) AS total FROM cards WHERE deck_id = :deck_id");
    $stmt->bindValue(':deck_id', $deck_id);
    $stmt->execute();
    return (int) $stmt->fetch()['total'];
}

function createCard(int $deck_id, string $question, string $answer): bool
{
    $conn = getConnection();
    $stmt = $conn->prepare(
        "INSERT INTO cards (deck_id, question, answer) VALUES (:deck_id, :question, :answer)"
    );
    $stmt->bindValue(':deck_id', $deck_id);
    $stmt->bindValue(':question', $question);
    $stmt->bindValue(':answer', $answer);
    return $stmt->execute();
}

/**
 * BUG FIX: Uses subquery to target card by position (cardNumber = 0-based offset).
 */
function editCard(int $deck_id, int $cardNumber, string $question, string $answer): bool
{
    $conn = getConnection();
    $stmt = $conn->prepare(
        "UPDATE cards SET question = :question, answer = :answer
         WHERE card_id IN (
           SELECT card_id FROM (
             SELECT card_id FROM cards WHERE deck_id = :deck_id
             ORDER BY card_id LIMIT 1 OFFSET :offset
           ) x
         )"
    );
    $stmt->bindValue(':question', $question);
    $stmt->bindValue(':answer', $answer);
    $stmt->bindValue(':deck_id', $deck_id);
    $stmt->bindValue(':offset', $cardNumber, PDO::PARAM_INT);
    return $stmt->execute();
}

function deleteCard(int $deck_id, int $cardNumber): bool
{
    $conn = getConnection();
    $stmt = $conn->prepare(
        "DELETE FROM cards WHERE card_id IN (
           SELECT card_id FROM (
             SELECT card_id FROM cards WHERE deck_id = :deck_id
             ORDER BY card_id LIMIT 1 OFFSET :offset
           ) x
         )"
    );
    $stmt->bindValue(':deck_id', $deck_id);
    $stmt->bindValue(':offset', $cardNumber, PDO::PARAM_INT);
    return $stmt->execute();
}


// ─── Activity / Streak Tracking ───────────────────────────────────────────────

function logActivity(int $user_id): void
{
    $conn = getConnection();
    $today = date('Y-m-d');
    // INSERT IGNORE so duplicate date+user is silently skipped
    $stmt = $conn->prepare(
        "INSERT IGNORE INTO activity_log (user_id, log_date) VALUES (:user_id, :log_date)"
    );
    $stmt->bindValue(':user_id', $user_id);
    $stmt->bindValue(':log_date', $today);
    $stmt->execute();
}

/**
 * Returns current streak (consecutive days up to today).
 */
function getStreak(int $user_id): int
{
    $conn = getConnection();
    $stmt = $conn->prepare(
        "SELECT log_date FROM activity_log WHERE user_id = :user_id ORDER BY log_date DESC"
    );
    $stmt->bindValue(':user_id', $user_id);
    $stmt->execute();
    $rows = $stmt->fetchAll();

    if (empty($rows)) return 0;

    $streak = 0;
    $check  = new DateTime('today');

    foreach ($rows as $row) {
        $date = new DateTime($row['log_date']);
        if ($date->format('Y-m-d') === $check->format('Y-m-d')) {
            $streak++;
            $check->modify('-1 day');
        } else {
            break;
        }
    }

    return $streak;
}

/**
 * Returns array of dates (strings Y-m-d) active in the last 365 days.
 */
function getActivityDates(int $user_id): array
{
    $conn = getConnection();
    $stmt = $conn->prepare(
        "SELECT log_date FROM activity_log
         WHERE user_id = :user_id AND log_date >= DATE_SUB(CURDATE(), INTERVAL 365 DAY)"
    );
    $stmt->bindValue(':user_id', $user_id);
    $stmt->execute();
    return array_column($stmt->fetchAll(), 'log_date');
}

/**
 * Returns stats for the dashboard.
 */
function getDashboardStats(int $user_id): array
{
    $conn = getConnection();

    $decks = getNumberOfDecks($user_id);

    $stmt = $conn->prepare(
        "SELECT COUNT(c.card_id) AS total FROM cards c
         JOIN decks d ON c.deck_id = d.deck_id
         WHERE d.user_id = :user_id"
    );
    $stmt->bindValue(':user_id', $user_id);
    $stmt->execute();
    $cards = (int) $stmt->fetch()['total'];

    $streak = getStreak($user_id);
    $activityDates = getActivityDates($user_id);

    return [
        'decks'          => $decks,
        'cards'          => $cards,
        'streak'         => $streak,
        'activityDates'  => $activityDates,
    ];
}