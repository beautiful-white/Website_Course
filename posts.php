<?php
require_once 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['content'])) {
    $user_id = $_SESSION['user_id'];
    $content = $_POST['content'];
    $sql = "INSERT INTO posts (user_id, content) VALUES ($user_id, '$content')";
    $result = mysqli_query($conn, $sql);
    if (!$result) die("Ошибка добавления поста: " . mysqli_error($conn));
    header("Location: posts.php");
    exit;
}

$posts = [];
$result = mysqli_query($conn, "SELECT posts.*, users.name FROM posts JOIN users ON posts.user_id = users.id ORDER BY posts.created_at DESC");
if (!$result) die("Ошибка получения постов: " . mysqli_error($conn));
while ($row = mysqli_fetch_assoc($result)) {
    $posts[] = $row;
}

$bgColors = ['bg-success', 'bg-primary', 'bg-warning', 'bg-danger', 'bg-info'];

function getInitials($name) {
    $words = explode(' ', trim($name));
    $initials = '';
    foreach ($words as $word) {
        $initials .= mb_strtoupper(mb_substr($word, 0, 1));
    }
    return mb_substr($initials, 0, 2);
}

function timeAgo($datetime) {
    $diff = time() - strtotime($datetime);
    if ($diff < 60) return "только что";
    if ($diff < 3600) return round($diff / 60) . " мин. назад";
    if ($diff < 86400) return round($diff / 3600) . " ч. назад";
    return round($diff / 86400) . " дн. назад";
}

$myInitials = getInitials($_SESSION['user_name']);
?>
<!DOCTYPE html>
<html lang="ru">

<head>
  <meta charset="UTF-8">
  <title>Соловьев</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>

<body class="bg-light">

  <nav class="navbar navbar-light bg-white border-bottom">
    <div class="container">
      <a href="index.php" class="navbar-brand fw-semibold">Блогли</a>
      <a href="profile.php" class="btn btn-outline-secondary btn-sm"><?= $myInitials ?> (<?= $_SESSION['user_name'] ?>)</a>
    </div>
  </nav>

  <div class="container py-4" style="max-width: 600px;">
    <div class="d-flex justify-content-between align-items-center mb-3">
      <h5 class="fw-semibold mb-0">Лента</h5>
      <button class="btn btn-dark btn-sm" onclick="var f = document.getElementById('new-post-form'); f.style.display = f.style.display === 'none' ? 'block' : 'none'">+ Написать</button>
    </div>

    <div id="new-post-form" style="display: none;">
      <div class="card mb-3 shadow-sm">
        <div class="card-body">
          <form method="POST" action="posts.php">
            <div class="mb-3">
              <textarea name="content" class="form-control" rows="3" placeholder="Что у вас нового?"></textarea>
            </div>
            <button type="submit" class="btn btn-dark btn-sm">Опубликовать</button>
          </form>
        </div>
      </div>
    </div>

    <?php if (empty($posts)): ?>
    <div class="text-center text-muted py-5">
      <p>Пока нет постов. Будьте первым!</p>
    </div>
    <?php endif; ?>

    <?php foreach ($posts as $post):
        $color = $bgColors[$post['user_id'] % count($bgColors)];
        $initials = getInitials($post['name']);
        $ago = timeAgo($post['created_at']);
    ?>
    <div class="card mb-3 shadow-sm">
      <div class="card-body">
        <div class="d-flex align-items-center mb-2">
          <div class="rounded-circle <?= $color ?> d-flex align-items-center justify-content-center text-white fw-semibold me-2"
            style="width: 36px; height: 36px; font-size: 12px; flex-shrink: 0;"><?= $initials ?></div>
          <div>
            <p class="fw-semibold mb-0 small"><?= $post['name'] ?></p>
            <p class="text-muted mb-0" style="font-size: 11px;"><?= $ago ?></p>
          </div>
        </div>
        <p class="mb-2"><?= $post['content'] ?></p>
      </div>
    </div>
    <?php endforeach; ?>

  </div>

</body>

</html>
