<?php
require_once 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$user_name = $_SESSION['user_name'];

$result = mysqli_query($conn, "SELECT * FROM posts WHERE user_id = $user_id ORDER BY created_at DESC");
if (!$result) die("Ошибка получения постов: " . mysqli_error($conn));

$userPosts = [];
while ($row = mysqli_fetch_assoc($result)) {
    $userPosts[] = $row;
}

$postsCount = count($userPosts);
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
      <a href="posts.php" class="btn btn-outline-secondary btn-sm">Лента</a>
    </div>
  </nav>

  <div class="container py-4" style="max-width: 600px;">
    <div class="card shadow-sm">
      <div class="bg-secondary" style="height: 80px; background-image: url('img/profile-banner.jpg');
              background-size: cover; background-position: center;">
      </div>
      <div class="card-body">
        <div class="d-flex align-items-center mb-3" style="margin-top: -40px;">
          <div
            class="rounded-circle bg-success d-flex align-items-center justify-content-center text-white fw-semibold border border-3 border-white"
            style="width: 64px; height: 64px; font-size: 18px;
                  flex-shrink: 0; background-image: url('img/profile.jpg'); background-size: cover; background-position: center;">
          </div>
        </div>
        <h5 class="fw-semibold mb-0"><?= $user_name ?></h5>
        <p class="text-muted small mb-3">@soloviev · Москва</p>
        <p class="mb-3">Интересуюсь технологиями, пишу о том, что важно.</p>

        <div class="d-flex gap-4 mb-3">
          <div><strong><?= $postsCount ?></strong> <span class="text-muted small">поста</span></div>
          <div><strong>318</strong> <span class="text-muted small">подписчиков</span></div>
          <div><strong>124</strong> <span class="text-muted small">подписок</span></div>
        </div>

        <button class="btn btn-outline-secondary btn-sm w-100 mb-3">Редактировать профиль</button>

        <hr>
        <h6 class="fw-semibold mb-3">Мои посты</h6>

        <?php if (empty($userPosts)): ?>
        <p class="text-muted small">Вы ещё не написали ни одного поста.</p>
        <?php endif; ?>

        <?php foreach ($userPosts as $post): ?>
        <div class="card mb-2 p-3">
          <p class="text-muted small mb-0"><?= $post['content'] ?></p>
        </div>
        <?php endforeach; ?>

      </div>
    </div>
  </div>

</body>

</html>
