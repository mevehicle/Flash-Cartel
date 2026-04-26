<?php
$pageTitle = "Dashboard";
require 'includes/functions_inc.php';
include 'header.php';

if (!array_key_exists('user_id', $_SESSION)) {
    header("location: index.php");
    exit();
}

$user_id  = (int) $_SESSION['user_id'];
$username = htmlspecialchars($_SESSION['username']);
$stats    = getDashboardStats($user_id);

// Log today's activity on every visit
logActivity($user_id);

$errorMsg = '';
if (isset($_GET['error']) && $_GET['error'] === 'unknownerror') {
    $errorMsg = 'Something went wrong. Please try again.';
}

// Build activity set for calendar
$activeDates = array_flip($stats['activityDates']); // flip for O(1) lookup
?>
<body>

<!-- Navbar -->
<nav class="navbar">
  <a class="nav-logo" href="home.php">
    <span class="bolt">⚡</span> FLASH CARTEL
  </a>
  <div class="nav-welcome">Welcome back, <strong><?= $username ?></strong>!</div>
  <div class="nav-actions">
    <a href="edit_account.php" class="btn btn-ghost">⚙ Account</a>
    <a href="includes/logout_inc.php" class="btn btn-outline">Logout</a>
  </div>
</nav>

<div class="page-wrap">

  <?php if ($errorMsg): ?>
    <div class="msg msg-error"><?= htmlspecialchars($errorMsg) ?></div>
  <?php endif; ?>

  <!-- Stats row -->
  <div class="dashboard-grid mt-3">
    <div class="stat-card">
      <div class="stat-label">🔥 Current Streak</div>
      <div class="stat-value"><?= $stats['streak'] ?></div>
      <div class="stat-unit"><?= $stats['streak'] === 1 ? 'day' : 'days' ?> in a row</div>
    </div>
    <div class="stat-card">
      <div class="stat-label">📚 Total Decks</div>
      <div class="stat-value"><?= $stats['decks'] ?></div>
      <div class="stat-unit">decks created</div>
    </div>
    <div class="stat-card">
      <div class="stat-label">🃏 Total Cards</div>
      <div class="stat-value"><?= $stats['cards'] ?></div>
      <div class="stat-unit">flashcards</div>
    </div>
    <div class="stat-card">
      <div class="stat-label">📅 Days Active</div>
      <div class="stat-value"><?= count($stats['activityDates']) ?></div>
      <div class="stat-unit">in the last year</div>
    </div>
  </div>

  <!-- Activity Calendar -->
  <div class="activity-section">
    <h3>Activity — last 52 weeks</h3>
    <div class="activity-grid">
      <?php
      // Build 364 days (52 weeks) grid, oldest first
      $today     = new DateTime('today');
      $startDate = (clone $today)->modify('-363 days');
      $current   = clone $startDate;

      while ($current <= $today):
          $dateStr   = $current->format('Y-m-d');
          $isActive  = isset($activeDates[$dateStr]);
          $isToday   = $dateStr === $today->format('Y-m-d');
          $classes   = 'activity-day' . ($isActive ? ' active' : '') . ($isToday ? ' today' : '');
          echo "<div class=\"$classes\" title=\"$dateStr\"></div>";
          $current->modify('+1 day');
      endwhile;
      ?>
    </div>
  </div>

  <!-- Quick Actions -->
  <h2 class="page-title mb-2">Quick Actions</h2>
  <div class="actions-grid">
    <a class="action-card" href="create_deck.php">
      <span class="action-icon">📁</span>
      <div class="action-title">Create Deck</div>
      <div class="action-desc">Start a new flashcard collection</div>
    </a>
    <a class="action-card" href="create_card.php">
      <span class="action-icon">✏️</span>
      <div class="action-title">Add Card</div>
      <div class="action-desc">Write a question & answer</div>
    </a>
    <a class="action-card" href="view_deck.php?action=view">
      <span class="action-icon">👁️</span>
      <div class="action-title">View Cards</div>
      <div class="action-desc">Browse your flashcard decks</div>
    </a>
    <a class="action-card" href="view_deck.php?action=test">
      <span class="action-icon">🧠</span>
      <div class="action-title">Test Yourself</div>
      <div class="action-desc">Quiz yourself on a deck</div>
    </a>
    <a class="action-card" href="delete_deck.php">
      <span class="action-icon">🗑️</span>
      <div class="action-title">Delete Deck</div>
      <div class="action-desc">Remove a flashcard deck</div>
    </a>
    <a class="action-card" href="edit_account.php">
      <span class="action-icon">⚙️</span>
      <div class="action-title">Edit Account</div>
      <div class="action-desc">Change username, email or password</div>
    </a>
  </div>
</div>

<style>
.activity-day.today { outline: 2px solid var(--green); outline-offset: 1px; }
</style>

<?php include 'footer.php'; ?>