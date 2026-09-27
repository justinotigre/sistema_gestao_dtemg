<?php
require_once __DIR__ . '/../../../App/auth.php';
require_login();


$pdo = db();

$params = [];
$where = scope_sql($params, 'e');

$pesquisa = trim($_GET['pesquisa'] ?? '');
$ramo = (int)($_GET['ramo'] ?? 0);
$quadro = (int)($_GET['quadro'] ?? 0);
$situacao = (int)($_GET['situacao'] ?? 0);

if ($pesquisa !== '') {
    $where .= " AND (
        dp.nome_completo LIKE ?
        OR ds.nip LIKE ?
        OR ds.numero_ordem LIKE ?
        OR dp.numero_bi LIKE ?
    )";

    $termo = '%' . $pesquisa . '%';

    $params[] = $termo;
    $params[] = $termo;
    $params[] = $termo;
    $params[] = $termo;
}

if ($ramo > 0) {
    $where .= " AND e.id_ramo = ?";
    $params[] = $ramo;
}

if ($quadro > 0) {
    $where .= " AND e.id_quadro = ?";
    $params[] = $quadro;
}

if ($situacao > 0) {
    $where .= " AND ds.id_situacao_servico = ?";
    $params[] = $situacao;
}


/*
|--------------------------------------------------------------------------
| Dados dos filtros
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

$situacoes = $pdo->query("
    SELECT id_situacao_servico, nome, grupo
    FROM situacoes_servico
    WHERE estado = 'ATIVO'
    ORDER BY nome
")->fetchAll(PDO::FETCH_ASSOC);


/*
|--------------------------------------------------------------------------
| Especialistas
|--------------------------------------------------------------------------
*/

$sql = "
    SELECT
        e.id_especialista,
        e.id_ramo,
        e.id_quadro,
        e.id_patente,
        e.estado,

        (
            SELECT f.caminho_ficheiro
            FROM fotografias f
            WHERE f.id_especialista = e.id_especialista
            ORDER BY f.principal DESC, f.data_registo DESC, f.id_fotografia DESC
            LIMIT 1
        ) AS foto_caminho,

        dp.nome_completo,
        dp.numero_bi,
        dp.telefone,

        r.nome AS ramo_nome,
        r.sigla AS ramo_sigla,

        q.nome AS quadro_nome,
        q.sigla AS quadro_sigla,

        p.nome AS patente_nome,
        p.sigla AS patente_sigla,

        ds.nip,
        ds.numero_ordem,
        ds.id_unidade,
        ds.id_situacao_servico,

        u.nome AS unidade_nome,
        u.sigla AS unidade_sigla,

        ss.nome AS situacao_nome,
        ss.grupo AS situacao_grupo

    FROM especialistas e

    INNER JOIN dados_pessoais dp
        ON dp.id_especialista = e.id_especialista

    INNER JOIN ramo r
        ON r.id_ramo = e.id_ramo

    INNER JOIN quadros_servico q
        ON q.id_quadro = e.id_quadro

    LEFT JOIN patentes p
        ON p.id_patente = e.id_patente

    LEFT JOIN dados_servico ds
        ON ds.id_especialista = e.id_especialista

    LEFT JOIN unidades u
        ON u.id_unidade = ds.id_unidade

    LEFT JOIN situacoes_servico ss
        ON ss.id_situacao_servico = ds.id_situacao_servico

    WHERE $where

    ORDER BY COALESCE(p.ordem, 999999) ASC, COALESCE(ds.cargo, '') ASC, dp.nome_completo ASC
";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);

$especialistas = $stmt->fetchAll(PDO::FETCH_ASSOC);
$page_title = "Especialistas";
require __DIR__ . '/../../layout/header.php';
?>

