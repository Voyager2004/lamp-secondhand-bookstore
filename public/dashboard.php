<?php
session_start();
if (empty($_SESSION['uid'])) { header('Location: login.php'); exit; }

require_once __DIR__.'/../config/db.php';
$uid = $_SESSION['uid'];
$myBooks = $pdo->prepare('SELECT bid,title,author,price,status,stock,created_at FROM book WHERE uid=? ORDER BY created_at DESC');
$myBooks->execute([$uid]);
$rows = $myBooks->fetchAll(PDO::FETCH_ASSOC);
?>
<!doctype html><html lang="zh"><head><meta charset="utf-8">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<title>个人中心</title></head>
<body class="container py-4">
<h1 class="mb-3">我的发布</h1>
<table class="table table-hover">
<thead><tr><th>ID</th><th>书名</th><th>作者</th><th>价格</th><th>库存</th><th>状态</th><th>时间</th></tr></thead>
<tbody>
<?php foreach ($rows as $r): ?>
<tr>
  <td><?=$r['bid']?></td>
  <td><?=htmlspecialchars($r['title'])?></td>
  <td><?=htmlspecialchars($r['author'])?></td>
  <td>¥<?=$r['price']?></td>
  <td><?=$r['stock']?></td>
  <td><?=$r['status']?></td>
  <td><?=$r['created_at']?></td>
</tr>
<?php endforeach; ?>
</tbody></table>
<a href="index.php" class="btn btn-link">返回首页</a>
</body></html>
