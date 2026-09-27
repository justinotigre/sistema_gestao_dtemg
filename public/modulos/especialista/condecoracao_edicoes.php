<?php
require_once __DIR__ . '/../../../App/auth.php';
require_login();


$pdo = db();

/*
|--------------------------------------------------------------------------
| Verificar ID da condecoração
|--------------------------------------------------------------------------
*/

$id_condecoracao = isset($_GET['id'])
    ? (int) $_GET['id']
    : 0;

if ($id_condecoracao <= 0) {
    header('Location: especialistas.php');
    exit;
}

/*
|--------------------------------------------------------------------------
| Buscar condecoração
|--------------------------------------------------------------------------
*/

$stmt = $pdo->prepare("
    SELECT
        c.id_condecoracao,
        c.id_especialista,
        c.id_tipo_condecoracao,
        c.entidade,
        c.data_atribuicao,
        c.motivo,
        c.numero_documento,
        c.observacoes,
        e.nome_completo
    FROM condecoracoes c
    INNER JOIN especialistas e
        ON e.id_especialista = c.id_especialista
    WHERE c.id_condecoracao = ?
    LIMIT 1
");

$stmt->execute([$id_condecoracao]);

$condecoracao = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$condecoracao) {
    header('Location: especialistas.php');
    exit;
}

$id_especialista = (int) $condecoracao['id_especialista'];

/*
|--------------------------------------------------------------------------
| Buscar tipos de condecoração
|--------------------------------------------------------------------------
*/

