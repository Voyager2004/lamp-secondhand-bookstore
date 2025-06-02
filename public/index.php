<?php
session_start();
require_once __DIR__.'/../config/db.php';

$uid = $_SESSION['uid'] ?? null;

$keyword = trim($_GET['q'] ?? '');

if ($keyword === '') {
    // 无关键词 → 列出全部在售
    $sql  = 'SELECT bid,title,author,price
             FROM book WHERE status="on_sale"
             ORDER BY created_at DESC';
    $stmt = $pdo->query($sql);
} else {
    $sql  = 'SELECT bid,title,author,price
             FROM book
             WHERE status="on_sale" AND
                   (title LIKE :kw OR author LIKE :kw)
             ORDER BY created_at DESC';
    $stmt = $pdo->prepare($sql);
    $like = "%{$keyword}%";
    $stmt->bindParam(':kw', $like, PDO::PARAM_STR);
    $stmt->execute();
}
$books = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!doctype html>
<html lang="zh">
<head>
  <meta charset="utf-8">
  <title>校园二手书</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container py-4">
<nav class="mb-4">
  <?php if ($uid): ?>
    <a class="btn btn-success me-2" href="book_create.php">发布图书</a>
    <a class="btn btn-success me-2" href="dashboard.php">个人中心</a>
    <a class="btn btn-success me-2" href="logout.php">退出</a>
  <?php else: ?>
    <a class="btn btn-primary me-2" href="login.php">登录</a>
    <a class="btn btn-outline-primary" href="register.php">注册</a>
  <?php endif; ?>
</nav>

<form class="input-group mb-3" method="get" action="index.php" style="max-width:400px;">
  <input type="text" class="form-control" name="q"
         placeholder="按书名或作者关键词搜索"
         value="<?=htmlspecialchars($keyword)?>">
  <button class="btn btn-outline-secondary" type="submit">搜索</button>
  <?php if ($keyword !== ''): ?>
    <a class="btn btn-link" href="index.php">清空</a>
  <?php endif; ?>
</form>

<h1 class="mb-3">在售图书<?= $keyword ? "：搜索“".htmlspecialchars($keyword)."”" : '' ?></h1>
  <table class="table table-striped">
    <thead><tr><th>书名</th><th>作者</th><th>价格</th><th></th></tr></thead>
    <tbody>
      <?php foreach ($books as $b): ?>
      <tr>
        <td><?= htmlspecialchars($b['title']) ?></td>
        <td><?= htmlspecialchars($b['author']) ?></td>
        <td>￥<?= $b['price'] ?></td>
        <td><a class="btn btn-sm btn-primary" href="login.php">查看</a></td>
      </tr>
      <?php endforeach; ?>
      <?php if (!$books): ?>
      <tr><td colspan="3" class="text-center text-muted">暂无匹配结果</td></tr>
      <?php endif; ?>
    </tbody>
  </table>
</body>
</html>
