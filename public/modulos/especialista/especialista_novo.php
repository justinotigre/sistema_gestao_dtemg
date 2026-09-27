<?php
require_once __DIR__ . '/../../../App/auth.php';
require_login();


$pdo = db();

$erro = '';
$sucesso = '';

/*
|--------------------------------------------------------------------------
| Carregar listas
|--------------------------------------------------------------------------
*/

$ramos = $pdo->query("
    SELECT id_ramo, nome, sigla
    FROM ramo
    WHERE estado = 'ATIVO'
    ORDER BY nome
")->fetchAll(PDO::FETCH_ASSOC);

$quadros = $pdo->query("
    SELECT id_quadro, nome, sigla
    FROM quadros_servico
    WHERE estado = 'ATIVO'
    ORDER BY nome
")->fetchAll(PDO::FETCH_ASSOC);

$sexos = $pdo->query("
    SELECT id_sexo, nome
    FROM sexos
    WHERE estado = 'ATIVO'
    ORDER BY nome
")->fetchAll(PDO::FETCH_ASSOC);

$estados_civis = $pdo->query("
    SELECT id_estado_civil, nome
    FROM estados_civis
    WHERE estado = 'ATIVO'
    ORDER BY nome
")->fetchAll(PDO::FETCH_ASSOC);

$paises = $pdo->query("
    SELECT id_pais, nome
    FROM paises
    WHERE estado = 'ATIVO'
    ORDER BY nome
")->fetchAll(PDO::FETCH_ASSOC);

$funcoes = $pdo->query("
    SELECT id_funcao, nome
    FROM funcoes
    WHERE estado = 'ATIVO'
    ORDER BY nome
")->fetchAll(PDO::FETCH_ASSOC);

$unidades = $pdo->query("
    SELECT
        u.id_unidade,
        u.id_ramo,
        u.nome,
        u.sigla
    FROM unidades u
    WHERE u.estado = 'ATIVO'
    ORDER BY u.nome
")->fetchAll(PDO::FETCH_ASSOC);

$situacoes = $pdo->query("
    SELECT
        id_situacao_servico,
        nome,
        grupo
    FROM situacoes_servico
    WHERE estado = 'ATIVO'
    ORDER BY nome
")->fetchAll(PDO::FETCH_ASSOC);


/*
|--------------------------------------------------------------------------
| Valores enviados
|--------------------------------------------------------------------------
*/

$dados = [
    'nome_completo' => '',
    'data_nascimento' => '',
    'id_sexo' => '',
    'id_pais_nascimento' => '',
    'id_provincia_nascimento' => '',
    'id_municipio_nascimento' => '',
    'id_pais_nacionalidade' => '',
    'id_estado_civil' => '',
    'numero_bi' => '',
    'data_emissao_bi' => '',
    'data_validade_bi' => '',
    'local_emissao_bi' => '',
    'telefone' => '',
    'email' => '',
    'morada' => '',

    'id_ramo' => '',
    'id_quadro' => '',
    'id_patente' => '',

    'nip' => '',
    'numero_ordem' => '',
    'numero_processo' => '',
    'data_ingresso' => '',
    'data_incorporacao' => '',
    'data_promocao' => '',
    'id_funcao' => '',
    'cargo' => '',
    'id_unidade' => '',
    'id_departamento' => '',
    'id_situacao_servico' => '',
    'data_inicio_funcao' => '',
    'data_fim_funcao' => '',
    'observacoes' => ''
];


/*
|--------------------------------------------------------------------------
| POST
|--------------------------------------------------------------------------
*/

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    foreach ($dados as $campo => $valor) {
        $dados[$campo] = trim($_POST[$campo] ?? '');
    }

    if ($dados['nome_completo'] === '') {
        $erro = 'O nome completo é obrigatório.';
    }

    elseif ($dados['id_ramo'] === '') {
        $erro = 'Selecione o ramo.';
    }

    elseif ($dados['id_quadro'] === '') {
        $erro = 'Selecione o quadro de serviço.';
    }

    elseif ($dados['nip'] === '') {
        $erro = 'O NIP é obrigatório.';
    }


    /*
    |--------------------------------------------------------------------------
    | Verificar âmbito de acesso
    |--------------------------------------------------------------------------
    */

    if ($erro === '') {

        $ramoId = (int)$dados['id_ramo'];
        $unidadeId = (int)$dados['id_unidade'];

        $usuario = $_SESSION['user'];

        if ($usuario['nivel'] === 'RAMO') {

            $stmt = $pdo->prepare("
                SELECT COUNT(*)
                FROM utilizador_ramo
                WHERE id_utilizador = ?
                AND id_ramo = ?
            ");

            $stmt->execute([
                $usuario['id'],
                $ramoId
            ]);

            if (!(int)$stmt->fetchColumn()) {
                $erro = 'Não tem permissão para cadastrar especialistas neste ramo.';
            }
        }

        elseif ($usuario['nivel'] === 'UNIDADE') {

            $stmt = $pdo->prepare("
                SELECT COUNT(*)
                FROM utilizador_unidade
                WHERE id_utilizador = ?
                AND id_unidade = ?
            ");

            $stmt->execute([
                $usuario['id'],
                $unidadeId
            ]);

            if (!$unidadeId || !(int)$stmt->fetchColumn()) {
                $erro = 'Não tem permissão para cadastrar especialistas nesta unidade.';
            }
        }
    }


    /*
    |--------------------------------------------------------------------------
    | Validar patente
    |--------------------------------------------------------------------------
    */

    if ($erro === '' && $dados['id_patente'] !== '') {

        $stmt = $pdo->prepare("
            SELECT COUNT(*)
            FROM patentes
            WHERE id_patente = ?
            AND id_ramo = ?
            AND estado = 'ATIVO'
        ");

        $stmt->execute([
            (int)$dados['id_patente'],
            (int)$dados['id_ramo']
        ]);

        if (!(int)$stmt->fetchColumn()) {
            $erro = 'A patente selecionada não pertence ao ramo escolhido.';
        }
    }


    /*
    |--------------------------------------------------------------------------
    | Gravar
    |--------------------------------------------------------------------------
    */

    if ($erro === '') {

        try {

            $pdo->beginTransaction();


            /*
            |--------------------------------------------------------------------------
            | Especialista
            |--------------------------------------------------------------------------
            */

            $stmt = $pdo->prepare("
                INSERT INTO especialistas
                (
                    id_ramo,
                    id_quadro,
                    id_patente,
                    estado,
                    observacoes
                )
                VALUES (?, ?, ?, 'ATIVO', ?)
            ");

            $stmt->execute([
                (int)$dados['id_ramo'],
                (int)$dados['id_quadro'],
                $dados['id_patente'] !== ''
                    ? (int)$dados['id_patente']
                    : null,
                $dados['observacoes']
            ]);

            $idEspecialista = (int)$pdo->lastInsertId();


            /*
            |--------------------------------------------------------------------------
            | Dados pessoais
            |--------------------------------------------------------------------------
            */

            $stmt = $pdo->prepare("
                INSERT INTO dados_pessoais
                (
                    id_especialista,
                    nome_completo,
                    data_nascimento,
                    id_sexo,
                    id_pais_nascimento,
                    id_provincia_nascimento,
                    id_municipio_nascimento,
                    id_pais_nacionalidade,
                    id_estado_civil,
                    numero_bi,
                    data_emissao_bi,
                    data_validade_bi,
                    local_emissao_bi,
                    telefone,
                    email,
                    morada
                )
                VALUES
                (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
            ");

            $stmt->execute([
                $idEspecialista,
                $dados['nome_completo'],
                $dados['data_nascimento'] ?: null,
                $dados['id_sexo'] !== ''
                    ? (int)$dados['id_sexo']
                    : null,
                $dados['id_pais_nascimento'] !== ''
                    ? (int)$dados['id_pais_nascimento']
                    : null,
                $dados['id_provincia_nascimento'] !== ''
                    ? (int)$dados['id_provincia_nascimento']
                    : null,
                $dados['id_municipio_nascimento'] !== ''
                    ? (int)$dados['id_municipio_nascimento']
                    : null,
                $dados['id_pais_nacionalidade'] !== ''
                    ? (int)$dados['id_pais_nacionalidade']
                    : null,
                $dados['id_estado_civil'] !== ''
                    ? (int)$dados['id_estado_civil']
                    : null,
                $dados['numero_bi'] !== ''
                    ? $dados['numero_bi']
                    : null,
                $dados['data_emissao_bi'] ?: null,
                $dados['data_validade_bi'] ?: null,
                $dados['local_emissao_bi'],
                $dados['telefone'],
                $dados['email'],
                $dados['morada']
            ]);


            /*
            |--------------------------------------------------------------------------
            | Dados de serviço
            |--------------------------------------------------------------------------
            */

            $stmt = $pdo->prepare("
                INSERT INTO dados_servico
                (
                    id_especialista,
                    nip,
                    numero_ordem,
                    numero_processo,
                    data_ingresso,
                    data_incorporacao,
                    data_promocao,
                    id_funcao,
                    cargo,
                    id_unidade,
                    id_departamento,
                    id_situacao_servico,
                    data_inicio_funcao,
                    data_fim_funcao,
                    observacoes
                )
                VALUES
                (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
            ");

            $stmt->execute([
                $idEspecialista,
                $dados['nip'],
                $dados['numero_ordem'] !== ''
                    ? $dados['numero_ordem']
                    : null,
                $dados['numero_processo'] !== ''
                    ? $dados['numero_processo']
                    : null,
                $dados['data_ingresso'] ?: null,
                $dados['data_incorporacao'] ?: null,
                $dados['data_promocao'] ?: null,
                $dados['id_funcao'] !== ''
                    ? (int)$dados['id_funcao']
                    : null,
                $dados['cargo'],
                $dados['id_unidade'] !== ''
                    ? (int)$dados['id_unidade']
                    : null,
                $dados['id_departamento'] !== ''
                    ? (int)$dados['id_departamento']
                    : null,
                $dados['id_situacao_servico'] !== ''
                    ? (int)$dados['id_situacao_servico']
                    : null,
                $dados['data_inicio_funcao'] ?: null,
                $dados['data_fim_funcao'] ?: null,
                $dados['observacoes']
            ]);


            $pdo->commit();

            header(
                'Location: especialista.php?id=' .
                $idEspecialista .
                '&criado=1'
            );

            exit;

        } catch (Throwable $e) {

            if ($pdo->inTransaction()) {
                $pdo->rollBack();
            }

            $erro = 'Não foi possível cadastrar o especialista.';

            /*
            | Durante o desenvolvimento pode ser útil visualizar
            | o erro real. Depois podemos remover esta linha.
            */
            $erro .= ' ' . $e->getMessage();
        }
    }
}
$page_title = "Especialista Novo";
require __DIR__ . '/../../layout/header.php';
?>

<div class="module-page module-especialista-novo">
<style>


        .module-page.module-especialista-novo .topo-pagina {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
            gap: 20px;
        }

        .module-page.module-especialista-novo .topo-pagina h1 {
            margin-bottom: 5px;
        }

        .module-page.module-especialista-novo .topo-pagina p {
            margin: 0;
            color: #666;
        }

        .module-page.module-especialista-novo .acoes-topo {
            display: flex;
            gap: 10px;
        }

        .module-page.module-especialista-novo .btn {
            display: inline-block;
            padding: 10px 16px;
            border-radius: 6px;
            border: 0;
            text-decoration: none;
            cursor: pointer;
            font-weight: 600;
        }

        .module-page.module-especialista-novo .btn-primary {
            background: #1f6feb;
            color: #fff;
        }

        .module-page.module-especialista-novo .btn-secondary {
            background: #e9ecef;
            color: #222;
        }

        .module-page.module-especialista-novo .form-section {
            background: #fff;
            border-radius: 10px;
            padding: 24px;
            margin-bottom: 20px;
            box-shadow: 0 2px 10px rgba(0,0,0,.06);
        }

        .module-page.module-especialista-novo .form-section h2 {
            margin-top: 0;
            padding-bottom: 12px;
            border-bottom: 1px solid #eee;
        }

        .module-page.module-especialista-novo .form-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 16px;
        }

        .module-page.module-especialista-novo .campo {
            display: flex;
            flex-direction: column;
            gap: 6px;
        }

        .module-page.module-especialista-novo .campo.col-2 {
            grid-column: span 2;
        }

        .module-page.module-especialista-novo .campo.col-3 {
            grid-column: span 3;
        }

        .module-page.module-especialista-novo .campo label {
            font-size: 13px;
            font-weight: 600;
        }

        .module-page.module-especialista-novo .campo input, .module-page.module-especialista-novo .campo select, .module-page.module-especialista-novo .campo textarea {
            width: 100%;
            box-sizing: border-box;
            padding: 10px;
            border: 1px solid #d5dbe1;
            border-radius: 6px;
            background: #fff;
            font: inherit;
        }

        .module-page.module-especialista-novo .campo textarea {
            min-height: 90px;
            resize: vertical;
        }

        .module-page.module-especialista-novo .obrigatorio {
            color: #c62828;
        }

        .module-page.module-especialista-novo .erro {
            background: #f8d7da;
            color: #842029;
            padding: 12px 15px;
            border-radius: 6px;
            margin-bottom: 20px;
        }

        .module-page.module-especialista-novo .acoes-final {
            display: flex;
            justify-content: flex-end;
            gap: 10px;
            margin-bottom: 30px;
        }

        @media(max-width: 900px) {

            .module-page.module-especialista-novo .form-grid {
                grid-template-columns: 1fr 1fr;
            }

            .module-page.module-especialista-novo .campo.col-3 {
                grid-column: span 2;
            }

        }

        @media(max-width: 600px) {

            .module-page.module-especialista-novo .topo-pagina {
                flex-direction: column;
                align-items: flex-start;
            }

            .module-page.module-especialista-novo .form-grid {
                grid-template-columns: 1fr;
            }

            .module-page.module-especialista-novo .campo.col-2, .module-page.module-especialista-novo .campo.col-3 {
                grid-column: span 1;
            }

        }

    
</style>
<main>

    <div class="topo-pagina">

        <div>

            <h1>Novo Especialista</h1>

            <p>
                Registo completo do especialista.
            </p>

        </div>

        <div class="acoes-topo">

            <a
                href="especialistas.php"
                class="btn btn-secondary"
            >
                Voltar
            </a>

        </div>

    </div>


    <?php if ($erro): ?>

        <div class="erro">

            <?= htmlspecialchars($erro) ?>

        </div>

    <?php endif; ?>


    <form method="post">


        <!-- DADOS PRINCIPAIS -->

        <section class="form-section">

            <h2>Dados do Especialista</h2>

            <div class="form-grid">

                <div class="campo col-2">

                    <label>
                        Nome completo
                        <span class="obrigatorio">*</span>
                    </label>

                    <input
                        type="text"
                        name="nome_completo"
                        value="<?= htmlspecialchars($dados['nome_completo']) ?>"
                        required
                    >

                </div>


                <div class="campo">

                    <label>Sexo</label>

                    <select name="id_sexo">

                        <option value="">Selecionar</option>

                        <?php foreach ($sexos as $item): ?>

                            <option
                                value="<?= $item['id_sexo'] ?>"
                                <?= $dados['id_sexo'] == $item['id_sexo'] ? 'selected' : '' ?>
                            >

                                <?= htmlspecialchars($item['nome']) ?>

                            </option>

                        <?php endforeach; ?>

                    </select>

                </div>


                <div class="campo">

                    <label>Data de nascimento</label>

                    <input
                        type="date"
                        name="data_nascimento"
                        value="<?= htmlspecialchars($dados['data_nascimento']) ?>"
                    >

                </div>


                <div class="campo">

                    <label>Estado civil</label>

                    <select name="id_estado_civil">

                        <option value="">Selecionar</option>

                        <?php foreach ($estados_civis as $item): ?>

                            <option
                                value="<?= $item['id_estado_civil'] ?>"
                                <?= $dados['id_estado_civil'] == $item['id_estado_civil'] ? 'selected' : '' ?>
                            >

                                <?= htmlspecialchars($item['nome']) ?>

                            </option>

                        <?php endforeach; ?>

                    </select>

                </div>


                <div class="campo">

                    <label>Nacionalidade</label>

                    <select name="id_pais_nacionalidade">

                        <option value="">Selecionar</option>

                        <?php foreach ($paises as $item): ?>

                            <option
                                value="<?= $item['id_pais'] ?>"
                                <?= $dados['id_pais_nacionalidade'] == $item['id_pais'] ? 'selected' : '' ?>
                            >

                                <?= htmlspecialchars($item['nome']) ?>

                            </option>

                        <?php endforeach; ?>

                    </select>

                </div>


                <div class="campo">

                    <label>País de nascimento</label>

                    <select name="id_pais_nascimento">

                        <option value="">Selecionar</option>

                        <?php foreach ($paises as $item): ?>

                            <option
                                value="<?= $item['id_pais'] ?>"
                                <?= $dados['id_pais_nascimento'] == $item['id_pais'] ? 'selected' : '' ?>
                            >

                                <?= htmlspecialchars($item['nome']) ?>

                            </option>

                        <?php endforeach; ?>

                    </select>

                </div>

            </div>

        </section>


        <!-- DOCUMENTO DE IDENTIFICAÇÃO -->

        <section class="form-section">

            <h2>Bilhete de Identidade</h2>

            <div class="form-grid">

                <div class="campo">

                    <label>Número do BI</label>

                    <input
                        type="text"
                        name="numero_bi"
                        value="<?= htmlspecialchars($dados['numero_bi']) ?>"
                    >

                </div>


                <div class="campo">

                    <label>Data de emissão</label>

                    <input
                        type="date"
                        name="data_emissao_bi"
                        value="<?= htmlspecialchars($dados['data_emissao_bi']) ?>"
                    >

                </div>


                <div class="campo">

                    <label>Data de validade</label>

                    <input
                        type="date"
                        name="data_validade_bi"
                        value="<?= htmlspecialchars($dados['data_validade_bi']) ?>"
                    >

                </div>


                <div class="campo">

                    <label>Local de emissão</label>

                    <input
                        type="text"
                        name="local_emissao_bi"
                        value="<?= htmlspecialchars($dados['local_emissao_bi']) ?>"
                    >

                </div>

            </div>

        </section>


        <!-- CONTACTOS -->

        <section class="form-section">

            <h2>Contactos</h2>

            <div class="form-grid">

                <div class="campo">

                    <label>Telefone</label>

                    <input
                        type="text"
                        name="telefone"
                        value="<?= htmlspecialchars($dados['telefone']) ?>"
                    >

                </div>


                <div class="campo">

                    <label>Email</label>

                    <input
                        type="email"
                        name="email"
                        value="<?= htmlspecialchars($dados['email']) ?>"
                    >

                </div>


                <div class="campo col-3">

                    <label>Morada</label>

                    <textarea name="morada"><?= htmlspecialchars($dados['morada']) ?></textarea>

                </div>

            </div>

        </section>


        <!-- ENQUADRAMENTO -->

        <section class="form-section">

            <h2>Enquadramento</h2>

            <div class="form-grid">

                <div class="campo">

                    <label>
                        Ramo
                        <span class="obrigatorio">*</span>
                    </label>

                    <select
                        name="id_ramo"
                        id="id_ramo"
                        required
                    >

                        <option value="">Selecionar</option>

                        <?php foreach ($ramos as $item): ?>

                            <option
                                value="<?= $item['id_ramo'] ?>"
                                <?= $dados['id_ramo'] == $item['id_ramo'] ? 'selected' : '' ?>
                            >

                                <?= htmlspecialchars($item['nome']) ?>

                                <?php if ($item['sigla']): ?>
                                    (<?= htmlspecialchars($item['sigla']) ?>)
                                <?php endif; ?>

                            </option>

                        <?php endforeach; ?>

                    </select>

                </div>


                <div class="campo">

                    <label>
                        Quadro de serviço
                        <span class="obrigatorio">*</span>
                    </label>

                    <select
                        name="id_quadro"
                        required
                    >

                        <option value="">Selecionar</option>

                        <?php foreach ($quadros as $item): ?>

                            <option
                                value="<?= $item['id_quadro'] ?>"
                                <?= $dados['id_quadro'] == $item['id_quadro'] ? 'selected' : '' ?>
                            >

                                <?= htmlspecialchars($item['nome']) ?>

                            </option>

                        <?php endforeach; ?>

                    </select>

                </div>


                <div class="campo">

                    <label>Patente</label>

                    <select name="id_patente" id="id_patente">

                        <option value="">
                            Selecionar
                        </option>

                    </select>

                    <small>
                        A patente será carregada de acordo com o ramo.
                    </small>

                </div>

            </div>

        </section>


        <!-- DADOS DE SERVIÇO -->

        <section class="form-section">

            <h2>Dados de Serviço</h2>

            <div class="form-grid">

                <div class="campo">

                    <label>
                        NIP
                        <span class="obrigatorio">*</span>
                    </label>

                    <input
                        type="text"
                        name="nip"
                        value="<?= htmlspecialchars($dados['nip']) ?>"
                        required
                    >

                </div>


                <div class="campo">

                    <label>Número de ordem</label>

                    <input
                        type="text"
                        name="numero_ordem"
                        value="<?= htmlspecialchars($dados['numero_ordem']) ?>"
                    >

                </div>


                <div class="campo">

                    <label>Número de processo</label>

                    <input
                        type="text"
                        name="numero_processo"
                        value="<?= htmlspecialchars($dados['numero_processo']) ?>"
                    >

                </div>


                <div class="campo">

                    <label>Data de ingresso</label>

                    <input
                        type="date"
                        name="data_ingresso"
                        value="<?= htmlspecialchars($dados['data_ingresso']) ?>"
                    >

                </div>


                <div class="campo">

                    <label>Data de incorporação</label>

                    <input
                        type="date"
                        name="data_incorporacao"
                        value="<?= htmlspecialchars($dados['data_incorporacao']) ?>"
                    >

                </div>


                <div class="campo">

                    <label>Data de promoção</label>

                    <input
                        type="date"
                        name="data_promocao"
                        value="<?= htmlspecialchars($dados['data_promocao']) ?>"
                    >

                </div>


                <div class="campo">

                    <label>Função</label>

                    <select name="id_funcao">

                        <option value="">Selecionar</option>

                        <?php foreach ($funcoes as $item): ?>

                            <option
                                value="<?= $item['id_funcao'] ?>"
                                <?= $dados['id_funcao'] == $item['id_funcao'] ? 'selected' : '' ?>
                            >

                                <?= htmlspecialchars($item['nome']) ?>

                            </option>

                        <?php endforeach; ?>

                    </select>

                </div>


                <div class="campo">

                    <label>Cargo</label>

                    <input
                        type="text"
                        name="cargo"
                        value="<?= htmlspecialchars($dados['cargo']) ?>"
                    >

                </div>


                <div class="campo">

                    <label>Unidade</label>

                    <select name="id_unidade">

                        <option value="">Selecionar</option>

                        <?php foreach ($unidades as $item): ?>

                            <option
                                value="<?= $item['id_unidade'] ?>"
                                <?= $dados['id_unidade'] == $item['id_unidade'] ? 'selected' : '' ?>
                            >

                                <?= htmlspecialchars($item['nome']) ?>

                            </option>

                        <?php endforeach; ?>

                    </select>

                </div>


                <div class="campo">

                    <label>Situação de serviço</label>

                    <select name="id_situacao_servico">

                        <option value="">Selecionar</option>

                        <?php foreach ($situacoes as $item): ?>

                            <option
                                value="<?= $item['id_situacao_servico'] ?>"
                                <?= $dados['id_situacao_servico'] == $item['id_situacao_servico'] ? 'selected' : '' ?>
                            >

                                <?= htmlspecialchars($item['nome']) ?>

                            </option>

                        <?php endforeach; ?>

                    </select>

                </div>


                <div class="campo">

                    <label>Início da função</label>

                    <input
                        type="date"
                        name="data_inicio_funcao"
                        value="<?= htmlspecialchars($dados['data_inicio_funcao']) ?>"
                    >

                </div>


                <div class="campo">

                    <label>Fim da função</label>

                    <input
                        type="date"
                        name="data_fim_funcao"
                        value="<?= htmlspecialchars($dados['data_fim_funcao']) ?>"
                    >

                </div>

            </div>

        </section>


        <!-- OBSERVAÇÕES -->

        <section class="form-section">

            <h2>Observações</h2>

            <div class="campo">

                <textarea
                    name="observacoes"
                    placeholder="Observações gerais sobre o especialista..."
                ><?= htmlspecialchars($dados['observacoes']) ?></textarea>

            </div>

        </section>


        <div class="acoes-final">

            <a
                href="especialistas.php"
                class="btn btn-secondary"
            >
                Cancelar
            </a>

            <button
                type="submit"
                class="btn btn-primary"
            >
                Guardar Especialista
            </button>

        </div>

    </form>

</main>


<script>

/*
|--------------------------------------------------------------------------
| Carregar patentes conforme o ramo
|--------------------------------------------------------------------------
*/

const ramo = document.getElementById('id_ramo');
const patente = document.getElementById('id_patente');

async function carregarPatentes() {

    const idRamo = ramo.value;

    patente.innerHTML =
        '<option value="">A carregar...</option>';

    if (!idRamo) {

        patente.innerHTML =
            '<option value="">Selecionar</option>';

        return;
    }

    try {

        const resposta = await fetch(
            '../configuracoes/api_patentes.php?id_ramo=' +
            encodeURIComponent(idRamo)
        );

        const dados = await resposta.json();

        patente.innerHTML =
            '<option value="">Selecionar</option>';

        dados.forEach(function(item) {

            const option =
                document.createElement('option');

            option.value = item.id_patente;

            option.textContent =
                item.nome +
                (item.sigla
                    ? ' (' + item.sigla + ')'
                    : '');

            patente.appendChild(option);

        });

    } catch (erro) {

        patente.innerHTML =
            '<option value="">Erro ao carregar</option>';

    }

}

ramo.addEventListener('change', carregarPatentes);

</script>
</div>

<?php require __DIR__ . '/../../layout/footer.php'; ?>
