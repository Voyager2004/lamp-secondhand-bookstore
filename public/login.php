<?php
session_start();
require_once __DIR__.'/../config/db.php';
if ($_SERVER['REQUEST_METHOD']==='POST') {
    $email = $_POST['email'] ?? '';
    $pwd   = $_POST['password'] ?? '';
    $stmt = $pdo->prepare('SELECT uid,password_hash FROM user WHERE email=?');
    $stmt->execute([$email]);
    $u = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($u && password_verify($pwd, $u['password_hash'])) {
        $_SESSION['uid']=$u['uid'];
        header('Location: dashboard.php'); exit;
    }
    $error = '邮箱或密码错误';
}
?>
<!doctype html>
<html lang="zh">
<head><meta charset="utf-8"><title>登录</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"></head>
<body class="container py-4">
<h1>登录</h1>
<?php if (!empty($error)): ?><div class="alert alert-danger"><?= $error ?></div><?php endif; ?>
<form method="post">
  <div class="mb-3"><label class="form-label">Email</label><input type="email" name="email" class="form-control" required></div>
  <div class="mb-3"><label class="form-label">密码</label><input type="password" name="password" class="form-control" required></div>
  <button class="btn btn-primary">登录</button>
  <a href="register.php" class="btn btn-link">没有账号？注册</a>
</form>
</body></html>
