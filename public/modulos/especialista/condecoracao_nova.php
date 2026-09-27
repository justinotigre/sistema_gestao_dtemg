<?php
require_once __DIR__ . '/../../../App/auth.php';
require_login();


$pdo = db();

/*
|--------------------------------------------------------------------------
| Verificar especialista
|--------------------------------------------------------------------------
*/

$id_especialista = isset($_GET['id_especialista'])
    ? (int) $_GET['id_especialista']
    : 0;

if ($id_especialista <= 0) {
    header('Location: especialistas.php');
    exit;
}

/*
|--------------------------------------------------------------------------
| Buscar especialista
|--------------------------------------------------------------------------
*/

$stmt = $pdo->prepare("
    SELECT
        id_especialista,
        nome_completo
    FROM especialistas
    WHERE id_especialista = ?
    LIMIT 1
");

$stmt->execute([$id_especialista]);

$especialista = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$especialista) {
    header('Location: especialistas.php');
    exit;
}

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
        | Confirmar se o tipo existe e está ativo
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

            /*
            |--------------------------------------------------------------------------
            | Inserir condecoração
            |--------------------------------------------------------------------------
            */

            try {

                $stmt = $pdo->prepare("
                    INSERT INTO condecoracoes (
                        id_especialista,
                        id_tipo_condecoracao,
                        entidade,
                        data_atribuicao,
                        motivo,
                        numero_documento,
                        observacoes
                    )
                    VALUES (
                        :id_especialista,
                        :id_tipo_condecoracao,
                        :entidade,
                        :data_atribuicao,
                        :motivo,
                        :numero_documento,
                        :observacoes
                    )
                ");

                $stmt->execute([
                    ':id_especialista' => $id_especialista,
                    ':id_tipo_condecoracao' => $id_tipo_condecoracao,
                    ':entidade' => $entidade,
                    ':data_atribuicao' => $data_atribuicao,
                    ':motivo' => $motivo !== '' ? $motivo : null,
                    ':numero_documento' => $numero_documento !== '' ? $numero_documento : null,
                    ':observacoes' => $observacoes !== '' ? $observacoes : null
                ]);

                header(
                    'Location: especialista.php?id='
                    . $id_especialista
                    . '&condecoracao=criada'
                );

                exit;

            } catch (PDOException $e) {

                $erro = 'Não foi possível cadastrar a condecoração.';
            }
        }
    }
}
$page_title = "Condecoracao Nova";
require __DIR__ . '/../../layout/header.php';
?>

<div class="module-page module-condecoracao-nova">
<style>


        .module-page.module-condecoracao-nova {
            font-family: Arial, sans-serif;
            background: #f4f6f8;
            margin: 0;
            padding: 30px;
        }

        .module-page.module-condecoracao-nova .container {
            max-width: 900px;
            margin: auto;
        }

        .module-page.module-condecoracao-nova .topo {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
            gap: 15px;
        }

        .module-page.module-condecoracao-nova h1 {
            margin: 0;
        }

        .module-page.module-condecoracao-nova .subtitulo {
            margin-top: 6px;
            color: #666;
        }

        .module-page.module-condecoracao-nova .card {
            background: white;
            border-radius: 10px;
            padding: 25px;
            box-shadow: 0 2px 10px rgba(0,0,0,.08);
        }

        .module-page.module-condecoracao-nova .especialista {
            background: #eef3f8;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 25px;
        }

        .module-page.module-condecoracao-nova .especialista strong {
            display: block;
            margin-bottom: 5px;
        }

        .module-page.module-condecoracao-nova .form-group {
            margin-bottom: 18px;
        }

        .module-page.module-condecoracao-nova label {
            display: block;
            font-weight: bold;
            margin-bottom: 7px;
        }

        .module-page.module-condecoracao-nova input, .module-page.module-condecoracao-nova select, .module-page.module-condecoracao-nova textarea {
            width: 100%;
            padding: 11px;
            border: 1px solid #ccc;
            border-radius: 6px;
            box-sizing: border-box;
            font-size: 14px;
        }

        .module-page.module-condecoracao-nova textarea {
            min-height: 110px;
            resize: vertical;
        }

        .module-page.module-condecoracao-nova .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }

        .module-page.module-condecoracao-nova .botoes {
            display: flex;
            gap: 10px;
            margin-top: 25px;
        }

        .module-page.module-condecoracao-nova .btn {
            display: inline-block;
            padding: 11px 18px;
            border-radius: 6px;
            border: none;
            text-decoration: none;
            cursor: pointer;
            font-size: 14px;
        }

        .module-page.module-condecoracao-nova .btn-primary {
            background: #0d6efd;
            color: white;
        }

        .module-page.module-condecoracao-nova .btn-secondary {
            background: #6c757d;
            color: white;
        }

        .module-page.module-condecoracao-nova .alert {
            background: #f8d7da;
            color: #842029;
            padding: 12px 15px;
            border-radius: 6px;
            margin-bottom: 20px;
        }

        .module-page.module-condecoracao-nova .obrigatorio {
            color: #dc3545;
        }

        @media (max-width: 700px) {

            .module-page.module-condecoracao-nova {
                padding: 15px;
            }

            .module-page.module-condecoracao-nova .topo {
                flex-direction: column;
                align-items: flex-start;
            }

            .module-page.module-condecoracao-nova .form-row {
                grid-template-columns: 1fr;
                gap: 0;
            }

            .module-page.module-condecoracao-nova .botoes {
                flex-direction: column;
            }

            .module-page.module-condecoracao-nova .btn {
                text-align: center;
            }

        }

    
</style>
<div class="container">

    <div class="topo">

        <div>

            <h1>Nova Condecoração</h1>

            <div class="subtitulo">
                Registar uma nova condecoração do especialista
            </div>

        </div>

        <a
            href="especialista.php?id=<?= (int) $id_especialista ?>"
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

            <?= h($especialista['nome_completo']) ?>

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
                                isset($_POST['id_tipo_condecoracao'])
                                && (int) $_POST['id_tipo_condecoracao']
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
                        value="<?= h($_POST['entidade'] ?? '') ?>"
                        placeholder="Ex.: Comando do Exército"
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
                        value="<?= h($_POST['data_atribuicao'] ?? '') ?>"
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
                        value="<?= h($_POST['numero_documento'] ?? '') ?>"
                        placeholder="Número do despacho, ordem, diploma, etc."
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
                        value="<?= h($_POST['motivo'] ?? '') ?>"
                        placeholder="Motivo da atribuição"
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
                    placeholder="Informações adicionais..."
                ><?= h($_POST['observacoes'] ?? '') ?></textarea>

            </div>


            <div class="botoes">

                <button
                    type="submit"
                    class="btn btn-primary"
                >
                    Guardar Condecoração
                </button>

                <a
                    href="especialista.php?id=<?= (int) $id_especialista ?>"
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
