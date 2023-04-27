<?php

  session_start();

  if (isset($_SESSION['user_id'])) {
    header('Location: /login.php');
  }
  require 'database.php';

  if (!empty($_POST['email']) && !empty($_POST['password'])) {
    $records = $conn->prepare('SELECT id, email, password FROM users WHERE email = :email');
    $records->bindParam(':email', $_POST['email']);
    $records->execute();
    $results = $records->fetch(PDO::FETCH_ASSOC);

    $message = '';

    if (count($results) > 0 && password_verify($_POST['password'], $results['password'])) {
      $_SESSION['user_id'] = $results['id'];
      header("Location: /login.php");
    } else {
      $message = 'Usuario o contraseña incorrecta';
    }
  }

?>

<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>Iniciar Sesión</title>
    <link href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">
  </head>
  <body>
    <?php require 'partials/header.php' ?>

    <?php if(!empty($message)): ?>
      <p> <?= $message ?></p>
    <?php endif; ?>

    <h1>Iniciar Sesión</h1>
    <span>o <a href="signup.php">Registrate</a></span>

    <form action="login.php" method="POST">
      <input name="email" type="text" placeholder="Ingresa tú usuario">
      <input name="password" type="password" placeholder="Ingresa tú contraseña">
      <input type="submit" value="Submit">
    </form>
  </body>
</html>