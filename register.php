<?php
require_once 'db.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    $sql = "INSERT INTO users (name, email, password) VALUES ('$name', '$email', '$password')";
    $result = mysqli_query($conn, $sql);
    if (!$result) {
        $error = "Ошибка регистрации: " . mysqli_error($conn);
    } else {
        header("Location: login.php");
        exit;
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
    <h4 class="fw-semibold mb-1">Регистрация</h4>
    <p class="text-muted small mb-4">Создайте аккаунт, это бесплатно</p>

    <?php if ($error): ?>
    <div class="alert alert-danger small"><?= $error ?></div>
    <?php endif; ?>

    <form method="POST" action="register.php">
      <div class="mb-3">
        <label class="form-label small">Имя</label>
        <input type="text" name="name" class="form-control" placeholder="Соловьев Р.С.">
      </div>
      <div class="mb-3">
        <label class="form-label small">Email</label>
        <input type="email" name="email" class="form-control" placeholder="example@mail.ru">
      </div>
      <div class="mb-3">
        <label class="form-label small">Пароль</label>
        <input type="password" name="password" class="form-control" placeholder="••••••••">
      </div>
      <button type="submit" class="btn btn-dark w-100">Создать аккаунт</button>
    </form>

    <p class="text-center text-muted small mt-3 mb-0">
      Уже есть аккаунт? <a href="login.php" class="text-dark fw-semibold">Войти</a>
    </p>
  </div>

</body>

</html>
