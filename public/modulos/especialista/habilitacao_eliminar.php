<?php
require_once __DIR__ . '/../../../App/auth.php';
require_login();


$pdo = db();

$id = (int)($_GET['id'] ?? $_POST['id'] ?? 0);

if (!$id) {
    header('Location: ' . app_url('modulos/dashboard.php'));
    exit;
}

$stmt = $pdo->prepare("
    SELECT
        h.id_habilitacao,
        h.id_especialista,
        h.curso,
        e.id_ramo,
        ds.id_unidade,
        dp.nome_completo
    FROM habilitacoes_literarias h
    INNER JOIN especialistas e
        ON e.id_especialista = h.id_especialista
    INNER JOIN dados_pessoais dp
        ON dp.id_especialista = e.id_especialista
    LEFT JOIN dados_servico ds
        ON ds.id_especialista = e.id_especialista
    WHERE h.id_habilitacao = ?
");

$stmt->execute([$id]);

$habilitacao = $stmt->fetch();

if (!$habilitacao) {
    exit('Habilitação não encontrada.');
}

$nivel = $_SESSION['user']['nivel'] ?? '';

/* ============================================================
   CONTROLO DE ACESSO
============================================================ */

if ($nivel === 'RAMO') {

    $stmt = $pdo->prepare("
        SELECT 1
        FROM utilizador_ramo
        WHERE id_utilizador = ?
          AND id_ramo = ?
    ");

    $stmt->execute([
        $_SESSION['user']['id'],
        $habilitacao['id_ramo']
    ]);

    if (!$stmt->fetchColumn()) {
        http_response_code(403);
        exit('Acesso não autorizado.');
    }
}

if ($nivel === 'UNIDADE') {

    $stmt = $pdo->prepare("
        SELECT 1
        FROM utilizador_unidade
        WHERE id_utilizador = ?
          AND id_unidade = ?
    ");

    $stmt->execute([
        $_SESSION['user']['id'],
        $habilitacao['id_unidade']
    ]);

    if (!$stmt->fetchColumn()) {
        http_response_code(403);
        exit('Acesso não autorizado.');
    }
}

/* ============================================================
   ELIMINAÇÃO
============================================================ */

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $stmt = $pdo->prepare("
        DELETE FROM habilitacoes_literarias
        WHERE id_habilitacao = ?
    ");

    $stmt->execute([$id]);

    header(
        'Location: especialista.php?id=' .
        (int)$habilitacao['id_especialista'] .
        '&habilitacao=eliminada'
    );

    exit;
}

function e($v) {
    return htmlspecialchars((string)$v, ENT_QUOTES, 'UTF-8');
}
$page_title = "Habilitacao Eliminar";
require __DIR__ . '/../../layout/header.php';
?>

<div class="module-page module-habilitacao-eliminar">
<style>


.module-page.module-habilitacao-eliminar .confirm {
    max-width:650px;
    margin:60px auto;
    padding:30px;
    background:#fff;
    border-radius:12px;
    box-shadow:0 3px 15px rgba(0,0,0,.08);
}

.module-page.module-habilitacao-eliminar .actions {
    margin-top:25px;
    display:flex;
    gap:10px;
}

.module-page.module-habilitacao-eliminar .btn {
    padding:11px 18px;
    border-radius:7px;
    text-decoration:none;
    border:0;
    cursor:pointer;
}

.module-page.module-habilitacao-eliminar .btn-danger {
    background:#b42318;
    color:#fff;
}

.module-page.module-habilitacao-eliminar .btn-secondary {
    background:#eee;
    color:#222;
}


</style>
<main>

<div class="confirm">

<h1>Eliminar Habilitação</h1>

<p>
Tem certeza que deseja eliminar esta habilitação literária?
</p>

<p>
<strong>Especialista:</strong>
<?= e($habilitacao['nome_completo']) ?>
</p>

<p>
<strong>Curso:</strong>
<?= e($habilitacao['curso']) ?>
</p>

<form method="post">

<input
type="hidden"
name="id"
value="<?= $id ?>"
>

<div class="actions">

<button
type="submit"
class="btn btn-danger"
>
Sim, eliminar
</button>

<a
href="especialista.php?id=<?= (int)$habilitacao['id_especialista'] ?>"
class="btn btn-secondary"
>
Cancelar
</a>

</div>

</form>

</div>

</main>
</div>

<?php require __DIR__ . '/../../layout/footer.php'; ?>
