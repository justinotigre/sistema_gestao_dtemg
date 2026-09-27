<?php
require_once __DIR__ . '/../../../App/auth.php';
require_login();


$pdo = db();

$id = (int)($_GET['id'] ?? 0);

if (!$id) {
    header('Location: especialistas.php');
    exit;
}

$stmt = $pdo->prepare("
    SELECT
        fo.id_formacao,
        fo.id_especialista,
        fo.designacao,
        e.id_ramo,
        ds.id_unidade,
        dp.nome_completo
    FROM formacoes fo
    JOIN especialistas e
        ON e.id_especialista = fo.id_especialista
    JOIN dados_pessoais dp
        ON dp.id_especialista = e.id_especialista
    LEFT JOIN dados_servico ds
        ON ds.id_especialista = e.id_especialista
    WHERE fo.id_formacao = ?
");

$stmt->execute([$id]);

$formacao = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$formacao) {
    die('Formação não encontrada.');
}


/* Acesso */

if ($_SESSION['user']['nivel'] === 'RAMO') {

    $stmt = $pdo->prepare("
        SELECT 1 FROM utilizador_ramo
        WHERE id_utilizador = ?
        AND id_ramo = ?
    ");

    $stmt->execute([
        $_SESSION['user']['id'],
        $formacao['id_ramo']
    ]);

    if (!$stmt->fetchColumn()) {
        die('Acesso não autorizado.');
    }

} elseif ($_SESSION['user']['nivel'] === 'UNIDADE') {

    $stmt = $pdo->prepare("
        SELECT 1 FROM utilizador_unidade
        WHERE id_utilizador = ?
        AND id_unidade = ?
    ");

    $stmt->execute([
        $_SESSION['user']['id'],
        $formacao['id_unidade']
    ]);

    if (!$stmt->fetchColumn()) {
        die('Acesso não autorizado.');
    }
}


if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $stmt = $pdo->prepare("
        DELETE FROM formacoes
        WHERE id_formacao = ?
    ");

    $stmt->execute([$id]);

    header(
        'Location: especialista.php?id=' .
        $formacao['id_especialista'] .
        '&formacao=eliminada#formacoes'
    );

    exit;
}
$page_title = "Formacao Eliminar";
require __DIR__ . '/../../layout/header.php';
?>

<div class="module-page module-formacao-eliminar">
<main>

<div class="panel">

<h1>Eliminar Formação</h1>

<p>

Tem certeza que deseja eliminar a formação:

<strong>
<?=htmlspecialchars($formacao['designacao'])?>
</strong>

?

</p>

<p>

Especialista:

<strong>
<?=htmlspecialchars($formacao['nome_completo'])?>
</strong>

</p>


<form method="post">

<button type="submit">
Sim, eliminar
</button>

<a href="especialista.php?id=<?=$formacao['id_especialista']?>#formacoes">
Cancelar
</a>

</form>

</div>

</main>
</div>

<?php require __DIR__ . '/../../layout/footer.php'; ?>
