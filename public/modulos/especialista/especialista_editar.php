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
| Carregar especialista
|--------------------------------------------------------------------------
*/

$sql = "
    SELECT
        e.id_especialista,
        e.id_ramo,
        e.id_quadro,
        e.id_patente,
        e.estado,
        e.observacoes,

        dp.nome_completo,
        dp.data_nascimento,
        dp.id_sexo,
        dp.id_pais_nascimento,
        dp.id_provincia_nascimento,
        dp.id_municipio_nascimento,
        dp.id_pais_nacionalidade,
        dp.id_estado_civil,
        dp.numero_bi,
        dp.data_emissao_bi,
        dp.data_validade_bi,
        dp.local_emissao_bi,
        dp.telefone,
        dp.email,
        dp.morada,

        ds.nip,
        ds.numero_ordem,
        ds.numero_processo,
        ds.data_ingresso,
        ds.data_incorporacao,
        ds.data_promocao,
        ds.id_funcao,
        ds.cargo,
        ds.id_unidade,
        ds.id_departamento,
        ds.id_situacao_servico,
        ds.data_inicio_funcao,
        ds.data_fim_funcao,
        ds.observacoes AS observacoes_servico

    FROM especialistas e
    INNER JOIN dados_pessoais dp
        ON dp.id_especialista = e.id_especialista
    INNER JOIN dados_servico ds
        ON ds.id_especialista = e.id_especialista
    WHERE e.id_especialista = ?
";

$stmt = $pdo->prepare($sql);
$stmt->execute([$id]);
$especialista = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$especialista) {
    die('Especialista não encontrado.');
}

/*
|--------------------------------------------------------------------------
| Verificar acesso
|--------------------------------------------------------------------------
*/

$paramsScope = [];

if ($_SESSION['user']['nivel'] === 'RAMO') {

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
        $especialista['id_unidade']
    ]);

    if (!$stmt->fetchColumn()) {
        http_response_code(403);
        die('Acesso não autorizado.');
    }
}

/*
|--------------------------------------------------------------------------
| Tabelas auxiliares
|--------------------------------------------------------------------------
*/