<div class="module-page module-especialistas">
<style>
.module-especialistas .page-header{display:flex;justify-content:space-between;align-items:flex-end;gap:20px;margin-bottom:24px}
.module-especialistas .page-header h1{margin:0;font-size:34px}
.module-especialistas .page-header p{margin:7px 0 0;color:var(--suave)}
.module-especialistas .filtros{background:var(--superficie);border:1px solid var(--borda);border-radius:14px;padding:18px 20px;margin-bottom:18px;box-shadow:var(--sombra)}
.module-especialistas .filtros-grid{display:grid;grid-template-columns:minmax(220px,2fr) minmax(145px,1fr) minmax(145px,1fr) minmax(145px,1fr) minmax(170px,auto);gap:12px;align-items:end}
.module-especialistas .campo{min-width:0;display:flex;flex-direction:column;gap:6px}.module-especialistas .campo label{font-size:12px;font-weight:700;color:var(--texto)}
.module-especialistas .campo input,.module-especialistas .campo select{width:100%;height:42px;padding:0 11px;border:1px solid var(--borda);border-radius:9px;background:var(--superficie);color:var(--texto);outline:none}
.module-especialistas .campo input:focus,.module-especialistas .campo select:focus{border-color:#5b8fb5;box-shadow:0 0 0 3px rgba(31,95,139,.10)}
.module-especialistas .filtro-acoes{display:grid;grid-template-columns:1fr 1fr;gap:8px}.module-especialistas .filtro-acoes .btn{height:42px;padding:0 12px;white-space:nowrap}
.module-especialistas .contador{margin:0 0 9px;color:var(--suave);font-size:13px}
.module-especialistas .tabela-container{border:1px solid var(--borda);border-radius:14px;background:var(--superficie);box-shadow:var(--sombra);overflow:auto}
.module-especialistas table{width:100%;min-width:980px;border-collapse:separate;border-spacing:0;table-layout:fixed;font-size:13px}
.module-especialistas th,.module-especialistas td{padding:11px 10px;border-bottom:1px solid var(--borda);text-align:left;vertical-align:middle;overflow:hidden;text-overflow:ellipsis}
.module-especialistas th{height:48px;background:var(--azul-suave);color:var(--texto);font-size:12px;font-weight:750;white-space:nowrap;position:sticky;top:0;z-index:4}
.module-especialistas tbody tr:last-child td{border-bottom:0}.module-especialistas tbody tr:hover td{background:color-mix(in srgb,var(--azul-suave) 45%,var(--superficie))}
.module-especialistas th:nth-child(1),.module-especialistas td:nth-child(1){width:66px;position:sticky;left:0;z-index:3;background:var(--superficie)}
.module-especialistas th:nth-child(2),.module-especialistas td:nth-child(2){width:190px;position:sticky;left:66px;z-index:3;background:var(--superficie)}
.module-especialistas th:nth-child(3),.module-especialistas td:nth-child(3){width:100px}.module-especialistas th:nth-child(4),.module-especialistas td:nth-child(4){width:105px}.module-especialistas th:nth-child(5),.module-especialistas td:nth-child(5){width:105px}.module-especialistas th:nth-child(6),.module-especialistas td:nth-child(6){width:110px}.module-especialistas th:nth-child(7),.module-especialistas td:nth-child(7){width:125px}.module-especialistas th:nth-child(8),.module-especialistas td:nth-child(8){width:105px}.module-especialistas th:nth-child(9),.module-especialistas td:nth-child(9){width:112px;position:sticky;right:0;z-index:3;background:var(--superficie)}
.module-especialistas thead th:nth-child(1),.module-especialistas thead th:nth-child(2),.module-especialistas thead th:nth-child(9){z-index:6;background:var(--azul-suave)}
.module-especialistas .celula-foto{text-align:center}.module-especialistas .foto-lista{width:40px;height:40px;display:inline-flex;align-items:center;justify-content:center;object-fit:cover;border-radius:9px;border:1px solid var(--borda);background:#edf2f5;color:#7d8a95;vertical-align:middle}
.module-especialistas .nome{display:block;font-weight:700;white-space:nowrap;overflow:hidden;text-overflow:ellipsis}.module-especialistas .subtexto{display:block;color:var(--suave);font-size:10px;margin-top:3px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis}
.module-especialistas .sigla-cell{display:inline-block;font-weight:700;letter-spacing:.02em}.module-especialistas .sigla-cell:hover{text-decoration:underline}.module-especialistas .acoes{display:flex;align-items:center;justify-content:center;gap:5px;white-space:nowrap}.module-especialistas .acoes .btn{height:32px;padding:0 9px;font-size:11px;border-radius:7px;min-width:43px}
.module-especialistas .badge{display:inline-flex;align-items:center;padding:4px 7px;border-radius:999px;font-size:10px;font-weight:750;white-space:nowrap}.module-especialistas .badge-ativo{background:#dff5e5;color:#18723a}.module-especialistas .badge-inativo{background:#f3dddd;color:#9b2c2c}.module-especialistas .vazio{padding:38px;text-align:center;color:var(--suave)}
html.modo-escuro .module-especialistas th:nth-child(1),html.modo-escuro .module-especialistas td:nth-child(1),html.modo-escuro .module-especialistas th:nth-child(2),html.modo-escuro .module-especialistas td:nth-child(2),html.modo-escuro .module-especialistas th:nth-child(9),html.modo-escuro .module-especialistas td:nth-child(9){background:var(--superficie)}
@media(max-width:1100px){.module-especialistas .filtros-grid{grid-template-columns:2fr 1fr 1fr}.module-especialistas .filtro-acoes{grid-column:1/-1;max-width:300px}}
@media(max-width:700px){.module-especialistas .page-header{align-items:flex-start;flex-direction:column}.module-especialistas .filtros-grid{grid-template-columns:1fr}.module-especialistas .filtro-acoes{grid-column:auto;max-width:none}}
</style>
    <div class="page-header">

        <div>

            <h1>Especialistas</h1>

            <p>
                Gestão e consulta do cadastro de especialistas.
            </p>

        </div>

        <a
            href="especialista_novo.php"
            class="btn btn-primary"
        >
            + Novo Especialista
        </a>

    </div>


    <!-- FILTROS -->

    <form
        method="get"
        class="filtros"
    >

        <div class="filtros-grid">

            <div class="campo">

                <label>Pesquisa</label>

                <input
                    type="text"
                    name="pesquisa"
                    value="<?= htmlspecialchars($pesquisa) ?>"
                    placeholder="Nome, NIP, Nº de ordem ou BI"
                >

            </div>


            <div class="campo">

                <label>Ramo</label>

                <select name="ramo">

                    <option value="">Todos</option>

                    <?php foreach ($ramos as $item): ?>

                        <option
                            value="<?= $item['id_ramo'] ?>"
                            <?= $ramo == $item['id_ramo'] ? 'selected' : '' ?>
                        >

                            <?= htmlspecialchars($item['nome']) ?>

                            <?php if (!empty($item['sigla'])): ?>
                                (<?= htmlspecialchars($item['sigla']) ?>)
                            <?php endif; ?>

                        </option>

                    <?php endforeach; ?>

                </select>

            </div>


            <div class="campo">

                <label>Quadro</label>

                <select name="quadro">

                    <option value="">Todos</option>

                    <?php foreach ($quadros as $item): ?>

                        <option
                            value="<?= $item['id_quadro'] ?>"
                            <?= $quadro == $item['id_quadro'] ? 'selected' : '' ?>
                        >

                            <?= htmlspecialchars($item['nome']) ?>

                        </option>

                    <?php endforeach; ?>

                </select>

            </div>


            <div class="campo">

                <label>Situação</label>

                <select name="situacao">

                    <option value="">Todas</option>

                    <?php foreach ($situacoes as $item): ?>

                        <option
                            value="<?= $item['id_situacao_servico'] ?>"
                            <?= $situacao == $item['id_situacao_servico'] ? 'selected' : '' ?>
                        >

                            <?= htmlspecialchars($item['nome']) ?>

                        </option>

                    <?php endforeach; ?>

                </select>

            </div>


            <div class="filtro-acoes">
                <button type="submit" class="btn btn-primary">Pesquisar</button>
                <a href="especialistas.php" class="btn btn-secondary">Limpar</a>
            </div>

        </div>

    </form>


    <div class="contador">

        <?= count($especialistas) ?>

        especialista(s) encontrado(s)

    </div>


    <!-- TABELA -->

    <div class="tabela-container">

        <?php if (!$especialistas): ?>

            <div class="vazio">

                Nenhum especialista encontrado.

            </div>

        <?php else: ?>

            <table>

                <thead>

                    <tr>

                        <th class="col-foto">Foto</th>
                        <th>Especialista</th>
                        <th>NIP</th>
                        <th>Patente</th>

                        <th>Ramo</th>

                        <th>Quadro</th>

                        <th>Unidade</th>

                        <th>Situação</th>

                        <th>Ações</th>

                    </tr>

                </thead>

                <tbody>

                <?php foreach ($especialistas as $item): ?>

                    <tr>

                        <td class="celula-foto">
                            <?php if (!empty($item['foto_caminho'])): ?>
                                <img class="foto-lista"
                                     src="<?= htmlspecialchars(app_file_url($item['foto_caminho'])) ?>"
                                     alt="Fotografia de <?= htmlspecialchars($item['nome_completo']) ?>">
                            <?php else: ?>
                                <span class="foto-lista foto-vazia">👤</span>
                            <?php endif; ?>
                        </td>

                        <td>
                            <span class="nome">
                                <?= htmlspecialchars($item['nome_completo']) ?>
                            </span>

                            <?php if (!empty($item['numero_bi'])): ?>

                                <span class="subtexto">

                                    BI:
                                    <?= htmlspecialchars(
                                        $item['numero_bi']
                                    ) ?>

                                </span>

                            <?php endif; ?>

                        </td>


                        <td>

                            <?= htmlspecialchars(
                                $item['nip'] ?? '—'
                            ) ?>

                            <?php if (!empty($item['numero_ordem'])): ?>

                                <span class="subtexto">

                                    Ordem:
                                    <?= htmlspecialchars(
                                        $item['numero_ordem']
                                    ) ?>

                                </span>

                            <?php endif; ?>

                        </td>


                        <td title="<?= htmlspecialchars($item['patente_nome'] ?? '—') ?>">
                            <span class="sigla-cell"><?= htmlspecialchars($item['patente_sigla'] ?: ($item['patente_nome'] ?? '—')) ?></span>
                        </td>


                        <td title="<?= htmlspecialchars($item['ramo_nome']) ?>">
                            <span class="sigla-cell"><?= htmlspecialchars($item['ramo_sigla'] ?: $item['ramo_nome']) ?></span>
                        </td>


                        <td title="<?= htmlspecialchars($item['quadro_nome']) ?>">
                            <span class="sigla-cell"><?= htmlspecialchars($item['quadro_sigla'] ?: $item['quadro_nome']) ?></span>
                        </td>


                        <td title="<?= htmlspecialchars($item['unidade_nome'] ?? '—') ?>">
                            <span class="sigla-cell"><?= htmlspecialchars($item['unidade_sigla'] ?: ($item['unidade_nome'] ?? '—')) ?></span>
                        </td>


                        <td>

                            <?php if (
                                ($item['situacao_grupo'] ?? '') === 'ATIVO'
                            ): ?>

                                <span class="badge badge-ativo">

                                    <?= htmlspecialchars(
                                        $item['situacao_nome'] ?? 'ATIVO'
                                    ) ?>

                                </span>

                            <?php else: ?>

                                <span class="badge badge-inativo">

                                    <?= htmlspecialchars(
                                        $item['situacao_nome'] ?? 'INATIVO'
                                    ) ?>

                                </span>

                            <?php endif; ?>

                        </td>


                        <td class="acoes">

                            <a
                                href="especialista.php?id=<?= (int)$item['id_especialista'] ?>"
                                class="btn btn-secondary btn-small"
                            >
                                Ver
                            </a>

                            <a
                                href="especialista_editar.php?id=<?= (int)$item['id_especialista'] ?>"
                                class="btn btn-primary btn-small"
                            >
                                Editar
                            </a>

                        </td>

                    </tr>

                <?php endforeach; ?>

                </tbody>

            </table>

        <?php endif; ?>

    </div>

</div>

<?php require __DIR__ . '/../../layout/footer.php'; ?>
