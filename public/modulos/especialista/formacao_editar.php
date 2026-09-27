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
        fo.*,
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

$id_especialista = (int)$formacao['id_especialista'];


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
        $erro = 'A data final não pode ser anterior à inicial.';
    }

    if (!$erro) {

        $stmt = $pdo->prepare("
            UPDATE formacoes
            SET
                categoria = ?,
                id_tipo_formacao = ?,
                designacao = ?,
                id_instituicao = ?,
                id_pais = ?,
                local = ?,
                data_inicio = ?,
                data_fim = ?,
                duracao = ?,
                certificado = ?,
                observacoes = ?
            WHERE id_formacao = ?
        ");

        $stmt->execute([
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
            $observacoes ?: null,
            $id
        ]);

        header(
            'Location: especialista.php?id=' .
            $id_especialista .
            '&formacao=atualizada#formacoes'
        );
        exit;
    }
}
$page_title = "Formacao Editar";
require __DIR__ . '/../../layout/header.php';
?>

<div class="module-page module-formacao-editar">
<main>

<div class="form-box">

<h1>Editar Formação</h1>

<p>
Especialista:
<strong>
<?=htmlspecialchars($formacao['nome_completo'])?>
</strong>
</p>

<?php if ($erro): ?>

<div class="erro">
<?=htmlspecialchars($erro)?>
</div>

<?php endif; ?>


<form method="post">

<label>Categoria *</label>

<select name="categoria" required>

<option value="MILITAR"
<?=($formacao['categoria'] === 'MILITAR' ? 'selected' : '')?>
>
Militar
</option>

<option value="GERAL"
<?=($formacao['categoria'] === 'GERAL' ? 'selected' : '')?>
>
Geral
</option>

</select>


<br><br>


<label>Tipo de formação</label>

<select name="id_tipo_formacao">

<option value="">Selecione</option>

<?php foreach ($tipos as $tipo): ?>

<option
value="<?=$tipo['id_tipo_formacao']?>"
<?=((int)$formacao['id_tipo_formacao'] === (int)$tipo['id_tipo_formacao']) ? 'selected' : ''?>
>
<?=htmlspecialchars($tipo['nome'])?>
</option>

<?php endforeach; ?>

</select>


<br><br>


<label>Designação *</label>

<input
type="text"
name="designacao"
required
maxlength="200"
value="<?=htmlspecialchars($formacao['designacao'])?>"
>


<br><br>


<label>Instituição</label>

<select name="id_instituicao">

<option value="">Selecione</option>

<?php foreach ($instituicoes as $i): ?>

<option
value="<?=$i['id_instituicao']?>"
<?=((int)$formacao['id_instituicao'] === (int)$i['id_instituicao']) ? 'selected' : ''?>
>
<?=htmlspecialchars($i['nome'])?>
</option>

<?php endforeach; ?>

</select>


<br><br>


<label>País</label>

<select name="id_pais">

<option value="">Selecione</option>

<?php foreach ($paises as $p): ?>

<option
value="<?=$p['id_pais']?>"
<?=((int)$formacao['id_pais'] === (int)$p['id_pais']) ? 'selected' : ''?>
>
<?=htmlspecialchars($p['nome'])?>
</option>

<?php endforeach; ?>

</select>


<br><br>


<label>Local</label>

<input
type="text"
name="local"
maxlength="150"
value="<?=htmlspecialchars($formacao['local'] ?? '')?>"
>


<br><br>


<label>Data de início</label>

<input
type="date"
name="data_inicio"
value="<?=htmlspecialchars($formacao['data_inicio'] ?? '')?>"
>


<br><br>


<label>Data de conclusão</label>

<input
type="date"
name="data_fim"
value="<?=htmlspecialchars($formacao['data_fim'] ?? '')?>"
>


<br><br>


<label>Duração</label>

<input
type="text"
name="duracao"
maxlength="100"
value="<?=htmlspecialchars($formacao['duracao'] ?? '')?>"
>


<br><br>


<label>Certificado</label>

<input
type="text"
name="certificado"
maxlength="100"
value="<?=htmlspecialchars($formacao['certificado'] ?? '')?>"
>


<br><br>


<label>Observações</label>

<textarea
name="observacoes"
rows="5"
><?=htmlspecialchars($formacao['observacoes'] ?? '')?></textarea>


<br><br>


<button type="submit">
Guardar alterações
</button>

<a href="especialista.php?id=<?=$id_especialista?>#formacoes">
Cancelar
</a>

</form>

</div>

</main>
</div>

<?php require __DIR__ . '/../../layout/footer.php'; ?>
