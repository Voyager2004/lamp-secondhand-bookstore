<?php
session_start();
if (empty($_SESSION['uid'])) { header('Location: login.php'); exit; }

require_once __DIR__.'/../config/db.php';
$msg = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title  = trim($_POST['title']  ?? '');
    $author = trim($_POST['author'] ?? '');
    $price  = (float)($_POST['price'] ?? 0);
    $stock  = (int)($_POST['stock'] ?? 1);

    if ($title && $price > 0) {
        $stmt = $pdo->prepare('INSERT INTO book(uid,title,author,price,stock) VALUES (?,?,?,?,?)');
        $stmt->execute([$_SESSION['uid'], $title, $author, $price, $stock]);
        header('Location: index.php'); exit;
    }
    $msg = '请填写完整且合法的书目信息';
}
?>
<!doctype html><html lang="zh"><head><meta charset="utf-8">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<title>发布图书</title></head><body class="container py-4">
<h1 class="mb-3">发布图书</h1>
<?php if ($msg): ?><div class="alert alert-danger"><?=$msg?></div><?php endif; ?>
<form method="post" class="w-50">
  <div class="mb-3"><label class="form-label">书名</label>
    <input type="text" name="title" class="form-control" required></div>
  <div class="mb-3"><label class="form-label">作者</label>
    <input type="text" name="author" class="form-control"></div>
  <div class="mb-3"><label class="form-label">价格 (¥)</label>
    <input type="number" step="0.01" min="0.01" name="price" class="form-control" required></div>
  <div class="mb-3"><label class="form-label">库存</label>
    <input type="number" min="1" name="stock" class="form-control" value="1" required></div>
  <button class="btn btn-success">发布</button>
  <a href="index.php" class="btn btn-link">取消</a>
</form>
</body></html>
