<?php
$pageTitle = "Welcome";
include 'header.php';

if (array_key_exists('user_id', $_SESSION)) {
    header("location: home.php");
    exit();
}

// Map error codes to friendly messages
$errors = [
    'emptyinput'  => 'Please fill in both fields.',
    'wronglogin'  => 'Incorrect username or password.',
];
$errorMsg = isset($_GET['error']) ? ($errors[$_GET['error']] ?? '') : '';
?>
<body class="auth-page">

<div class="auth-card">
  <!-- Logo with lightning bolt -->
  <div class="auth-logo">
    <span class="bolt">⚡</span>
    <div class="wordmark">FLASH <span>CARTEL</span></div>
  </div>

  <?php if ($errorMsg): ?>
    <div class="msg msg-error"><?= htmlspecialchars($errorMsg) ?></div>
  <?php endif; ?>

  <!-- Choice view shown by default; JS swaps panels -->
  <div id="view-choice">
    <p class="auth-tagline">Your AI-powered flashcard study companion.</p>
    <button class="choice-btn choice-login" onclick="show('login')">Login</button>
    <button class="choice-btn choice-signup" onclick="show('register')">Sign Up to Flash Cartel</button>
  </div>

  <!-- Login form -->
  <div id="view-login" style="display:none">
    <p style="margin:14px 0 18px;font-size:13px;color:var(--green-dim);">
      <span style="cursor:pointer;text-decoration:underline" onclick="show('choice')">← Back</span>
      &nbsp; Login
    </p>
    <form method="POST" action="includes/login_inc.php">
      <label>Username</label>
      <input type="text" name="username" placeholder="Your username…" required autofocus>
      <label>Password</label>
      <input type="password" name="password" placeholder="Your password…" required>
      <input type="submit" value="Login →">
    </form>
    <div class="mt-2 text-muted" style="text-align:center">
      <a href="forgot.php">Forgot password?</a>
    </div>
  </div>

  <!-- Register form -->
  <div id="view-register" style="display:none">
    <p style="margin:14px 0 18px;font-size:13px;color:var(--green-dim);">
      <span style="cursor:pointer;text-decoration:underline" onclick="show('choice')">← Back</span>
      &nbsp; Create Account
    </p>
    <form method="POST" action="includes/register_inc.php">
      <label>Username <span style="color:var(--text-muted);font-size:11px">(3–20 letters/numbers)</span></label>
      <input type="text" name="username" placeholder="Choose a username…" required>
      <label>Email Address</label>
      <input type="email" name="email" placeholder="your@email.com" required>
      <label>Password <span style="color:var(--text-muted);font-size:11px">(≥8 chars, upper, lower, number)</span></label>
      <input type="password" name="password" placeholder="Choose a password…" required>
      <label>Confirm Password</label>
      <input type="password" name="passwordRepeat" placeholder="Repeat password…" required>
      <input type="submit" value="Create Account →">
    </form>
  </div>
</div>

<script>
// Auto-open login/register panel if there's an error
const err = <?= json_encode($_GET['error'] ?? '') ?>;
if (err === 'wronglogin' || err === 'emptyinput') show('login');

function show(view) {
  ['choice','login','register'].forEach(v => {
    document.getElementById('view-' + v).style.display = v === view ? '' : 'none';
  });
}
</script>

<?php include 'footer.php'; ?>