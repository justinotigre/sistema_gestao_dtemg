<?php
require_once __DIR__ . '/../../../App/auth.php';
require_login();


$pdo = db();

$erro = '';

$nome = '';
$sigla = '';
$descricao = '';
$estado = 'ATIVO';

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
            | VERIFICAR DUPLICIDADE DO NOME
            |--------------------------------------------------------------------------
            */

            $stmt = $pdo->prepare("
                SELECT id_ramo
                FROM ramo
                WHERE nome = :nome
                LIMIT 1
            ");

            $stmt->execute([
                ':nome' => $nome
            ]);

            if ($stmt->fetch()) {

                $erro = 'Já existe um ramo com este nome.';

            } else {

                /*
                |--------------------------------------------------------------------------
                | VERIFICAR DUPLICIDADE DA SIGLA
                |--------------------------------------------------------------------------
                */

                $stmt = $pdo->prepare("
                    SELECT id_ramo
                    FROM ramo
                    WHERE sigla = :sigla
                    LIMIT 1
                ");

                $stmt->execute([
                    ':sigla' => $sigla
                ]);

                if ($stmt->fetch()) {

                    $erro = 'Já existe um ramo com esta sigla.';

                } else {

                    /*
                    |--------------------------------------------------------------------------
                    | INSERIR RAMO
                    |--------------------------------------------------------------------------
                    */

                    $stmt = $pdo->prepare("
                        INSERT INTO ramo
                        (
                            nome,
                            sigla,
                            descricao,
                            estado
                        )
                        VALUES
                        (
                            :nome,
                            :sigla,
                            :descricao,
                            :estado
                        )
                    ");

                    $stmt->execute([
                        ':nome' => $nome,
                        ':sigla' => $sigla,
                        ':descricao' => $descricao !== '' ? $descricao : null,
                        ':estado' => $estado
                    ]);

                    /*
                    |--------------------------------------------------------------------------
                    | VOLTAR PARA RAMOS
                    |--------------------------------------------------------------------------
                    */

                    header('Location: ramos.php?criado=1');
                    exit;
                }
            }

        } catch (PDOException $e) {

            $erro = 'Erro ao cadastrar o ramo: ' . $e->getMessage();
        }
    }
}

function h($valor)
{
    return htmlspecialchars((string)$valor, ENT_QUOTES, 'UTF-8');
}
$page_title = "Ramo Novo";
require __DIR__ . '/../../layout/header.php';
?>

<div class="module-page module-ramo-novo">
<style>


        .module-page.module-ramo-novo {
            font-family: Arial, sans-serif;
            background: #f4f6f8;
            margin: 0;
            padding: 30px;
        }

        .module-page.module-ramo-novo .container {
            max-width: 800px;
            margin: auto;
        }

        .module-page.module-ramo-novo .topo {
            margin-bottom: 25px;
        }

        .module-page.module-ramo-novo .topo h1 {
            margin: 0 0 5px;
        }

        .module-page.module-ramo-novo .topo p {
            margin: 0;
            color: #666;
        }

        .module-page.module-ramo-novo .card {
            background: white;
            border-radius: 10px;
            padding: 25px;
            box-shadow: 0 2px 10px rgba(0,0,0,.08);
        }

        .module-page.module-ramo-novo .alert {
            background: #f8d7da;
            color: #842029;
            padding: 12px 15px;
            border-radius: 6px;
            margin-bottom: 20px;
        }

        .module-page.module-ramo-novo .campo {
            margin-bottom: 20px;
        }

        .module-page.module-ramo-novo label {
            display: block;
            margin-bottom: 7px;
            font-weight: bold;
        }

        .module-page.module-ramo-novo input, .module-page.module-ramo-novo textarea, .module-page.module-ramo-novo select {
            width: 100%;
            padding: 11px 12px;
            border: 1px solid #ced4da;
            border-radius: 6px;
            font-size: 14px;
            box-sizing: border-box;
        }

        .module-page.module-ramo-novo textarea {
            min-height: 120px;
            resize: vertical;
        }

        .module-page.module-ramo-novo input:focus, .module-page.module-ramo-novo textarea:focus, .module-page.module-ramo-novo select:focus {
            outline: none;
            border-color: #0d6efd;
        }

        .module-page.module-ramo-novo .acoes {
            display: flex;
            gap: 10px;
            margin-top: 25px;
        }

        .module-page.module-ramo-novo .btn {
            display: inline-block;
            padding: 10px 16px;
            border-radius: 6px;
            text-decoration: none;
            border: none;
            cursor: pointer;
            font-size: 14px;
        }

        .module-page.module-ramo-novo .btn-primary {
            background: #0d6efd;
            color: white;
        }

        .module-page.module-ramo-novo .btn-secondary {
            background: #6c757d;
            color: white;
        }

        .module-page.module-ramo-novo .obrigatorio {
            color: #dc3545;
        }

    
</style>
<div class="container">

    <div class="topo">

        <h1>Novo Ramo</h1>

        <p>
            Cadastro de um novo ramo da instituição.
        </p>

    </div>


    <?php if ($erro): ?>

        <div class="alert">
            <?= h($erro) ?>
        </div>

    <?php endif; ?>


    <div class="card">

        <form method="POST" action="">

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
                    placeholder="Ex.: Exército"
                    required
                >

            </div>


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
                    placeholder="Ex.: EXE"
                    required
                >

            </div>


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
                        <?= $estado === 'ATIVO' ? 'selected' : '' ?>
                    >
                        ATIVO
                    </option>

                    <option
                        value="INATIVO"
                        <?= $estado === 'INATIVO' ? 'selected' : '' ?>
                    >
                        INATIVO
                    </option>

                </select>

            </div>


            <div class="acoes">

                <button
                    type="submit"
                    class="btn btn-primary"
                >
                    Cadastrar Ramo
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