$ramos = $pdo->query("
    SELECT id_ramo, nome
    FROM ramo
    WHERE estado = 'ATIVO'
    ORDER BY nome
")->fetchAll();

$quadros = $pdo->query("
    SELECT id_quadro, nome
    FROM quadros_servico
    WHERE estado = 'ATIVO'
    ORDER BY nome
")->fetchAll();

$sexos = $pdo->query("
    SELECT id_sexo, nome
    FROM sexos
    WHERE estado = 'ATIVO'
    ORDER BY nome
")->fetchAll();

$estadosCivis = $pdo->query("
    SELECT id_estado_civil, nome
    FROM estados_civis
    WHERE estado = 'ATIVO'
    ORDER BY nome
")->fetchAll();

$paises = $pdo->query("
    SELECT id_pais, nome
    FROM paises
    WHERE estado = 'ATIVO'
    ORDER BY nome
")->fetchAll();

$funcoes = $pdo->query("
    SELECT id_funcao, nome
    FROM funcoes
    WHERE estado = 'ATIVO'
    ORDER BY nome
")->fetchAll();

$unidades = $pdo->query("
    SELECT id_unidade, nome, id_ramo
    FROM unidades
    WHERE estado = 'ATIVO'
    ORDER BY nome
")->fetchAll();

$departamentos = $pdo->query("
    SELECT id_departamento, nome, id_unidade
    FROM departamentos
    WHERE estado = 'ATIVO'
    ORDER BY nome
")->fetchAll();

$situacoes = $pdo->query("
    SELECT id_situacao_servico, nome
    FROM situacoes_servico
    WHERE estado = 'ATIVO'
    ORDER BY nome
")->fetchAll();

/*
|--------------------------------------------------------------------------
| Processar formulário
|--------------------------------------------------------------------------
*/

$erro = '';
$sucesso = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    try {

        $nome = trim($_POST['nome_completo'] ?? '');
        $nip = trim($_POST['nip'] ?? '');

        if ($nome === '') {
            throw new Exception('O nome completo é obrigatório.');
        }

        if ($nip === '') {
            throw new Exception('O NIP é obrigatório.');
        }

        $idRamo = (int)($_POST['id_ramo'] ?? 0);
        $idQuadro = (int)($_POST['id_quadro'] ?? 0);
        $idPatente = !empty($_POST['id_patente'])
            ? (int)$_POST['id_patente']
            : null;

        $idUnidade = !empty($_POST['id_unidade'])
            ? (int)$_POST['id_unidade']
            : null;

        /*
        |--------------------------------------------------------------------------
        | Validar acesso ao ramo/unidade
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
                $idRamo
            ]);

            if (!$stmt->fetchColumn()) {
                throw new Exception('Não possui permissão para este ramo.');
            }

        } elseif ($_SESSION['user']['nivel'] !== 'GLOBAL') {

            if (!$idUnidade) {
                throw new Exception('A unidade é obrigatória para este utilizador.');
            }

            $stmt = $pdo->prepare("
                SELECT 1
                FROM utilizador_unidade
                WHERE id_utilizador = ?
                AND id_unidade = ?
            ");

            $stmt->execute([
                $_SESSION['user']['id'],
                $idUnidade
            ]);

            if (!$stmt->fetchColumn()) {
                throw new Exception('Não possui permissão para esta unidade.');
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Validar patente
        |--------------------------------------------------------------------------
        */

        if ($idPatente) {

            $stmt = $pdo->prepare("
                SELECT 1
                FROM patentes
                WHERE id_patente = ?
                AND id_ramo = ?
                AND estado = 'ATIVO'
            ");

            $stmt->execute([
                $idPatente,
                $idRamo
            ]);

            if (!$stmt->fetchColumn()) {
                throw new Exception('A patente selecionada não pertence ao ramo.');
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Verificar NIP duplicado
        |--------------------------------------------------------------------------
        */

        $stmt = $pdo->prepare("
            SELECT id_especialista
            FROM dados_servico
            WHERE nip = ?
            AND id_especialista <> ?
        ");

        $stmt->execute([
            $nip,
            $id
        ]);

        if ($stmt->fetchColumn()) {
            throw new Exception('Este NIP já está associado a outro especialista.');
        }

        /*
        |--------------------------------------------------------------------------
        | Actualização
        |--------------------------------------------------------------------------
        */

        $pdo->beginTransaction();

        /*
        | Especialista
        */

        $stmt = $pdo->prepare("
            UPDATE especialistas
            SET
                id_ramo = ?,
                id_quadro = ?,
                id_patente = ?,
                estado = ?,
                observacoes = ?
            WHERE id_especialista = ?
        ");

        $stmt->execute([
            $idRamo,
            $idQuadro,
            $idPatente,
            $_POST['estado'] ?? 'ATIVO',
            trim($_POST['observacoes'] ?? ''),
            $id
        ]);

        /*
        | Dados pessoais
        */

        $stmt = $pdo->prepare("
            UPDATE dados_pessoais
            SET
                nome_completo = ?,
                data_nascimento = ?,
                id_sexo = ?,
                id_pais_nascimento = ?,
                id_provincia_nascimento = ?,
                id_municipio_nascimento = ?,
                id_pais_nacionalidade = ?,
                id_estado_civil = ?,
                numero_bi = ?,
                data_emissao_bi = ?,
                data_validade_bi = ?,
                local_emissao_bi = ?,
                telefone = ?,
                email = ?,
                morada = ?
            WHERE id_especialista = ?
        ");

        $stmt->execute([
            $nome,
            $_POST['data_nascimento'] ?: null,
            !empty($_POST['id_sexo']) ? (int)$_POST['id_sexo'] : null,
            !empty($_POST['id_pais_nascimento']) ? (int)$_POST['id_pais_nascimento'] : null,
            !empty($_POST['id_provincia_nascimento']) ? (int)$_POST['id_provincia_nascimento'] : null,
            !empty($_POST['id_municipio_nascimento']) ? (int)$_POST['id_municipio_nascimento'] : null,
            !empty($_POST['id_pais_nacionalidade']) ? (int)$_POST['id_pais_nacionalidade'] : null,
            !empty($_POST['id_estado_civil']) ? (int)$_POST['id_estado_civil'] : null,
            trim($_POST['numero_bi'] ?? ''),
            $_POST['data_emissao_bi'] ?: null,
            $_POST['data_validade_bi'] ?: null,
            trim($_POST['local_emissao_bi'] ?? ''),
            trim($_POST['telefone'] ?? ''),
            trim($_POST['email'] ?? ''),
            trim($_POST['morada'] ?? ''),
            $id
        ]);

        /*
        | Dados de serviço
        */

        $stmt = $pdo->prepare("
            UPDATE dados_servico
            SET
                nip = ?,
                numero_ordem = ?,
                numero_processo = ?,
                data_ingresso = ?,
                data_incorporacao = ?,
                data_promocao = ?,
                id_funcao = ?,
                cargo = ?,
                id_unidade = ?,
                id_departamento = ?,
                id_situacao_servico = ?,
                data_inicio_funcao = ?,
                data_fim_funcao = ?,
                observacoes = ?
            WHERE id_especialista = ?
        ");

        $stmt->execute([
            $nip,
            trim($_POST['numero_ordem'] ?? ''),
            trim($_POST['numero_processo'] ?? ''),
            $_POST['data_ingresso'] ?: null,
            $_POST['data_incorporacao'] ?: null,
            $_POST['data_promocao'] ?: null,
            !empty($_POST['id_funcao']) ? (int)$_POST['id_funcao'] : null,
            trim($_POST['cargo'] ?? ''),
            $idUnidade,
            !empty($_POST['id_departamento']) ? (int)$_POST['id_departamento'] : null,
            !empty($_POST['id_situacao_servico'])
                ? (int)$_POST['id_situacao_servico']
                : null,
            $_POST['data_inicio_funcao'] ?: null,
            $_POST['data_fim_funcao'] ?: null,
            trim($_POST['observacoes_servico'] ?? ''),
            $id
        ]);

        $pdo->commit();

        header('Location: especialista.php?id=' . $id . '&atualizado=1');
        exit;

    } catch (Throwable $e) {

        if ($pdo->inTransaction()) {
            $pdo->rollBack();
        }

        $erro = $e->getMessage();
    }
}

/*
|--------------------------------------------------------------------------
| Valores do formulário
|--------------------------------------------------------------------------
*/

$v = function ($campo) use ($especialista) {
    return htmlspecialchars(
        $_POST[$campo] ?? $especialista[$campo] ?? '',
        ENT_QUOTES,
        'UTF-8'
    );
};

function selected($valor, $atual)
{
    return ((string)$valor === (string)$atual) ? 'selected' : '';
}
$page_title = "Especialista Editar";
require __DIR__ . '/../../layout/header.php';
?>

<div class="module-page module-especialista-editar">
<style>


.module-page.module-especialista-editar .form-section {
    margin-bottom:25px;
    padding:20px;
    background:#fff;
    border-radius:10px;
}

.module-page.module-especialista-editar .form-grid {
    display:grid;
    grid-template-columns:repeat(3,1fr);
    gap:15px;
}

.module-page.module-especialista-editar .form-group {
    display:flex;
    flex-direction:column;
    gap:6px;
}

.module-page.module-especialista-editar .form-group.full {
    grid-column:1/-1;
}

.module-page.module-especialista-editar .form-group label {
    font-weight:600;
}

.module-page.module-especialista-editar .form-group input, .module-page.module-especialista-editar .form-group select, .module-page.module-especialista-editar .form-group textarea {
    padding:10px;
    border:1px solid #ccc;
    border-radius:6px;
}

.module-page.module-especialista-editar .form-group textarea {
    min-height:90px;
}

.module-page.module-especialista-editar .acoes {
    display:flex;
    gap:10px;
    margin-top:20px;
}

@media(max-width:900px){
    .module-page.module-especialista-editar .form-grid {
        grid-template-columns:1fr;
    }
}


</style>
<main>

<h1>Editar Especialista</h1>

<p>
    <a href="especialista.php?id=<?=$id?>">← Voltar para a ficha</a>
</p>

<?php if ($erro): ?>

<div class="erro">
    <?=htmlspecialchars($erro)?>
</div>

<?php endif; ?>


<form method="post">


<!-- ========================================================= -->
<!-- DADOS DO ESPECIALISTA -->
<!-- ========================================================= -->

<section class="form-section">

<h2>Dados do Especialista</h2>

<div class="form-grid">

<div class="form-group">

<label>Ramo *</label>

<select name="id_ramo" id="id_ramo" required>

<option value="">Seleccione</option>

<?php foreach ($ramos as $r): ?>

<option
    value="<?=$r['id_ramo']?>"
    <?=selected(
        $r['id_ramo'],
        $_POST['id_ramo'] ?? $especialista['id_ramo']
    )?>
>
    <?=htmlspecialchars($r['nome'])?>
</option>

<?php endforeach; ?>

</select>

</div>


<div class="form-group">

<label>Quadro *</label>

<select name="id_quadro" required>

<option value="">Seleccione</option>

<?php foreach ($quadros as $q): ?>

<option
    value="<?=$q['id_quadro']?>"
    <?=selected(
        $q['id_quadro'],
        $_POST['id_quadro'] ?? $especialista['id_quadro']
    )?>
>
    <?=htmlspecialchars($q['nome'])?>
</option>

<?php endforeach; ?>

</select>

</div>


<div class="form-group">

<label>Patente</label>

<select name="id_patente" id="id_patente">

<option value="">Seleccione</option>

</select>

</div>


<div class="form-group">

<label>Estado</label>

<select name="estado">

<option value="ATIVO" <?=selected('ATIVO', $v('estado'))?>>ATIVO</option>
<option value="INATIVO" <?=selected('INATIVO', $v('estado'))?>>INATIVO</option>

</select>

</div>

<div class="form-group full">

<label>Observações</label>

<textarea name="observacoes"><?= $v('observacoes') ?></textarea>

</div>

</div>

</section>


<!-- ========================================================= -->
<!-- DADOS PESSOAIS -->
<!-- ========================================================= -->

<section class="form-section">

<h2>Dados Pessoais</h2>

<div class="form-grid">

<div class="form-group full">

<label>Nome completo *</label>

<input
    type="text"
    name="nome_completo"
    value="<?=$v('nome_completo')?>"
    required
>

</div>


<div class="form-group">

<label>Data de nascimento</label>

<input
    type="date"
    name="data_nascimento"
    value="<?=$v('data_nascimento')?>"
>

</div>


<div class="form-group">

<label>Sexo</label>

<select name="id_sexo">

<option value="">Seleccione</option>

<?php foreach ($sexos as $s): ?>

<option
    value="<?=$s['id_sexo']?>"
    <?=selected(
        $s['id_sexo'],
        $_POST['id_sexo'] ?? $especialista['id_sexo']
    )?>
>
    <?=htmlspecialchars($s['nome'])?>
</option>

<?php endforeach; ?>

</select>

</div>


<div class="form-group">

<label>Estado civil</label>

<select name="id_estado_civil">

<option value="">Seleccione</option>

<?php foreach ($estadosCivis as $e): ?>

<option
    value="<?=$e['id_estado_civil']?>"
    <?=selected(
        $e['id_estado_civil'],
        $_POST['id_estado_civil'] ?? $especialista['id_estado_civil']
    )?>
>
    <?=htmlspecialchars($e['nome'])?>
</option>

<?php endforeach; ?>

</select>

</div>


<div class="form-group">

<label>Nacionalidade</label>

<select name="id_pais_nacionalidade">

<option value="">Seleccione</option>

<?php foreach ($paises as $p): ?>

<option
    value="<?=$p['id_pais']?>"
    <?=selected(
        $p['id_pais'],
        $_POST['id_pais_nacionalidade'] ?? $especialista['id_pais_nacionalidade']
    )?>
>
    <?=htmlspecialchars($p['nome'])?>
</option>

<?php endforeach; ?>

</select>

</div>


<div class="form-group">

<label>País de nascimento</label>

<select name="id_pais_nascimento">

<option value="">Seleccione</option>

<?php foreach ($paises as $p): ?>

<option
    value="<?=$p['id_pais']?>"
    <?=selected(
        $p['id_pais'],
        $_POST['id_pais_nascimento'] ?? $especialista['id_pais_nascimento']
    )?>
>
    <?=htmlspecialchars($p['nome'])?>
</option>

<?php endforeach; ?>

</select>

</div>


<div class="form-group">

<label>Província de nascimento</label>

<input
    type="number"
    name="id_provincia_nascimento"
    value="<?=$v('id_provincia_nascimento')?>"
>

</div>


<div class="form-group">

<label>Município de nascimento</label>

<input
    type="number"
    name="id_municipio_nascimento"
    value="<?=$v('id_municipio_nascimento')?>"
>

</div>

</div>

</section>


<!-- ========================================================= -->
<!-- BILHETE DE IDENTIDADE -->
<!-- ========================================================= -->

<section class="form-section">

<h2>Bilhete de Identidade</h2>

<div class="form-grid">

<div class="form-group">

<label>Número do BI</label>

<input
    type="text"
    name="numero_bi"
    value="<?=$v('numero_bi')?>"
>

</div>


<div class="form-group">

<label>Data de emissão</label>

<input
    type="date"
    name="data_emissao_bi"
    value="<?=$v('data_emissao_bi')?>"
>

</div>


<div class="form-group">

<label>Data de validade</label>

<input
    type="date"
    name="data_validade_bi"
    value="<?=$v('data_validade_bi')?>"
>

</div>


<div class="form-group full">

<label>Local de emissão</label>

<input
    type="text"
    name="local_emissao_bi"
    value="<?=$v('local_emissao_bi')?>"
>

</div>

</div>

</section>


<!-- ========================================================= -->
<!-- CONTACTOS -->
<!-- ========================================================= -->

<section class="form-section">

<h2>Contactos</h2>

<div class="form-grid">

<div class="form-group">

<label>Telefone</label>

<input
    type="text"
    name="telefone"
    value="<?=$v('telefone')?>"
>

</div>


<div class="form-group">

<label>E-mail</label>

<input
    type="email"
    name="email"
    value="<?=$v('email')?>"
>

</div>


<div class="form-group full">

<label>Morada</label>

<textarea name="morada"><?= $v('morada') ?></textarea>

</div>

</div>

</section>


<!-- ========================================================= -->
<!-- DADOS DE SERVIÇO -->
<!-- ========================================================= -->

<section class="form-section">

<h2>Dados de Serviço</h2>

<div class="form-grid">

<div class="form-group">

<label>NIP *</label>

<input
    type="text"
    name="nip"
    value="<?=$v('nip')?>"
    required
>

</div>


<div class="form-group">

<label>Número de ordem</label>

<input
    type="text"
    name="numero_ordem"
    value="<?=$v('numero_ordem')?>"
>

</div>


<div class="form-group">

<label>Número de processo</label>

<input
    type="text"
    name="numero_processo"
    value="<?=$v('numero_processo')?>"
>

</div>


<div class="form-group">

<label>Data de ingresso</label>

<input
    type="date"
    name="data_ingresso"
    value="<?=$v('data_ingresso')?>"
>

</div>


<div class="form-group">

<label>Data de incorporação</label>

<input
    type="date"
    name="data_incorporacao"
    value="<?=$v('data_incorporacao')?>"
>

</div>


<div class="form-group">

<label>Data de promoção</label>

<input
    type="date"
    name="data_promocao"
    value="<?=$v('data_promocao')?>"
>

</div>


<div class="form-group">

<label>Função</label>

<select name="id_funcao">

<option value="">Seleccione</option>

<?php foreach ($funcoes as $f): ?>

<option
    value="<?=$f['id_funcao']?>"
    <?=selected(
        $f['id_funcao'],
        $_POST['id_funcao'] ?? $especialista['id_funcao']
    )?>
>
    <?=htmlspecialchars($f['nome'])?>
</option>

<?php endforeach; ?>

</select>

</div>


<div class="form-group">

<label>Cargo</label>

<input
    type="text"
    name="cargo"
    value="<?=$v('cargo')?>"
>

</div>


<div class="form-group">

<label>Unidade</label>

<select name="id_unidade" id="id_unidade">

<option value="">Seleccione</option>

<?php foreach ($unidades as $u): ?>

<option
    value="<?=$u['id_unidade']?>"
    data-ramo="<?=$u['id_ramo']?>"
    <?=selected(
        $u['id_unidade'],
        $_POST['id_unidade'] ?? $especialista['id_unidade']
    )?>
>
    <?=htmlspecialchars($u['nome'])?>
</option>

<?php endforeach; ?>

</select>

</div>


<div class="form-group">

<label>Departamento</label>

<select name="id_departamento" id="id_departamento">

<option value="">Seleccione</option>

<?php foreach ($departamentos as $d): ?>

<option
    value="<?=$d['id_departamento']?>"
    data-unidade="<?=$d['id_unidade']?>"
    <?=selected(
        $d['id_departamento'],
        $_POST['id_departamento'] ?? $especialista['id_departamento']
    )?>
>
    <?=htmlspecialchars($d['nome'])?>
</option>

<?php endforeach; ?>

</select>

</div>


<div class="form-group">

<label>Situação de serviço</label>

<select name="id_situacao_servico">

<option value="">Seleccione</option>

<?php foreach ($situacoes as $s): ?>

<option
    value="<?=$s['id_situacao_servico']?>"
    <?=selected(
        $s['id_situacao_servico'],
        $_POST['id_situacao_servico'] ?? $especialista['id_situacao_servico']
    )?>
>
    <?=htmlspecialchars($s['nome'])?>
</option>

<?php endforeach; ?>

</select>

</div>


<div class="form-group">

<label>Início da função</label>

<input
    type="date"
    name="data_inicio_funcao"
    value="<?=$v('data_inicio_funcao')?>"
>

</div>


<div class="form-group">

<label>Fim da função</label>

<input
    type="date"
    name="data_fim_funcao"
    value="<?=$v('data_fim_funcao')?>"
>

</div>


<div class="form-group full">

<label>Observações de serviço</label>

<textarea name="observacoes_servico"><?= $v('observacoes_servico') ?></textarea>

</div>

</div>

</section>


<div class="acoes">

<button type="submit">
    Guardar alterações
</button>

<a href="especialista.php?id=<?=$id?>">
    Cancelar
</a>

</div>

</form>

</main>


<script>

async function carregarPatentes(idRamo, selecionada = '') {

    const select = document.getElementById('id_patente');

    select.innerHTML = '<option value="">A carregar...</option>';

    if (!idRamo) {
        select.innerHTML = '<option value="">Seleccione</option>';
        return;
    }

    try {

        const resposta = await fetch(
            '../configuracoes/api_patentes.php?id_ramo=' + encodeURIComponent(idRamo)
        );

        const dados = await resposta.json();

        select.innerHTML = '<option value="">Seleccione</option>';

        dados.forEach(function(patente) {

            const option = document.createElement('option');

            option.value = patente.id_patente;

            option.textContent =
                patente.sigla
                ? patente.sigla + ' - ' + patente.nome
                : patente.nome;

            if (String(patente.id_patente) === String(selecionada)) {
                option.selected = true;
            }

            select.appendChild(option);
        });

    } catch (erro) {

        select.innerHTML =
            '<option value="">Erro ao carregar patentes</option>';

    }
}


const ramo = document.getElementById('id_ramo');

const patenteAtual =
    <?=json_encode(
        $_POST['id_patente']
        ?? $especialista['id_patente']
    )?>;

carregarPatentes(
    ramo.value,
    patenteAtual
);


ramo.addEventListener('change', function() {

    carregarPatentes(this.value, '');

    /*
    | Filtrar unidades pelo ramo
    */

    const unidade = document.getElementById('id_unidade');

    Array.from(unidade.options).forEach(function(option) {

        if (!option.value) return;

        option.hidden =
            option.dataset.ramo !== this.value;

    }, this);

});


/*
|--------------------------------------------------------------------------
| Filtrar departamentos pela unidade
|--------------------------------------------------------------------------
*/

const unidade = document.getElementById('id_unidade');
const departamento = document.getElementById('id_departamento');

function filtrarDepartamentos() {

    const idUnidade = unidade.value;

    Array.from(departamento.options).forEach(function(option) {

        if (!option.value) return;

        option.hidden =
            option.dataset.unidade !== idUnidade;

    });
}

unidade.addEventListener('change', filtrarDepartamentos);

filtrarDepartamentos();

</script>
</div>

<?php require __DIR__ . '/../../layout/footer.php'; ?>
