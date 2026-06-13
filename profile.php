<?php
require_once 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$user_name = $_SESSION['user_name'];

$upload_error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['avatar'])) {
    $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
    $file_type = $_FILES['avatar']['type'];

    if (in_array($file_type, $allowed_types)) {
        $filename = $_FILES['avatar']['name'];
        $destination = 'uploads/' . $filename;
        move_uploaded_file($_FILES['avatar']['tmp_name'], $destination);

        $sql = "UPDATE users SET avatar = '$filename' WHERE id = $user_id";
        $result = mysqli_query($conn, $sql);
        if (!$result) die("Ошибка обновления аватара: " . mysqli_error($conn));

        $_SESSION['user_avatar'] = $filename;
        header("Location: profile.php");
        exit;
    } else {
        $upload_error = "Недопустимый тип файла. Разрешены только изображения.";
    }
}

$result = mysqli_query($conn, "SELECT * FROM users WHERE id = $user_id");
if (!$result) die("Ошибка получения пользователя: " . mysqli_error($conn));
$user = mysqli_fetch_assoc($result);

$avatar = $user['avatar'];

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
      <div>
        <a href="posts.php" class="btn btn-outline-secondary btn-sm me-2">Лента</a>
        <a href="logout.php" class="btn btn-outline-danger btn-sm">Выйти</a>
      </div>
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
            style="width: 64px; height: 64px; font-size: 18px; flex-shrink: 0;
                  <?= $avatar ? "background-image: url('uploads/" . $avatar . "'); background-size: cover; background-position: center;" : "background-image: url('img/profile.jpg'); background-size: cover; background-position: center;" ?>">
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

        <button class="btn btn-outline-secondary btn-sm w-100 mb-3" onclick="document.getElementById('avatar-form').style.display = document.getElementById('avatar-form').style.display === 'none' ? 'block' : 'none'">Редактировать профиль</button>

        <div id="avatar-form" style="display: none;">
          <?php if ($upload_error): ?>
          <div class="alert alert-danger small"><?= $upload_error ?></div>
          <?php endif; ?>
          <form method="POST" action="profile.php" enctype="multipart/form-data" class="mb-3">
            <label class="form-label small">Загрузить аватарку</label>
            <div class="input-group">
              <input type="file" name="avatar" class="form-control form-control-sm">
              <button type="submit" class="btn btn-dark btn-sm">Сохранить</button>
            </div>
          </form>
        </div>

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