$stmt = $pdo->query("
    SELECT
        id_tipo_condecoracao,
        nome,
        descricao
    FROM tipos_condecoracao
    WHERE estado = 'ATIVO'
    ORDER BY nome ASC
");

$tipos_condecoracao = $stmt->fetchAll(PDO::FETCH_ASSOC);

$erro = '';

/*
|--------------------------------------------------------------------------
| Função de segurança
|--------------------------------------------------------------------------
*/

function h($valor)
{
    return htmlspecialchars(
        (string) $valor,
        ENT_QUOTES,
        'UTF-8'
    );
}

/*
|--------------------------------------------------------------------------
| Processar formulário
|--------------------------------------------------------------------------
*/

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $id_tipo_condecoracao = isset($_POST['id_tipo_condecoracao'])
        ? (int) $_POST['id_tipo_condecoracao']
        : 0;

    $entidade = trim($_POST['entidade'] ?? '');

    $data_atribuicao = trim($_POST['data_atribuicao'] ?? '');

    $motivo = trim($_POST['motivo'] ?? '');

    $numero_documento = trim($_POST['numero_documento'] ?? '');

    $observacoes = trim($_POST['observacoes'] ?? '');

    /*
    |--------------------------------------------------------------------------
    | Validações
    |--------------------------------------------------------------------------
    */

    if ($id_tipo_condecoracao <= 0) {

        $erro = 'Selecione o tipo de condecoração.';

    } elseif ($entidade === '') {

        $erro = 'Informe a entidade que atribuiu a condecoração.';

    } elseif ($data_atribuicao === '') {

        $erro = 'Informe a data de atribuição.';

    } else {

        /*
        |--------------------------------------------------------------------------
        | Confirmar tipo de condecoração
        |--------------------------------------------------------------------------
        */

        $stmt = $pdo->prepare("
            SELECT id_tipo_condecoracao
            FROM tipos_condecoracao
            WHERE id_tipo_condecoracao = ?
              AND estado = 'ATIVO'
            LIMIT 1
        ");

        $stmt->execute([
            $id_tipo_condecoracao
        ]);

        $tipo_valido = $stmt->fetchColumn();

        if (!$tipo_valido) {

            $erro = 'O tipo de condecoração selecionado não é válido.';

        } else {

            try {

                /*
                |--------------------------------------------------------------------------
                | Atualizar condecoração
                |--------------------------------------------------------------------------
                */

                $stmt = $pdo->prepare("
                    UPDATE condecoracoes
                    SET
                        id_tipo_condecoracao = :id_tipo_condecoracao,
                        entidade = :entidade,
                        data_atribuicao = :data_atribuicao,
                        motivo = :motivo,
                        numero_documento = :numero_documento,
                        observacoes = :observacoes
                    WHERE id_condecoracao = :id_condecoracao
                ");

                $stmt->execute([
                    ':id_tipo_condecoracao' => $id_tipo_condecoracao,
                    ':entidade' => $entidade,
                    ':data_atribuicao' => $data_atribuicao,
                    ':motivo' => $motivo !== '' ? $motivo : null,
                    ':numero_documento' => $numero_documento !== ''
                        ? $numero_documento
                        : null,
                    ':observacoes' => $observacoes !== ''
                        ? $observacoes
                        : null,
                    ':id_condecoracao' => $id_condecoracao
                ]);

                header(
                    'Location: especialista.php?id='
                    . $id_especialista
                    . '&condecoracao=atualizada'
                );

                exit;

            } catch (PDOException $e) {

                $erro = 'Não foi possível atualizar a condecoração.';
            }
        }
    }

    /*
    |--------------------------------------------------------------------------
    | Manter dados digitados em caso de erro
    |--------------------------------------------------------------------------
    */

    $dados = [
        'id_tipo_condecoracao' => $id_tipo_condecoracao,
        'entidade' => $entidade,
        'data_atribuicao' => $data_atribuicao,
        'motivo' => $motivo,
        'numero_documento' => $numero_documento,
        'observacoes' => $observacoes
    ];

} else {

    /*
    |--------------------------------------------------------------------------
    | Dados originais
    |--------------------------------------------------------------------------
    */

    $dados = [
        'id_tipo_condecoracao' => $condecoracao['id_tipo_condecoracao'],
        'entidade' => $condecoracao['entidade'],
        'data_atribuicao' => $condecoracao['data_atribuicao'],
        'motivo' => $condecoracao['motivo'],
        'numero_documento' => $condecoracao['numero_documento'],
        'observacoes' => $condecoracao['observacoes']
    ];
}
$page_title = "Condecoracao Edicoes";
require __DIR__ . '/../../layout/header.php';
?>

<div class="module-page module-condecoracao-edicoes">
<style>


        .module-page.module-condecoracao-edicoes {
            font-family: Arial, sans-serif;
            background: #f4f6f8;
            margin: 0;
            padding: 30px;
        }

        .module-page.module-condecoracao-edicoes .container {
            max-width: 900px;
            margin: auto;
        }

        .module-page.module-condecoracao-edicoes .topo {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
            gap: 15px;
        }

        .module-page.module-condecoracao-edicoes h1 {
            margin: 0;
        }

        .module-page.module-condecoracao-edicoes .subtitulo {
            margin-top: 6px;
            color: #666;
        }

        .module-page.module-condecoracao-edicoes .card {
            background: white;
            border-radius: 10px;
            padding: 25px;
            box-shadow: 0 2px 10px rgba(0,0,0,.08);
        }

        .module-page.module-condecoracao-edicoes .especialista {
            background: #eef3f8;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 25px;
        }

        .module-page.module-condecoracao-edicoes .especialista strong {
            display: block;
            margin-bottom: 5px;
        }

        .module-page.module-condecoracao-edicoes .form-group {
            margin-bottom: 18px;
        }

        .module-page.module-condecoracao-edicoes label {
            display: block;
            font-weight: bold;
            margin-bottom: 7px;
        }

        .module-page.module-condecoracao-edicoes input, .module-page.module-condecoracao-edicoes select, .module-page.module-condecoracao-edicoes textarea {
            width: 100%;
            padding: 11px;
            border: 1px solid #ccc;
            border-radius: 6px;
            box-sizing: border-box;
            font-size: 14px;
        }

        .module-page.module-condecoracao-edicoes textarea {
            min-height: 110px;
            resize: vertical;
        }

        .module-page.module-condecoracao-edicoes .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }

        .module-page.module-condecoracao-edicoes .botoes {
            display: flex;
            gap: 10px;
            margin-top: 25px;
        }

        .module-page.module-condecoracao-edicoes .btn {
            display: inline-block;
            padding: 11px 18px;
            border-radius: 6px;
            border: none;
            text-decoration: none;
            cursor: pointer;
            font-size: 14px;
        }

        .module-page.module-condecoracao-edicoes .btn-primary {
            background: #0d6efd;
            color: white;
        }

        .module-page.module-condecoracao-edicoes .btn-secondary {
            background: #6c757d;
            color: white;
        }

        .module-page.module-condecoracao-edicoes .alert {
            background: #f8d7da;
            color: #842029;
            padding: 12px 15px;
            border-radius: 6px;
            margin-bottom: 20px;
        }

        .module-page.module-condecoracao-edicoes .obrigatorio {
            color: #dc3545;
        }

        @media (max-width: 700px) {

            .module-page.module-condecoracao-edicoes {
                padding: 15px;
            }

            .module-page.module-condecoracao-edicoes .topo {
                flex-direction: column;
                align-items: flex-start;
            }

            .module-page.module-condecoracao-edicoes .form-row {
                grid-template-columns: 1fr;
                gap: 0;
            }

            .module-page.module-condecoracao-edicoes .botoes {
                flex-direction: column;
            }

            .module-page.module-condecoracao-edicoes .btn {
                text-align: center;
            }

        }

    
</style>
<div class="container">

    <div class="topo">

        <div>

            <h1>Editar Condecoração</h1>

            <div class="subtitulo">
                Alterar os dados da condecoração
            </div>

        </div>

        <a
            href="../especialista.php?id=<?= $id_especialista ?>"
            class="btn btn-secondary"
        >
            Voltar
        </a>

    </div>


    <?php if ($erro): ?>

        <div class="alert">
            <?= h($erro) ?>
        </div>

    <?php endif; ?>


    <div class="card">

        <div class="especialista">

            <strong>Especialista</strong>

            <?= h($condecoracao['nome_completo']) ?>

        </div>


        <form method="POST">

            <div class="form-group">

                <label for="id_tipo_condecoracao">

                    Tipo de Condecoração
                    <span class="obrigatorio">*</span>

                </label>

                <select
                    name="id_tipo_condecoracao"
                    id="id_tipo_condecoracao"
                    required
                >

                    <option value="">
                        -- Selecionar tipo --
                    </option>

                    <?php foreach ($tipos_condecoracao as $tipo): ?>

                        <option
                            value="<?= (int) $tipo['id_tipo_condecoracao'] ?>"
                            <?= (
                                (int) $dados['id_tipo_condecoracao']
                                === (int) $tipo['id_tipo_condecoracao']
                            ) ? 'selected' : '' ?>
                        >
                            <?= h($tipo['nome']) ?>
                        </option>

                    <?php endforeach; ?>

                </select>

            </div>


            <div class="form-row">

                <div class="form-group">

                    <label for="entidade">

                        Entidade
                        <span class="obrigatorio">*</span>

                    </label>

                    <input
                        type="text"
                        name="entidade"
                        id="entidade"
                        maxlength="255"
                        value="<?= h($dados['entidade']) ?>"
                        required
                    >

                </div>


                <div class="form-group">

                    <label for="data_atribuicao">

                        Data de atribuição
                        <span class="obrigatorio">*</span>

                    </label>

                    <input
                        type="date"
                        name="data_atribuicao"
                        id="data_atribuicao"
                        value="<?= h($dados['data_atribuicao']) ?>"
                        required
                    >

                </div>

            </div>


            <div class="form-row">

                <div class="form-group">

                    <label for="numero_documento">
                        Número do documento
                    </label>

                    <input
                        type="text"
                        name="numero_documento"
                        id="numero_documento"
                        maxlength="255"
                        value="<?= h($dados['numero_documento']) ?>"
                    >

                </div>


                <div class="form-group">

                    <label for="motivo">
                        Motivo
                    </label>

                    <input
                        type="text"
                        name="motivo"
                        id="motivo"
                        maxlength="255"
                        value="<?= h($dados['motivo']) ?>"
                    >

                </div>

            </div>


            <div class="form-group">

                <label for="observacoes">
                    Observações
                </label>

                <textarea
                    name="observacoes"
                    id="observacoes"
                ><?= h($dados['observacoes']) ?></textarea>

            </div>


            <div class="botoes">

                <button
                    type="submit"
                    class="btn btn-primary"
                >
                    Guardar Alterações
                </button>

                <a
                    href="../especialista.php?id=<?= $id_especialista ?>"
                    class="btn btn-secondary"
                >
                    Cancelar
                </a>

            </div>

        </form>

    </div>

</div>
</div>

<?php require __DIR__ . '/../../layout/footer.php'; ?>
