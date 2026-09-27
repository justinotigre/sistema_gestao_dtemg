<?php
require_once __DIR__ . '/../../../App/auth.php';
require_login();


$pdo = db();

$id = (int)($_GET['id'] ?? 0);

if (!$id) {
    header('Location: especialistas.php');
    exit;
}

/*
|--------------------------------------------------------------------------
| Carregar familiar
|--------------------------------------------------------------------------
*/

$stmt = $pdo->prepare("
    SELECT
        f.*,
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
| Graus
|--------------------------------------------------------------------------
*/

$graus = $pdo->query("
    SELECT id_grau_parentesco, nome
    FROM graus_parentesco
    WHERE estado = 'ATIVO'
    ORDER BY nome
")->fetchAll();

$erro = '';

/*
|--------------------------------------------------------------------------
| Actualizar
|--------------------------------------------------------------------------
*/

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    try {

        $nome = trim($_POST['nome'] ?? '');

        if ($nome === '') {
            throw new Exception('O nome do familiar é obrigatório.');
        }

        $idGrau = !empty($_POST['id_grau_parentesco'])
            ? (int)$_POST['id_grau_parentesco']
            : null;

        $stmt = $pdo->prepare("
            UPDATE familiares
            SET
                nome = ?,
                id_grau_parentesco = ?,
                data_nascimento = ?,
                profissao = ?,
                telefone = ?,
                morada = ?,
                observacoes = ?
            WHERE id_familiar = ?
        ");

        $stmt->execute([
            $nome,
            $idGrau,
            $_POST['data_nascimento'] ?: null,
            trim($_POST['profissao'] ?? ''),
            trim($_POST['telefone'] ?? ''),
            trim($_POST['morada'] ?? ''),
            trim($_POST['observacoes'] ?? ''),
            $id
        ]);

        header(
            'Location: especialista.php?id=' .
            $idEspecialista .
            '&familiar=atualizado'
        );

        exit;

    } catch (Throwable $e) {

        $erro = $e->getMessage();
    }
}

function valor($campo)
{
    global $familiar;

    return htmlspecialchars(
        $_POST[$campo] ?? $familiar[$campo] ?? '',
        ENT_QUOTES,
        'UTF-8'
    );
}
$page_title = "Familiar Editar";
require __DIR__ . '/../../layout/header.php';
?>

<div class="module-page module-familiar-editar">
<style>


.module-page.module-familiar-editar .form-box {
    background:#fff;
    padding:25px;
    border-radius:10px;
    max-width:900px;
}

.module-page.module-familiar-editar .form-grid {
    display:grid;
    grid-template-columns:repeat(2,1fr);
    gap:18px;
}

.module-page.module-familiar-editar .form-group {
    display:flex;
    flex-direction:column;
    gap:6px;
}

.module-page.module-familiar-editar .form-group.full {
    grid-column:1/-1;
}

.module-page.module-familiar-editar .form-group input, .module-page.module-familiar-editar .form-group select, .module-page.module-familiar-editar .form-group textarea {
    padding:10px;
    border:1px solid #ccc;
    border-radius:6px;
}

.module-page.module-familiar-editar .form-group textarea {
    min-height:100px;
}

.module-page.module-familiar-editar .acoes {
    margin-top:20px;
    display:flex;
    gap:10px;
}

@media(max-width:700px){
    .module-page.module-familiar-editar .form-grid {
        grid-template-columns:1fr;
    }
}


</style>
<main>

<h1>Editar Familiar</h1>

<p>
Especialista:
<strong>
<?=htmlspecialchars($familiar['especialista_nome'])?>
</strong>
</p>

<p>
<a href="especialista.php?id=<?=$idEspecialista?>">
← Voltar para a ficha
</a>
</p>

<?php if ($erro): ?>

<div class="erro">
<?=htmlspecialchars($erro)?>
</div>

<?php endif; ?>

<div class="form-box">

<form method="post">

<div class="form-grid">

<div class="form-group full">

<label>Nome completo *</label>

<input
    type="text"
    name="nome"
    value="<?=valor('nome')?>"
    required
>

</div>


<div class="form-group">

<label>Grau de parentesco</label>

<select name="id_grau_parentesco">

<option value="">Seleccione</option>

<?php foreach ($graus as $grau): ?>

<option
    value="<?=$grau['id_grau_parentesco']?>"
    <?=(
        (string)($_POST['id_grau_parentesco']
        ?? $familiar['id_grau_parentesco'])
        ===
        (string)$grau['id_grau_parentesco']
    ) ? 'selected' : ''?>
>
<?=htmlspecialchars($grau['nome'])?>
</option>

<?php endforeach; ?>

</select>

</div>


<div class="form-group">

<label>Data de nascimento</label>

<input
    type="date"
    name="data_nascimento"
    value="<?=valor('data_nascimento')?>"
>

</div>


<div class="form-group">

<label>Profissão</label>

<input
    type="text"
    name="profissao"
    value="<?=valor('profissao')?>"
>

</div>


<div class="form-group">

<label>Telefone</label>

<input
    type="text"
    name="telefone"
    value="<?=valor('telefone')?>"
>

</div>


<div class="form-group full">

<label>Morada</label>

<textarea name="morada"><?=valor('morada')?></textarea>

</div>


<div class="form-group full">

<label>Observações</label>

<textarea name="observacoes"><?=valor('observacoes')?></textarea>

</div>

</div>


<div class="acoes">

<button type="submit">
Guardar alterações
</button>

<a href="especialista.php?id=<?=$idEspecialista?>">
Cancelar
</a>

</div>

</form>

</div>

</main>
</div>

<?php require __DIR__ . '/../../layout/footer.php'; ?>
