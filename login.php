<?php
require_once 'db.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM users WHERE email = '$email' AND password = '$password'";
    $result = mysqli_query($conn, $sql);
    if (!$result) die("Ошибка запроса: " . mysqli_error($conn));

    if (mysqli_num_rows($result) > 0) {
        $user = mysqli_fetch_assoc($result);
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_name'] = $user['name'];
        $_SESSION['user_email'] = $user['email'];
        header("Location: profile.php");
        exit;
    } else {
        $error = "Неверный email или пароль";
    }
}
?>
<!DOCTYPE html>
<html lang="ru">

<head>
  <meta charset="UTF-8">
  <title>Соловьев</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>

<body class="bg-light d-flex align-items-center justify-content-center" style="min-height: 100vh;">

  <div class="card p-4 shadow-sm" style="width: 360px;">
    <p class="text-muted small text-uppercase mb-3">Блогли</p>
    <h4 class="fw-semibold mb-1">Добро пожаловать</h4>
    <p class="text-muted small mb-4">Войдите в свой аккаунт</p>

    <?php if ($error): ?>
    <div class="alert alert-danger small"><?= $error ?></div>
    <?php endif; ?>

    <form method="POST" action="login.php">
      <div class="mb-3">
        <label class="form-label small">Email</label>
        <input type="email" name="email" class="form-control" placeholder="example@mail.ru">
      </div>
      <div class="mb-2">
        <label class="form-label small">Пароль</label>
        <input type="password" name="password" class="form-control" placeholder="••••••••">
      </div>
      <p class="text-end small mb-3">
        <a href="#" class="text-muted">Забыли пароль?</a>
      </p>
      <button type="submit" class="btn btn-dark w-100">Войти</button>
    </form>

    <p class="text-center text-muted small mt-3 mb-0">
      Нет аккаунта? <a href="register.php" class="text-dark fw-semibold">Зарегистрироваться</a>
    </p>
  </div>

</body>

</html>
