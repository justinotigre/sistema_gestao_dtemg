<?php
require_once __DIR__ . '/../../../App/auth.php';
require_login();


$pdo = db();

$id = (int)($_GET['id'] ?? $_POST['id'] ?? 0);

if (!$id) {
    header('Location: ' . app_url('modulos/dashboard.php'));
    exit;
}

function e($v) {
    return htmlspecialchars((string)$v, ENT_QUOTES, 'UTF-8');
}

/* ============================================================
   CARREGAR HABILITAÇÃO
============================================================ */

$stmt = $pdo->prepare("
    SELECT
        h.*,
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
    exit('Habilitação literária não encontrada.');
}

$id_especialista = (int)$habilitacao['id_especialista'];

/* ============================================================
   CONTROLO DE ACESSO
============================================================ */

$nivel = $_SESSION['user']['nivel'] ?? '';

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
   LISTAS
============================================================ */

$niveis = $pdo->query("
    SELECT id_nivel_habilitacao, nome
    FROM niveis_habilitacao
    WHERE estado = 'ATIVO'
    ORDER BY ordem ASC, nome ASC
")->fetchAll();

$instituicoes = $pdo->query("
    SELECT id_instituicao, nome
    FROM instituicoes
    WHERE estado = 'ATIVO'
    ORDER BY nome ASC
")->fetchAll();

$paises = $pdo->query("
    SELECT id_pais, nome
    FROM paises
    WHERE estado = 'ATIVO'
    ORDER BY nome ASC
")->fetchAll();

/* ============================================================
   PROCESSAR
============================================================ */

$erro = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $categoria = trim($_POST['categoria'] ?? '');
    $id_nivel_habilitacao = (int)($_POST['id_nivel_habilitacao'] ?? 0);
    $curso = trim($_POST['curso'] ?? '');
    $id_instituicao = (int)($_POST['id_instituicao'] ?? 0);
    $id_pais = (int)($_POST['id_pais'] ?? 0);
    $data_inicio = $_POST['data_inicio'] ?? null;
    $data_conclusao = $_POST['data_conclusao'] ?? null;
    $numero_certificado = trim($_POST['numero_certificado'] ?? '');
    $observacoes = trim($_POST['observacoes'] ?? '');

    if (!in_array($categoria, ['MILITAR', 'GERAL'], true)) {
        $erro = 'Selecione uma categoria válida.';
    } elseif ($id_nivel_habilitacao <= 0) {
        $erro = 'Selecione o nível de habilitação.';
    } elseif ($curso === '') {
        $erro = 'Informe o curso.';
    } elseif (
        $data_inicio &&
        $data_conclusao &&
        $data_conclusao < $data_inicio
    ) {
        $erro = 'A data de conclusão não pode ser anterior à data de início.';
    }

    if (!$erro) {

        try {

            $stmt = $pdo->prepare("
                UPDATE habilitacoes_literarias
                SET
                    categoria = ?,
                    id_nivel_habilitacao = ?,
                    curso = ?,
                    id_instituicao = ?,
                    id_pais = ?,
                    data_inicio = ?,
                    data_conclusao = ?,
                    numero_certificado = ?,
                    observacoes = ?
                WHERE id_habilitacao = ?
            ");

            $stmt->execute([
                $categoria,
                $id_nivel_habilitacao,
                $curso,
                $id_instituicao ?: null,
                $id_pais ?: null,
                $data_inicio ?: null,
                $data_conclusao ?: null,
                $numero_certificado ?: null,
                $observacoes ?: null,
                $id
            ]);

            header(
                'Location: especialista.php?id=' .
                $id_especialista .
                '&habilitacao=atualizada'
            );

            exit;

        } catch (Throwable $e) {

            $erro = 'Não foi possível atualizar: ' . $e->getMessage();
        }
    }

    $habilitacao['categoria'] = $categoria;
    $habilitacao['id_nivel_habilitacao'] = $id_nivel_habilitacao;
    $habilitacao['curso'] = $curso;
    $habilitacao['id_instituicao'] = $id_instituicao;
    $habilitacao['id_pais'] = $id_pais;
    $habilitacao['data_inicio'] = $data_inicio;
    $habilitacao['data_conclusao'] = $data_conclusao;
    $habilitacao['numero_certificado'] = $numero_certificado;
    $habilitacao['observacoes'] = $observacoes;
}
$page_title = "Habilitacao Editar";
require __DIR__ . '/../../layout/header.php';
?>

<div class="module-page module-habilitacao-editar">
<style>


.module-page.module-habilitacao-editar .form-container {
    max-width:1000px;
    margin:30px auto;
    padding:25px;
    background:#fff;
    border-radius:12px;
    box-shadow:0 3px 15px rgba(0,0,0,.08);
}

.module-page.module-habilitacao-editar .form-grid {
    display:grid;
    grid-template-columns:repeat(2,1fr);
    gap:20px;
}

.module-page.module-habilitacao-editar .form-group {
    display:flex;
    flex-direction:column;
    gap:7px;
}

.module-page.module-habilitacao-editar .form-group.full {
    grid-column:1/-1;
}

.module-page.module-habilitacao-editar .form-group label {
    font-weight:600;
}

.module-page.module-habilitacao-editar .form-group input, .module-page.module-habilitacao-editar .form-group select, .module-page.module-habilitacao-editar .form-group textarea {
    padding:11px;
    border:1px solid #ccc;
    border-radius:7px;
    font-size:15px;
}

.module-page.module-habilitacao-editar .form-group textarea {
    min-height:120px;
}

.module-page.module-habilitacao-editar .actions {
    margin-top:25px;
    display:flex;
    gap:10px;
}

.module-page.module-habilitacao-editar .btn {
    padding:11px 18px;
    border-radius:7px;
    text-decoration:none;
    border:0;
    cursor:pointer;
}

.module-page.module-habilitacao-editar .btn-primary {
    background:#1f5f8b;
    color:#fff;
}

.module-page.module-habilitacao-editar .btn-secondary {
    background:#eee;
    color:#222;
}

.module-page.module-habilitacao-editar .erro {
    padding:12px;
    margin-bottom:20px;
    background:#f8d7da;
    color:#842029;
    border-radius:7px;
}

@media(max-width:700px){
    .module-page.module-habilitacao-editar .form-grid {
        grid-template-columns:1fr;
    }

    .module-page.module-habilitacao-editar .form-group.full {
        grid-column:auto;
    }
}


</style>
<main>

<div class="form-container">

<h1>Editar Habilitação Literária</h1>

<p>
Especialista:
<strong><?= e($habilitacao['nome_completo']) ?></strong>
</p>

<?php if ($erro): ?>

<div class="erro">
<?= e($erro) ?>
</div>

<?php endif; ?>

<form method="post">

<input type="hidden" name="id" value="<?= $id ?>">

<div class="form-grid">

<div class="form-group">

<label>Categoria *</label>

<select name="categoria" required>

<option value="">Selecione</option>

<option value="MILITAR"
<?= $habilitacao['categoria'] === 'MILITAR' ? 'selected' : '' ?>>
Militar
</option>

<option value="GERAL"
<?= $habilitacao['categoria'] === 'GERAL' ? 'selected' : '' ?>>
Geral
</option>

</select>

</div>

<div class="form-group">

<label>Nível de Habilitação *</label>

<select name="id_nivel_habilitacao" required>

<option value="">Selecione</option>

<?php foreach ($niveis as $nivel): ?>

<option
value="<?= (int)$nivel['id_nivel_habilitacao'] ?>"
<?= (int)$habilitacao['id_nivel_habilitacao'] === (int)$nivel['id_nivel_habilitacao'] ? 'selected' : '' ?>
>
<?= e($nivel['nome']) ?>
</option>

<?php endforeach; ?>

</select>

</div>

<div class="form-group full">

<label>Curso *</label>

<input
type="text"
name="curso"
maxlength="200"
required
value="<?= e($habilitacao['curso']) ?>"
>

</div>

<div class="form-group">

<label>Instituição</label>

<select name="id_instituicao">

<option value="">Selecione</option>

<?php foreach ($instituicoes as $instituicao): ?>

<option
value="<?= (int)$instituicao['id_instituicao'] ?>"
<?= (int)$habilitacao['id_instituicao'] === (int)$instituicao['id_instituicao'] ? 'selected' : '' ?>
>
<?= e($instituicao['nome']) ?>
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
value="<?= (int)$pais['id_pais'] ?>"
<?= (int)$habilitacao['id_pais'] === (int)$pais['id_pais'] ? 'selected' : '' ?>
>
<?= e($pais['nome']) ?>
</option>

<?php endforeach; ?>

</select>

</div>

<div class="form-group">

<label>Data de Início</label>

<input
type="date"
name="data_inicio"
value="<?= e($habilitacao['data_inicio']) ?>"
>

</div>

<div class="form-group">

<label>Data de Conclusão</label>

<input
type="date"
name="data_conclusao"
value="<?= e($habilitacao['data_conclusao']) ?>"
>

</div>

<div class="form-group">

<label>Número do Certificado</label>

<input
type="text"
name="numero_certificado"
maxlength="100"
value="<?= e($habilitacao['numero_certificado']) ?>"
>

</div>

<div class="form-group full">

<label>Observações</label>

<textarea name="observacoes"><?= e($habilitacao['observacoes']) ?></textarea>

</div>

</div>

<div class="actions">

<button class="btn btn-primary" type="submit">
Atualizar Habilitação
</button>

<a
class="btn btn-secondary"
href="especialista.php?id=<?= $id_especialista ?>"
>
Cancelar
</a>

</div>

</form>

</div>

</main>
</div>

<?php require __DIR__ . '/../../layout/footer.php'; ?>
