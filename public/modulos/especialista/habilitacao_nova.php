<?php
require_once __DIR__ . '/../../../App/auth.php';
require_login();


$pdo = db();

$id_especialista = (int)($_GET['id_especialista'] ?? $_POST['id_especialista'] ?? 0);

if (!$id_especialista) {
    header('Location: ' . app_url('modulos/dashboard.php'));
    exit;
}

/* ============================================================
   FUNÇÕES
============================================================ */

function e($v) {
    return htmlspecialchars((string)$v, ENT_QUOTES, 'UTF-8');
}

/* ============================================================
   LOCALIZAR ESPECIALISTA E VERIFICAR ACESSO
============================================================ */

$stmt = $pdo->prepare("
    SELECT
        e.id_especialista,
        e.id_ramo,
        dp.nome_completo,
        ds.id_unidade
    FROM especialistas e
    INNER JOIN dados_pessoais dp
        ON dp.id_especialista = e.id_especialista
    LEFT JOIN dados_servico ds
        ON ds.id_especialista = e.id_especialista
    WHERE e.id_especialista = ?
");
$stmt->execute([$id_especialista]);

$especialista = $stmt->fetch();

if (!$especialista) {
    exit('Especialista não encontrado.');
}

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
        $especialista['id_ramo']
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
        $especialista['id_unidade']
    ]);

    if (!$stmt->fetchColumn()) {
        http_response_code(403);
        exit('Acesso não autorizado.');
    }
}

