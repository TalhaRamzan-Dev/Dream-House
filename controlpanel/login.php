<?php
require_once __DIR__ . '/includes/config.php';

if (!empty($_SESSION['admin_id'])) {
    header('Location: dashboard.php');
    exit;
}

$error = '';
$usernameVal = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    $usernameVal = htmlspecialchars($username, ENT_QUOTES, 'UTF-8');

    if ($username === '' || $password === '') {
        $error = "Please enter username and password.";
    } else {
        $stmt = $mysqli->prepare("SELECT id, username, password FROM admins WHERE username = ? LIMIT 1");
        if ($stmt) {
            $stmt->bind_param("s", $username);
            $stmt->execute();
            $user = $stmt->get_result()->fetch_assoc();
            $stmt->close();

            if ($user && password_verify($password, $user['password'])) {
                $_SESSION['admin_id'] = $user['id'];
                $_SESSION['admin_user'] = $user['username'];
                $_SESSION['LAST_ACTIVITY'] = time();
                header('Location: dashboard.php');
                exit;
            } else {
                $error = "Invalid username or password.";
            }
        } else {
            $error = "Login failed (DB).";
        }
    }
}
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>Admin Login</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>body{background:#f7f8fb}</style>
</head>
<body class="d-flex align-items-center" style="min-height:100vh;">
<div class="container">
  <div class="row justify-content-center">
    <div class="col-md-5">
      <div class="card shadow-sm p-4">
        <h4 class="mb-3">Admin Login</h4>
        <?php if (!empty($_GET['timeout'])): ?>
          <div class="alert alert-warning">Session expired due to inactivity. Please log in again.</div>
        <?php endif; ?>
        <?php if ($error): ?>
          <div class="alert alert-danger"><?= esc($error) ?></div>
        <?php endif; ?>
        <form method="post" novalidate>
          <div class="mb-2">
            <input name="username" class="form-control" placeholder="Username" required value="<?= $usernameVal ?>">
          </div>
          <div class="mb-2">
            <input name="password" type="password" class="form-control" placeholder="Password" required>
          </div>
          <button class="btn btn-primary w-100">Login</button>
        </form>
      </div>
    </div>
  </div>
</div>
</body>
</html>
