<?php
require_once __DIR__ . '/../../../App/auth.php';
require_login();
$page_title='Configurações';
require __DIR__.'/../../layout/header.php';
?>
<div class="module-page"><div class="config-grid">
<section class="panel"><h3>Estrutura Organizacional</h3><a class="config-link" href="siglas.php">Siglas de apresentação</a><a class="config-link" href="ramos.php">Ramos</a><span class="config-link muted">Unidades</span><span class="config-link muted">Departamentos</span><span class="config-link muted">Funções</span></section>
<section class="panel"><h3>Serviço e Carreira</h3><span class="config-link muted">Patentes</span><span class="config-link muted">Quadro de Serviço</span><span class="config-link muted">Situações de Serviço</span></section>
<section class="panel"><h3>Formação</h3><span class="config-link muted">Instituições</span><span class="config-link muted">Tipos de Formação</span><span class="config-link muted">Níveis de Habilitação</span></section>
<section class="panel"><h3>Dados de Referência</h3><span class="config-link muted">Países</span><span class="config-link muted">Províncias</span><span class="config-link muted">Municípios</span><span class="config-link muted">Estados Civis</span><span class="config-link muted">Graus de Parentesco</span></section>
</div></div>
<?php require __DIR__.'/../../layout/footer.php'; ?>
