<?php
require_once __DIR__ . '/../../../App/auth.php';
require_login();


$pdo = db();

$erro = '';

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if (!$id || $id <= 0) {
    header('Location: ramos.php');
    exit;
}


/*
|--------------------------------------------------------------------------
| BUSCAR RAMO
|--------------------------------------------------------------------------
*/

$stmt = $pdo->prepare("
    SELECT
        id_ramo,
        nome,
        sigla,
        descricao,
        estado,
        data_criacao
    FROM ramo
    WHERE id_ramo = :id
    LIMIT 1
");

$stmt->execute([
    ':id' => $id
]);

$ramo = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$ramo) {
    header('Location: ramos.php');
    exit;
}


/*
|--------------------------------------------------------------------------
| VALORES INICIAIS
|--------------------------------------------------------------------------
*/

$nome = $ramo['nome'];
$sigla = $ramo['sigla'];
$descricao = $ramo['descricao'];
$estado = $ramo['estado'];


/*
|--------------------------------------------------------------------------
| PROCESSAR FORMULÁRIO
|--------------------------------------------------------------------------
*/

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $nome = trim($_POST['nome'] ?? '');
    $sigla = trim($_POST['sigla'] ?? '');
    $descricao = trim($_POST['descricao'] ?? '');
    $estado = $_POST['estado'] ?? 'ATIVO';


    /*
    |--------------------------------------------------------------------------
    | VALIDAÇÕES
    |--------------------------------------------------------------------------
    */

    if ($nome === '') {

        $erro = 'Informe o nome do ramo.';

    } elseif ($sigla === '') {

        $erro = 'Informe a sigla do ramo.';

    } elseif (!in_array($estado, ['ATIVO', 'INATIVO'], true)) {

        $erro = 'Estado inválido.';

    } else {

        try {

            /*
            |--------------------------------------------------------------------------
            | VERIFICAR NOME DUPLICADO
            |--------------------------------------------------------------------------
            */

            $stmt = $pdo->prepare("
                SELECT id_ramo
                FROM ramo
                WHERE nome = :nome
                  AND id_ramo <> :id
                LIMIT 1
            ");

            $stmt->execute([
                ':nome' => $nome,
                ':id' => $id
            ]);

            if ($stmt->fetch()) {

                $erro = 'Já existe outro ramo com este nome.';

            } else {

                /*
                |--------------------------------------------------------------------------
                | VERIFICAR SIGLA DUPLICADA
                |--------------------------------------------------------------------------
                */

                $stmt = $pdo->prepare("
                    SELECT id_ramo
                    FROM ramo
                    WHERE sigla = :sigla
                      AND id_ramo <> :id
                    LIMIT 1
                ");

                $stmt->execute([
                    ':sigla' => $sigla,
                    ':id' => $id
                ]);

                if ($stmt->fetch()) {

                    $erro = 'Já existe outro ramo com esta sigla.';

                } else {

                    /*
                    |--------------------------------------------------------------------------
                    | ATUALIZAR
                    |--------------------------------------------------------------------------
                    */

                    $stmt = $pdo->prepare("
                        UPDATE ramo
                        SET
                            nome = :nome,
                            sigla = :sigla,
                            descricao = :descricao,
                            estado = :estado
                        WHERE id_ramo = :id
                    ");

                    $stmt->execute([
                        ':nome' => $nome,
                        ':sigla' => $sigla,
                        ':descricao' => $descricao !== ''
                            ? $descricao
                            : null,
                        ':estado' => $estado,
                        ':id' => $id
                    ]);


                    /*
                    |--------------------------------------------------------------------------
                    | VOLTAR PARA LISTA
                    |--------------------------------------------------------------------------
                    */

                    header('Location: ramos.php?atualizado=1');
                    exit;
                }
            }

        } catch (PDOException $e) {

            $erro = 'Erro ao atualizar o ramo: ' . $e->getMessage();
        }
    }
}


/*
|--------------------------------------------------------------------------
| FUNÇÃO DE SEGURANÇA
|--------------------------------------------------------------------------
*/

function h($valor)
{
    return htmlspecialchars(
        (string)$valor,
        ENT_QUOTES,
        'UTF-8'
    );
}
$page_title = "Ramo Editar";
require __DIR__ . '/../../layout/header.php';
?>

<div class="module-page module-ramo-editar">
<style>


        .module-page.module-ramo-editar {
            font-family: Arial, sans-serif;
            background: #f4f6f8;
            margin: 0;
            padding: 30px;
        }

        .module-page.module-ramo-editar .container {
            max-width: 800px;
            margin: auto;
        }

        .module-page.module-ramo-editar .topo {
            margin-bottom: 25px;
        }

        .module-page.module-ramo-editar .topo h1 {
            margin: 0 0 5px;
        }

        .module-page.module-ramo-editar .topo p {
            margin: 0;
            color: #666;
        }

        .module-page.module-ramo-editar .card {
            background: white;
            border-radius: 10px;
            padding: 25px;
            box-shadow: 0 2px 10px rgba(0,0,0,.08);
        }

        .module-page.module-ramo-editar .alert {
            background: #f8d7da;
            color: #842029;
            padding: 12px 15px;
            border-radius: 6px;
            margin-bottom: 20px;
        }

        .module-page.module-ramo-editar .campo {
            margin-bottom: 20px;
        }

        .module-page.module-ramo-editar label {
            display: block;
            margin-bottom: 7px;
            font-weight: bold;
        }

        .module-page.module-ramo-editar input, .module-page.module-ramo-editar textarea, .module-page.module-ramo-editar select {
            width: 100%;
            padding: 11px 12px;
            border: 1px solid #ced4da;
            border-radius: 6px;
            font-size: 14px;
            box-sizing: border-box;
        }

        .module-page.module-ramo-editar textarea {
            min-height: 120px;
            resize: vertical;
        }

        .module-page.module-ramo-editar input:focus, .module-page.module-ramo-editar textarea:focus, .module-page.module-ramo-editar select:focus {
            outline: none;
            border-color: #0d6efd;
        }

        .module-page.module-ramo-editar .acoes {
            display: flex;
            gap: 10px;
            margin-top: 25px;
        }

        .module-page.module-ramo-editar .btn {
            display: inline-block;
            padding: 10px 16px;
            border-radius: 6px;
            text-decoration: none;
            border: none;
            cursor: pointer;
            font-size: 14px;
        }

        .module-page.module-ramo-editar .btn-primary {
            background: #0d6efd;
            color: white;
        }

        .module-page.module-ramo-editar .btn-primary:hover {
            background: #0b5ed7;
        }

        .module-page.module-ramo-editar .btn-secondary {
            background: #6c757d;
            color: white;
        }

        .module-page.module-ramo-editar .btn-secondary:hover {
            background: #5c636a;
        }

        .module-page.module-ramo-editar .obrigatorio {
            color: #dc3545;
        }

        .module-page.module-ramo-editar .informacao {
            background: #f1f3f5;
            border-radius: 6px;
            padding: 12px 15px;
            margin-bottom: 20px;
            font-size: 13px;
            color: #666;
        }

    
</style>
<div class="container">


    <div class="topo">

        <h1>Editar Ramo</h1>

        <p>
            Alteração dos dados do ramo.
        </p>

    </div>


    <?php if ($erro): ?>

        <div class="alert">

            <?= h($erro) ?>

        </div>

    <?php endif; ?>


    <div class="card">


        <div class="informacao">

            ID do ramo:
            <strong>
                <?= h($id) ?>
            </strong>

        </div>


        <form method="POST" action="">


            <!-- NOME -->

            <div class="campo">

                <label for="nome">

                    Nome do Ramo

                    <span class="obrigatorio">*</span>

                </label>

                <input
                    type="text"
                    id="nome"
                    name="nome"
                    maxlength="150"
                    value="<?= h($nome) ?>"
                    required
                >

            </div>


            <!-- SIGLA -->

            <div class="campo">

                <label for="sigla">

                    Sigla

                    <span class="obrigatorio">*</span>

                </label>

                <input
                    type="text"
                    id="sigla"
                    name="sigla"
                    maxlength="30"
                    value="<?= h($sigla) ?>"
                    required
                >

            </div>


            <!-- DESCRIÇÃO -->

            <div class="campo">

                <label for="descricao">

                    Descrição

                </label>

                <textarea
                    id="descricao"
                    name="descricao"
                    placeholder="Descrição do ramo..."
                ><?= h($descricao) ?></textarea>

            </div>


            <!-- ESTADO -->

            <div class="campo">

                <label for="estado">

                    Estado

                </label>

                <select
                    id="estado"
                    name="estado"
                >

                    <option
                        value="ATIVO"
                        <?= $estado === 'ATIVO'
                            ? 'selected'
                            : ''
                        ?>
                    >
                        ATIVO
                    </option>

                    <option
                        value="INATIVO"
                        <?= $estado === 'INATIVO'
                            ? 'selected'
                            : ''
                        ?>
                    >
                        INATIVO
                    </option>

                </select>

            </div>


            <!-- AÇÕES -->

            <div class="acoes">

                <button
                    type="submit"
                    class="btn btn-primary"
                >
                    Guardar Alterações
                </button>


                <a
                    href="ramos.php"
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
