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
        f.id_familiar,
        f.nome,
        f.id_especialista,
        e.id_ramo,
        ds.id_unidade,
        dp.nome_completo AS especialista_nome
    FROM familiares f
    INNER JOIN especialistas e
        ON e.id_especialista = f.id_especialista
    INNER JOIN dados_pessoais dp
        ON dp.id_especialista = e.id_especialista
    LEFT JOIN dados_servico ds
        ON ds.id_especialista = e.id_especialista
    WHERE f.id_familiar = ?
");

$stmt->execute([$id]);

$familiar = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$familiar) {
    die('Familiar não encontrado.');
}

$idEspecialista = (int)$familiar['id_especialista'];

/*
|--------------------------------------------------------------------------
| Verificar acesso
|--------------------------------------------------------------------------
*/

if ($_SESSION['user']['nivel'] === 'RAMO') {

    $stmt = $pdo->prepare("
        SELECT 1
        FROM utilizador_ramo
        WHERE id_utilizador = ?
        AND id_ramo = ?
    ");

    $stmt->execute([
        $_SESSION['user']['id'],
        $familiar['id_ramo']
    ]);

    if (!$stmt->fetchColumn()) {
        http_response_code(403);
        die('Acesso não autorizado.');
    }

} elseif ($_SESSION['user']['nivel'] !== 'GLOBAL') {

    $stmt = $pdo->prepare("
        SELECT 1
        FROM utilizador_unidade
        WHERE id_utilizador = ?
        AND id_unidade = ?
    ");

    $stmt->execute([
        $_SESSION['user']['id'],
        $familiar['id_unidade']
    ]);

    if (!$stmt->fetchColumn()) {
        http_response_code(403);
        die('Acesso não autorizado.');
    }
}

/*
|--------------------------------------------------------------------------
| Eliminar
|--------------------------------------------------------------------------
*/

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $stmt = $pdo->prepare("
        DELETE FROM familiares
        WHERE id_familiar = ?
    ");

    $stmt->execute([$id]);

    header(
        'Location: especialista.php?id=' .
        $idEspecialista .
        '&familiar=eliminado'
    );

    exit;
}
$page_title = "Familiar Eliminar";
require __DIR__ . '/../../layout/header.php';
?>

<div class="module-page module-familiar-eliminar">
<main>

<h1>Eliminar Familiar</h1>

<div class="panel">

<p>
Tem a certeza que pretende eliminar o familiar:
</p>

<h3>
<?=htmlspecialchars($familiar['nome'])?>
</h3>

<p>
Especialista:
<strong>
<?=htmlspecialchars($familiar['especialista_nome'])?>
</strong>
</p>

<p>
<strong>Atenção:</strong>
esta operação não pode ser desfeita.
</p>

<form method="post">

<button type="submit">
Sim, eliminar
</button>

<a href="especialista.php?id=<?=$idEspecialista?>">
Cancelar
</a>

</form>

</div>

</main>
</div>

<?php require __DIR__ . '/../../layout/footer.php'; ?>
