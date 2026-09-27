<?php
if (!isset($page_title)) $page_title = APP_NAME;
$usuario = $_SESSION['user'] ?? [];
$nomeUtilizador = $usuario['nome'] ?? 'Utilizador';
$perfilUtilizador = $usuario['perfil'] ?? '';
$inicialUtilizador = strtoupper(substr(trim($nomeUtilizador), 0, 1));
if ($inicialUtilizador === '') $inicialUtilizador = 'U';
$fotoUtilizador = $usuario['foto'] ?? null;
?>
<!doctype html>
<html lang="pt">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title><?= htmlspecialchars($page_title) ?> — <?= htmlspecialchars(APP_NAME) ?></title>
<link rel="stylesheet" href="<?= htmlspecialchars(app_url('assets/css/app.css')) ?>">
</head>
<body>
<div class="app-frame">
<header class="sistema-topbar">
  <div class="sistema-marca">
    <div class="sistema-logo">SE</div>
    <div class="sistema-titulo-wrap">
      <strong>Sistema de Gestão de Especialistas</strong>
      <small>Gestão integrada de especialistas e recursos</small>
    </div>
  </div>

  <div class="sistema-utilizador">
    <a class="perfil-utilizador perfil-utilizador-link" href="<?= htmlspecialchars(app_url('modulos/utilizador_editar.php?id=' . (int)($usuario['id'] ?? 0))) ?>" title="Editar o meu perfil">
      <?php if ($fotoUtilizador): ?>
        <img class="foto-utilizador" src="<?= htmlspecialchars(app_file_url($fotoUtilizador)) ?>" alt="Fotografia do utilizador">
      <?php else: ?>
        <span class="foto-inicial"><?= htmlspecialchars($inicialUtilizador) ?></span>
      <?php endif; ?>
    </a>
    <div class="dados-utilizador">
      <strong><?= htmlspecialchars($nomeUtilizador) ?></strong>
      <?php if ($perfilUtilizador): ?><small><?= htmlspecialchars($perfilUtilizador) ?></small><?php endif; ?>
    </div>
    <button type="button" class="btn-tema" id="btnTema" title="Alterar modo de apresentação">☾ Escuro</button>
    <a class="btn-sair" href="<?= htmlspecialchars(app_url('logout.php')) ?>">Sair</a>
  </div>
</header>
<?php require __DIR__ . '/sidebar.php'; ?>
<div class="app-shell">
<main class="app-content">
