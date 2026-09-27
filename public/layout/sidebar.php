<?php
$u = $_SESSION['user'] ?? [];
$nivel = $u['nivel'] ?? '';
$scriptAtual = str_replace('\\','/', $_SERVER['SCRIPT_NAME'] ?? '');
$moduloAtual = basename($scriptAtual);
?>
<aside class="sistema-sidebar" aria-label="Menu principal">
  <div class="sidebar-section-title">Principal</div>
  <nav class="sidebar-nav">
    <a class="sidebar-link <?= $moduloAtual === 'dashboard.php' ? 'ativo' : '' ?>" href="<?= htmlspecialchars(app_url('modulos/dashboard.php')) ?>">
      <span class="sidebar-icon">⌂</span><span>Dashboard</span>
    </a>
    <a class="sidebar-link <?= $moduloAtual === 'especialistas.php' ? 'ativo' : '' ?>" href="<?= htmlspecialchars(app_url('modulos/especialista/especialistas.php')) ?>">
      <span class="sidebar-icon">♙</span><span>Especialistas</span>
    </a>
    <a class="sidebar-link <?= $moduloAtual === 'equipamentos.php' ? 'ativo' : '' ?>" href="<?= htmlspecialchars(app_url('modulos/equipamentos.php')) ?>">
      <span class="sidebar-icon">▣</span><span>Equipamentos</span>
    </a>
    <a class="sidebar-link <?= $moduloAtual === 'utilizadores.php' ? 'ativo' : '' ?>" href="<?= htmlspecialchars(app_url('modulos/utilizadores.php')) ?>">
      <span class="sidebar-icon">♧</span><span>Utilizadores</span>
    </a>
  </nav>

  <div class="sidebar-separator"></div>
  <div class="sidebar-section-title">Configurações</div>
  <nav class="sidebar-nav sidebar-config">
    <a class="sidebar-link <?= $moduloAtual === 'configuracoes.php' ? 'ativo' : '' ?>" href="<?= htmlspecialchars(app_url('modulos/configuracoes/configuracoes.php')) ?>"><span class="sidebar-icon">⚙</span><span>Configurações</span></a>
  </nav>

  <div class="sidebar-info">
    <span class="sidebar-info-label">Perfil de acesso</span>
    <strong><?= htmlspecialchars($u['perfil'] ?? 'Utilizador') ?></strong>
    <small><?= htmlspecialchars($nivel ?: '—') ?></small>
  </div>
</aside>
