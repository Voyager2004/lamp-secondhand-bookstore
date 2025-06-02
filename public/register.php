<?php
require_once __DIR__.'/../config/db.php';
if ($_SERVER['REQUEST_METHOD']==='POST') {
    $nick = $_POST['nickname'] ?? '';
    $email= $_POST['email'] ?? '';
    $pwd  = $_POST['password'] ?? '';
    $hash = password_hash($pwd, PASSWORD_DEFAULT);
    try {
        $stmt = $pdo->prepare('INSERT INTO user(nickname,email,password_hash) VALUES(?,?,?)');
        $stmt->execute([$nick,$email,$hash]);
        header('Location: login.php'); exit;
    } catch (PDOException $e) {
        $error='邮箱已存在';
    }
}
?>
<!doctype html><html lang="zh"><head><meta charset="utf-8"><title>注册</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"></head>
<body class="container py-4">
<h1>注册</h1>
<?php if (!empty($error)): ?><div class="alert alert-danger"><?= $error ?></div><?php endif; ?>
<form method="post">
<div class="mb-3"><label class="form-label">昵称</label><input type="text" name="nickname" class="form-control" required></div>
<div class="mb-3"><label class="form-label">Email</label><input type="email" name="email" class="form-control" required></div>
<div class="mb-3"><label class="form-label">密码</label><input type="password" name="password" class="form-control" required></div>
<button class="btn btn-primary">注册</button>
</form></body></html>
