<?php
require_once __DIR__ . '/../../../App/auth.php';
require_login();


$pdo = db();

$id_especialista = (int)($_GET['id_especialista'] ?? 0);

if (!$id_especialista) {
    header('Location: especialistas.php');
    exit;
}

/* Especialista */
$stmt = $pdo->prepare("
    SELECT e.id_especialista, e.id_ramo, ds.id_unidade,
           dp.nome_completo
    FROM especialistas e
    JOIN dados_pessoais dp
        ON dp.id_especialista = e.id_especialista
    LEFT JOIN dados_servico ds
        ON ds.id_especialista = e.id_especialista
    WHERE e.id_especialista = ?
");
$stmt->execute([$id_especialista]);

$especialista = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$especialista) {
    die('Especialista não encontrado.');
}

/* Controlo de acesso */
if ($_SESSION['user']['nivel'] === 'RAMO') {

    $stmt = $pdo->prepare("
        SELECT 1 FROM utilizador_ramo
        WHERE id_utilizador = ? AND id_ramo = ?
    ");
    $stmt->execute([
        $_SESSION['user']['id'],
        $especialista['id_ramo']
    ]);

    if (!$stmt->fetchColumn()) {
        die('Acesso não autorizado.');
    }

} elseif ($_SESSION['user']['nivel'] === 'UNIDADE') {

    $stmt = $pdo->prepare("
        SELECT 1 FROM utilizador_unidade
        WHERE id_utilizador = ? AND id_unidade = ?
    ");
    $stmt->execute([
        $_SESSION['user']['id'],
        $especialista['id_unidade']
    ]);

    if (!$stmt->fetchColumn()) {
        die('Acesso não autorizado.');
    }
}

/* Listas */
$tipos = $pdo->query("
    SELECT id_tipo_formacao, nome
    FROM tipos_formacao
    ORDER BY nome
")->fetchAll(PDO::FETCH_ASSOC);

$instituicoes = $pdo->query("
    SELECT id_instituicao, nome
    FROM instituicoes
    ORDER BY nome
")->fetchAll(PDO::FETCH_ASSOC);

$paises = $pdo->query("
    SELECT id_pais, nome
    FROM paises
    WHERE estado = 'ATIVO'
    ORDER BY nome
")->fetchAll(PDO::FETCH_ASSOC);

$erro = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $categoria = $_POST['categoria'] ?? '';
    $id_tipo_formacao = !empty($_POST['id_tipo_formacao'])
        ? (int)$_POST['id_tipo_formacao']
        : null;

    $designacao = trim($_POST['designacao'] ?? '');
    $id_instituicao = !empty($_POST['id_instituicao'])
        ? (int)$_POST['id_instituicao']
        : null;

    $id_pais = !empty($_POST['id_pais'])
        ? (int)$_POST['id_pais']
        : null;

    $local = trim($_POST['local'] ?? '');
    $data_inicio = $_POST['data_inicio'] ?: null;
    $data_fim = $_POST['data_fim'] ?: null;
    $duracao = trim($_POST['duracao'] ?? '');
    $certificado = trim($_POST['certificado'] ?? '');
    $observacoes = trim($_POST['observacoes'] ?? '');

    if (!in_array($categoria, ['MILITAR', 'GERAL'], true)) {
        $erro = 'Selecione uma categoria válida.';
    } elseif ($designacao === '') {
        $erro = 'A designação da formação é obrigatória.';
    } elseif ($data_inicio && $data_fim && $data_fim < $data_inicio) {
        $erro = 'A data final não pode ser anterior à data inicial.';
    }

    if (!$erro) {

        $stmt = $pdo->prepare("
            INSERT INTO formacoes (
                id_especialista,
                categoria,
                id_tipo_formacao,
                designacao,
                id_instituicao,
                id_pais,
                local,
                data_inicio,
                data_fim,
                duracao,
                certificado,
                observacoes
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");

        $stmt->execute([
            $id_especialista,
            $categoria,
            $id_tipo_formacao,
            $designacao,
            $id_instituicao,
            $id_pais,
            $local ?: null,
            $data_inicio,
            $data_fim,
            $duracao ?: null,
            $certificado ?: null,
            $observacoes ?: null
        ]);

        header(
            'Location: especialista.php?id=' .
            $id_especialista .
            '&formacao=adicionada#formacoes'
        );
        exit;
    }
}
$page_title = "Formacao Nova";
require __DIR__ . '/../../layout/header.php';
?>

<div class="module-page module-formacao-nova">
<style>

.module-page.module-formacao-nova .form-box {
    max-width:900px;
    margin:auto;
    background:#fff;
    padding:25px;
    border-radius:10px;
}

.module-page.module-formacao-nova .form-grid {
    display:grid;
    grid-template-columns:1fr 1fr;
    gap:15px;
}

.module-page.module-formacao-nova .form-group {
    display:flex;
    flex-direction:column;
    gap:6px;
}

.module-page.module-formacao-nova .form-group.full {
    grid-column:1/-1;
}

.module-page.module-formacao-nova .form-group input, .module-page.module-formacao-nova .form-group select, .module-page.module-formacao-nova .form-group textarea {
    padding:10px;
    border:1px solid #ccc;
    border-radius:6px;
}

.module-page.module-formacao-nova .form-actions {
    margin-top:20px;
    display:flex;
    gap:10px;
}

.module-page.module-formacao-nova .erro {
    background:#f8dddd;
    padding:12px;
    border-radius:6px;
    margin-bottom:15px;
}

@media(max-width:700px){
    .module-page.module-formacao-nova .form-grid {
        grid-template-columns:1fr;
    }

    .module-page.module-formacao-nova .form-group.full {
        grid-column:auto;
    }
}

</style>
<main>

<div class="form-box">

<h1>Nova Formação</h1>

<p>
Especialista:
<strong>
<?=htmlspecialchars($especialista['nome_completo'])?>
</strong>
</p>

<?php if ($erro): ?>

<div class="erro">
<?=htmlspecialchars($erro)?>
</div>

<?php endif; ?>

<form method="post">

<div class="form-grid">

<div class="form-group">

<label>Categoria *</label>

<select name="categoria" required>

<option value="">Selecione</option>

<option
    value="MILITAR"
    <?=($_POST['categoria'] ?? '') === 'MILITAR' ? 'selected' : ''?>
>
Militar
</option>

<option
    value="GERAL"
    <?=($_POST['categoria'] ?? '') === 'GERAL' ? 'selected' : ''?>
>
Geral
</option>

</select>

</div>


<div class="form-group">

<label>Tipo de formação</label>

<select name="id_tipo_formacao">

<option value="">Selecione</option>

<?php foreach ($tipos as $tipo): ?>

<option
    value="<?=$tipo['id_tipo_formacao']?>"
    <?=((int)($_POST['id_tipo_formacao'] ?? 0) === (int)$tipo['id_tipo_formacao']) ? 'selected' : ''?>
>
<?=htmlspecialchars($tipo['nome'])?>
</option>

<?php endforeach; ?>

</select>

</div>


<div class="form-group full">

<label>Designação da formação *</label>

<input
    type="text"
    name="designacao"
    maxlength="200"
    required
    value="<?=htmlspecialchars($_POST['designacao'] ?? '')?>"
    placeholder="Ex.: Curso de Especialização em..."
>

</div>


<div class="form-group">

<label>Instituição</label>

<select name="id_instituicao">

<option value="">Selecione</option>

<?php foreach ($instituicoes as $instituicao): ?>

<option
    value="<?=$instituicao['id_instituicao']?>"
    <?=((int)($_POST['id_instituicao'] ?? 0) === (int)$instituicao['id_instituicao']) ? 'selected' : ''?>
>
<?=htmlspecialchars($instituicao['nome'])?>
</option>

<?php endforeach; ?>

</select>

</div>


<div class="form-group">

<label>País</label>

<select name="id_pais">

<option value="">Selecione</option>

<?php foreach ($paises as $pais): ?>

<option
    value="<?=$pais['id_pais']?>"
    <?=((int)($_POST['id_pais'] ?? 0) === (int)$pais['id_pais']) ? 'selected' : ''?>
>
<?=htmlspecialchars($pais['nome'])?>
</option>

<?php endforeach; ?>

</select>

</div>


<div class="form-group">

<label>Local</label>

<input
    type="text"
    name="local"
    maxlength="150"
    value="<?=htmlspecialchars($_POST['local'] ?? '')?>"
>

</div>


<div class="form-group">

<label>Duração</label>

<input
    type="text"
    name="duracao"
    maxlength="100"
    value="<?=htmlspecialchars($_POST['duracao'] ?? '')?>"
    placeholder="Ex.: 6 meses / 240 horas"
>

</div>


<div class="form-group">

<label>Data de início</label>

<input
    type="date"
    name="data_inicio"
    value="<?=htmlspecialchars($_POST['data_inicio'] ?? '')?>"
>

</div>


<div class="form-group">

<label>Data de conclusão</label>

<input
    type="date"
    name="data_fim"
    value="<?=htmlspecialchars($_POST['data_fim'] ?? '')?>"
>

</div>


<div class="form-group">

<label>Certificado</label>

<input
    type="text"
    name="certificado"
    maxlength="100"
    value="<?=htmlspecialchars($_POST['certificado'] ?? '')?>"
    placeholder="Número ou referência"
>

</div>


<div class="form-group full">

<label>Observações</label>

<textarea
    name="observacoes"
    rows="5"
><?=htmlspecialchars($_POST['observacoes'] ?? '')?></textarea>

</div>

</div>


<div class="form-actions">

<button type="submit">
Guardar formação
</button>

<a href="especialista.php?id=<?=$id_especialista?>#formacoes">
Cancelar
</a>

</div>

</form>

</div>

</main>
</div>

<?php require __DIR__ . '/../../layout/footer.php'; ?>
