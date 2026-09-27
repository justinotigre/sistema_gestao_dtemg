<?php
require_once __DIR__ . '/../../../App/auth.php';
require_login();


$pdo = db();

/*
|--------------------------------------------------------------------------
| BUSCAR RAMOS
|--------------------------------------------------------------------------
*/

$stmt = $pdo->query("
    SELECT
        id_ramo,
        nome,
        sigla,
        descricao,
        estado,
        data_criacao
    FROM ramo
    ORDER BY nome ASC
");

$ramos = $stmt->fetchAll(PDO::FETCH_ASSOC);


/*
|--------------------------------------------------------------------------
| MENSAGENS
|--------------------------------------------------------------------------
*/

$mensagem = '';
$tipoMensagem = 'sucesso';

if (isset($_GET['criado'])) {

    $mensagem = 'Ramo criado com sucesso.';

}

if (isset($_GET['atualizado'])) {

    $mensagem = 'Ramo atualizado com sucesso.';

}

if (isset($_GET['estado'])) {

    $mensagem = 'Estado do ramo atualizado com sucesso.';

}

if (isset($_GET['eliminado'])) {

    $mensagem = 'Ramo eliminado com sucesso.';

}

if (isset($_GET['erro'])) {

    if ($_GET['erro'] === 'eliminar') {

        $mensagem = 'Não foi possível eliminar o ramo.';
        $tipoMensagem = 'erro';

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
$page_title = "Ramos";
require __DIR__ . '/../../layout/header.php';
?>

<div class="module-page module-ramos">
<style>


        .module-page.module-ramos {
            font-family: Arial, sans-serif;
            background: #f4f6f8;
            margin: 0;
            padding: 30px;
        }

        .module-page.module-ramos .container {
            max-width: 1200px;
            margin: auto;
        }

        /*
        |--------------------------------------------------------------------------
        | TOPO
        |--------------------------------------------------------------------------
        */

        .module-page.module-ramos .topo {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
            gap: 20px;
        }

        .module-page.module-ramos .topo h1 {
            margin: 0;
        }

        .module-page.module-ramos .topo p {
            margin: 5px 0 0;
            color: #666;
        }

        /*
        |--------------------------------------------------------------------------
        | BOTÕES
        |--------------------------------------------------------------------------
        */

        .module-page.module-ramos .btn {
            display: inline-block;
            padding: 10px 16px;
            border-radius: 6px;
            text-decoration: none;
            border: none;
            cursor: pointer;
            font-size: 14px;
            white-space: nowrap;
        }

        .module-page.module-ramos .btn-primary {
            background: #0d6efd;
            color: white;
        }

        .module-page.module-ramos .btn-primary:hover {
            background: #0b5ed7;
        }

        .module-page.module-ramos .btn-secondary {
            background: #6c757d;
            color: white;
        }

        .module-page.module-ramos .btn-secondary:hover {
            background: #5c636a;
        }

        .module-page.module-ramos .btn-warning {
            background: #ffc107;
            color: #212529;
        }

        .module-page.module-ramos .btn-warning:hover {
            background: #e0a800;
        }

        .module-page.module-ramos .btn-success {
            background: #198754;
            color: white;
        }

        .module-page.module-ramos .btn-success:hover {
            background: #157347;
        }

        .module-page.module-ramos .btn-danger {
            background: #dc3545;
            color: white;
        }

        .module-page.module-ramos .btn-danger:hover {
            background: #bb2d3b;
        }

        /*
        |--------------------------------------------------------------------------
        | ALERTAS
        |--------------------------------------------------------------------------
        */

        .module-page.module-ramos .alert {
            padding: 12px 15px;
            border-radius: 6px;
            margin-bottom: 20px;
        }

        .module-page.module-ramos .alert-sucesso {
            background: #d1e7dd;
            color: #0f5132;
        }

        .module-page.module-ramos .alert-erro {
            background: #f8d7da;
            color: #842029;
        }

        /*
        |--------------------------------------------------------------------------
        | CARD
        |--------------------------------------------------------------------------
        */

        .module-page.module-ramos .card {
            background: white;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, .08);
            overflow: hidden;
        }

        /*
        |--------------------------------------------------------------------------
        | TABELA
        |--------------------------------------------------------------------------
        */

        .module-page.module-ramos table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
        }

        .module-page.module-ramos th, .module-page.module-ramos td {
            padding: 12px;
            border-bottom: 1px solid #ddd;
            text-align: left;
            vertical-align: middle;
        }

        .module-page.module-ramos th {
            background: #f1f3f5;
            color: #343a40;
        }

        .module-page.module-ramos tbody tr:hover {
            background: #f8f9fa;
        }

        /*
        |--------------------------------------------------------------------------
        | ESTADO
        |--------------------------------------------------------------------------
        */

        .module-page.module-ramos .estado {
            display: inline-block;
            padding: 5px 9px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
        }

        .module-page.module-ramos .ativo {
            background: #d1e7dd;
            color: #0f5132;
        }

        .module-page.module-ramos .inativo {
            background: #f8d7da;
            color: #842029;
        }

        /*
        |--------------------------------------------------------------------------
        | AÇÕES
        |--------------------------------------------------------------------------
        */

        .module-page.module-ramos .acoes {
            display: flex;
            gap: 6px;
            flex-wrap: wrap;
        }

        /*
        |--------------------------------------------------------------------------
        | LISTA VAZIA
        |--------------------------------------------------------------------------
        */

        .module-page.module-ramos .vazio {
            text-align: center;
            padding: 40px 20px;
            color: #777;
        }

        /*
        |--------------------------------------------------------------------------
        | RESPONSIVO
        |--------------------------------------------------------------------------
        */

        @media (max-width: 700px) {

            .module-page.module-ramos {
                padding: 15px;
            }

            .module-page.module-ramos .topo {
                flex-direction: column;
                align-items: flex-start;
            }

        }

    
</style>
<div class="container">


    <!--
    |--------------------------------------------------------------------------
    | CABEÇALHO
    |--------------------------------------------------------------------------
    -->

    <div class="topo">

        <div>

            <h1>Ramos</h1>

            <p>
                Gestão dos ramos da instituição
            </p>

        </div>


        <div>

            <a
                href="<?= htmlspecialchars(app_url('modulos/dashboard.php')) ?>"
                class="btn btn-secondary"
            >
                Dashboard
            </a>

            <a
                href="ramo_novo.php"
                class="btn btn-primary"
            >
                + Novo Ramo
            </a>

        </div>

    </div>


    <!--
    |--------------------------------------------------------------------------
    | MENSAGEM
    |--------------------------------------------------------------------------
    -->

    <?php if ($mensagem): ?>

        <div
            class="alert <?= $tipoMensagem === 'erro'
                ? 'alert-erro'
                : 'alert-sucesso'
            ?>"
        >

            <?= h($mensagem) ?>

        </div>

    <?php endif; ?>


    <!--
    |--------------------------------------------------------------------------
    | LISTAGEM
    |--------------------------------------------------------------------------
    -->

    <div class="card">

        <?php if (!$ramos): ?>

            <div class="vazio">

                <p>
                    Ainda não existem ramos cadastrados.
                </p>

                <a
                    href="ramo_novo.php"
                    class="btn btn-primary"
                >
                    + Cadastrar Primeiro Ramo
                </a>

            </div>

        <?php else: ?>


            <table>

                <thead>

                <tr>

                    <th>ID</th>

                    <th>Nome</th>

                    <th>Sigla</th>

                    <th>Descrição</th>

                    <th>Estado</th>

                    <th>Data de criação</th>

                    <th>Ações</th>

                </tr>

                </thead>


                <tbody>


                <?php foreach ($ramos as $ramo): ?>

                    <tr>


                        <!-- ID -->

                        <td>

                            <?= h($ramo['id_ramo']) ?>

                        </td>


                        <!-- NOME -->

                        <td>

                            <strong>
                                <?= h($ramo['nome']) ?>
                            </strong>

                        </td>


                        <!-- SIGLA -->

                        <td>

                            <?= h($ramo['sigla']) ?>

                        </td>


                        <!-- DESCRIÇÃO -->

                        <td>

                            <?= h(
                                $ramo['descricao']
                                    ?: '-'
                            ) ?>

                        </td>


                        <!-- ESTADO -->

                        <td>

                            <?php if ($ramo['estado'] === 'ATIVO'): ?>

                                <span class="estado ativo">
                                    ATIVO
                                </span>

                            <?php else: ?>

                                <span class="estado inativo">
                                    INATIVO
                                </span>

                            <?php endif; ?>

                        </td>


                        <!-- DATA -->

                        <td>

                            <?= $ramo['data_criacao']
                                ? date(
                                    'd/m/Y H:i',
                                    strtotime(
                                        $ramo['data_criacao']
                                    )
                                )
                                : '-'
                            ?>

                        </td>


                        <!-- AÇÕES -->

                        <td>

                            <div class="acoes">


                                <!-- EDITAR -->

                                <a
                                    href="ramo_editar.php?id=<?= (int)$ramo['id_ramo'] ?>"
                                    class="btn btn-warning"
                                >
                                    Editar
                                </a>


                                <!-- ELIMINAR -->

                                <a
                                    href="ramo_eliminar.php?id=<?= (int)$ramo['id_ramo'] ?>"
                                    class="btn btn-danger"
                                    onclick="return confirm('Tem certeza que deseja eliminar o ramo <?= h($ramo['nome']) ?>? Esta operação não poderá ser desfeita.');"
                                >
                                    Eliminar
                                </a>


                                <!-- ATIVAR / INATIVAR -->

                                <?php if ($ramo['estado'] === 'ATIVO'): ?>

                                    <a
                                        href="ramo_estado.php?id=<?= (int)$ramo['id_ramo'] ?>&estado=INATIVO"
                                        class="btn btn-danger"
                                        onclick="return confirm('Deseja inativar este ramo?');"
                                    >
                                        Inativar
                                    </a>

                                <?php else: ?>

                                    <a
                                        href="ramo_estado.php?id=<?= (int)$ramo['id_ramo'] ?>&estado=ATIVO"
                                        class="btn btn-success"
                                        onclick="return confirm('Deseja ativar este ramo?');"
                                    >
                                        Ativar
                                    </a>

                                <?php endif; ?>


                            </div>

                        </td>


                    </tr>

                <?php endforeach; ?>


                </tbody>

            </table>


        <?php endif; ?>

    </div>


</div>
</div>

<?php require __DIR__ . '/../../layout/footer.php'; ?>
