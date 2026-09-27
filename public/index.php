<?php
require_once __DIR__ . '/../App/auth.php';
if (session_status() !== PHP_SESSION_ACTIVE) session_start();
if (!empty($_SESSION['user'])) { header('Location: sistema.php'); exit; }

$erro='';
if ($_SERVER['REQUEST_METHOD']==='POST') {
    $username=trim($_POST['username']??'');
    $password=$_POST['password']??'';
    if ($username==='' || $password==='') $erro='Informe o utilizador e a palavra-passe.';
    elseif (login_user($username,$password)) { header('Location: sistema.php'); exit; }
    else $erro='Utilizador ou palavra-passe inválidos.';
}
?>
<!doctype html>
<html lang="pt">
<head>
<meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>Entrar — <?= htmlspecialchars(APP_NAME) ?></title>
<link rel="stylesheet" href="assets/css/app.css">
</head>
<body class="login">
<div class="login-box">
  <div class="sistema-logo" style="margin-bottom:18px">SE</div>
  <h1>Sistema de Gestão de Especialistas</h1>
  <p>Acesso seguro à plataforma de gestão.</p>
  <?php if($erro): ?><div class="erro"><?= htmlspecialchars($erro) ?></div><?php endif; ?>
  <form method="post" autocomplete="on">
    <label for="username">Utilizador</label>
    <input id="username" type="text" name="username" autocomplete="username" required autofocus>
    <label for="password">Palavra-passe</label>
    <input id="password" type="password" name="password" autocomplete="current-password" required>
    <button type="submit">Entrar no sistema</button>
  </form>
</div>
</body>
</html>