/* ============================================================
   CARREGAR LISTAS
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
   PROCESSAMENTO
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
    }

    if (!$erro && $data_inicio && $data_conclusao && $data_conclusao < $data_inicio) {
        $erro = 'A data de conclusão não pode ser anterior à data de início.';
    }

    if (!$erro) {

        try {

            $stmt = $pdo->prepare("
                INSERT INTO habilitacoes_literarias (
                    id_especialista,
                    categoria,
                    id_nivel_habilitacao,
                    curso,
                    id_instituicao,
                    id_pais,
                    data_inicio,
                    data_conclusao,
                    numero_certificado,
                    observacoes
                ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
            ");

            $stmt->execute([
                $id_especialista,
                $categoria,
                $id_nivel_habilitacao,
                $curso,
                $id_instituicao ?: null,
                $id_pais ?: null,
                $data_inicio ?: null,
                $data_conclusao ?: null,
                $numero_certificado ?: null,
                $observacoes ?: null
            ]);

            header(
                'Location: especialista.php?id=' .
                $id_especialista .
                '&habilitacao=adicionada'
            );
            exit;

        } catch (Throwable $e) {
            $erro = 'Não foi possível guardar a habilitação: ' . $e->getMessage();
        }
    }
}
$page_title = "Habilitacao Nova";
require __DIR__ . '/../../layout/header.php';
?>

<div class="module-page module-habilitacao-nova">
<style>

.module-page.module-habilitacao-nova .form-container {
    max-width:1000px;
    margin:30px auto;
    padding:25px;
    background:#fff;
    border-radius:12px;
    box-shadow:0 3px 15px rgba(0,0,0,.08);
}

.module-page.module-habilitacao-nova .form-grid {
    display:grid;
    grid-template-columns:repeat(2,1fr);
    gap:20px;
}

.module-page.module-habilitacao-nova .form-group {
    display:flex;
    flex-direction:column;
    gap:7px;
}

.module-page.module-habilitacao-nova .form-group.full {
    grid-column:1/-1;
}

.module-page.module-habilitacao-nova .form-group label {
    font-weight:600;
}

.module-page.module-habilitacao-nova .form-group input, .module-page.module-habilitacao-nova .form-group select, .module-page.module-habilitacao-nova .form-group textarea {
    padding:11px;
    border:1px solid #ccc;
    border-radius:7px;
    font-size:15px;
}

.module-page.module-habilitacao-nova .form-group textarea {
    min-height:120px;
    resize:vertical;
}

.module-page.module-habilitacao-nova .actions {
    margin-top:25px;
    display:flex;
    gap:10px;
}

.module-page.module-habilitacao-nova .btn {
    display:inline-block;
    padding:11px 18px;
    border-radius:7px;
    text-decoration:none;
    border:0;
    cursor:pointer;
    font-size:15px;
}

.module-page.module-habilitacao-nova .btn-primary {
    background:#1f5f8b;
    color:#fff;
}

.module-page.module-habilitacao-nova .btn-secondary {
    background:#eee;
    color:#222;
}

.module-page.module-habilitacao-nova .erro {
    padding:12px;
    margin-bottom:20px;
    background:#f8d7da;
    color:#842029;
    border-radius:7px;
}

@media(max-width:700px){
    .module-page.module-habilitacao-nova .form-grid {
        grid-template-columns:1fr;
    }

    .module-page.module-habilitacao-nova .form-group.full {
        grid-column:auto;
    }
}

</style>
<main>

<div class="form-container">

    <h1>Nova Habilitação Literária</h1>

    <p>
        Especialista:
        <strong><?= e($especialista['nome_completo']) ?></strong>
    </p>

    <?php if ($erro): ?>
        <div class="erro"><?= e($erro) ?></div>
    <?php endif; ?>

    <form method="post">

        <input
            type="hidden"
            name="id_especialista"
            value="<?= $id_especialista ?>"
        >

        <div class="form-grid">

            <div class="form-group">

                <label>Categoria *</label>

                <select name="categoria" required>

                    <option value="">Selecione</option>

                    <option value="MILITAR"
                        <?= (($_POST['categoria'] ?? '') === 'MILITAR') ? 'selected' : '' ?>>
                        Militar
                    </option>

                    <option value="GERAL"
                        <?= (($_POST['categoria'] ?? '') === 'GERAL') ? 'selected' : '' ?>>
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
                            <?= ((int)($_POST['id_nivel_habilitacao'] ?? 0) === (int)$nivel['id_nivel_habilitacao']) ? 'selected' : '' ?>
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
                    value="<?= e($_POST['curso'] ?? '') ?>"
                    placeholder="Ex.: Engenharia Informática"
                >

            </div>

            <div class="form-group">

                <label>Instituição</label>

                <select name="id_instituicao">

                    <option value="">Selecione</option>

                    <?php foreach ($instituicoes as $instituicao): ?>

                        <option
                            value="<?= (int)$instituicao['id_instituicao'] ?>"
                            <?= ((int)($_POST['id_instituicao'] ?? 0) === (int)$instituicao['id_instituicao']) ? 'selected' : '' ?>
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
                            <?= ((int)($_POST['id_pais'] ?? 0) === (int)$pais['id_pais']) ? 'selected' : '' ?>
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
                    value="<?= e($_POST['data_inicio'] ?? '') ?>"
                >

            </div>

            <div class="form-group">

                <label>Data de Conclusão</label>

                <input
                    type="date"
                    name="data_conclusao"
                    value="<?= e($_POST['data_conclusao'] ?? '') ?>"
                >

            </div>

            <div class="form-group">

                <label>Número do Certificado</label>

                <input
                    type="text"
                    name="numero_certificado"
                    maxlength="100"
                    value="<?= e($_POST['numero_certificado'] ?? '') ?>"
                >

            </div>

            <div class="form-group full">

                <label>Observações</label>

                <textarea
                    name="observacoes"
                    placeholder="Observações adicionais..."
                ><?= e($_POST['observacoes'] ?? '') ?></textarea>

            </div>

        </div>

        <div class="actions">

            <button
                type="submit"
                class="btn btn-primary"
            >
                Guardar Habilitação
            </button>

            <a
                href="especialista.php?id=<?= $id_especialista ?>"
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
