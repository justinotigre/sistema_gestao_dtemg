<?php
require_once __DIR__ . '/../../../App/auth.php';
require_login();


$pdo = db();

$id = (int)($_GET['id'] ?? 0);
$modoImpressao = $_GET['imprimir'] ?? '';
$modoImpressao = in_array($modoImpressao, ['resumida', 'completa', 'secao'], true) ? $modoImpressao : '';
$secaoImpressao = $_GET['secao'] ?? '';
$secaoPermitida = ['pessoais','servico','familiares','formacoes','habilitacoes','condecoracoes','ferias','fotografias','documentos'];
if ($modoImpressao === 'secao' && !in_array($secaoImpressao, $secaoPermitida, true)) { $modoImpressao = ''; }

if (!$id) {
    header('Location: ' . app_url('modulos/dashboard.php'));
    exit;
}

/* ============================================================
   FUNÇÕES
============================================================ */

function e($valor)
{
    return htmlspecialchars(
        (string)$valor,
        ENT_QUOTES,
        'UTF-8'
    );
}

function data_pt($data)
{
    if (!$data) {
        return '—';
    }

    $ts = strtotime($data);

    return $ts ? date('d/m/Y', $ts) : '—';
}

function sigla_html($nome, $sigla = null): string
{
    $nome = trim((string)$nome);
    $sigla = trim((string)$sigla);
    if ($nome === '') return '—';
    if ($sigla === '') return e($nome);
    return '<span class="sigla-tooltip" title="'.e($nome).'">'.e($sigla).'</span>';
}

function redirecionar_especialista($id, $mensagem)
{
    header(
        'Location: especialista.php?id=' .
        (int)$id .
        '&' .
        $mensagem
    );

    exit;
}

/* ============================================================
   CARREGAR ESPECIALISTA
============================================================ */

$stmt = $pdo->prepare("
    SELECT
        e.id_especialista,
        e.id_ramo,
        e.id_quadro,
        e.id_patente,
        e.estado AS estado_especialista,
        e.data_registo,
        e.observacoes AS observacoes_especialista,

        r.nome AS ramo_nome,
        r.sigla AS ramo_sigla,

        q.nome AS quadro_nome,
        q.sigla AS quadro_sigla,

        p.nome AS patente_nome,
        p.sigla AS patente_sigla,
        p.ordem AS patente_ordem,

        dp.nome_completo,
        dp.data_nascimento,
        dp.numero_bi,
        dp.data_emissao_bi,
        dp.data_validade_bi,
        dp.local_emissao_bi,
        dp.telefone,
        dp.email,
        dp.morada,

        sx.nome AS sexo_nome,

        pn.nome AS pais_nascimento_nome,
        pnat.nome AS nacionalidade_nome,

        ec.nome AS estado_civil_nome,

        ds.nip,
        ds.numero_ordem,
        ds.numero_processo,
        ds.data_ingresso,
        ds.data_incorporacao,
        ds.data_promocao,
        ds.cargo,
        ds.data_inicio_funcao,
        ds.data_fim_funcao,
        ds.observacoes AS observacoes_servico,
        ds.id_unidade,

        f.nome AS funcao_nome,

        u.nome AS unidade_nome,
        u.sigla AS unidade_sigla,

        dep.nome AS departamento_nome,

        ss.nome AS situacao_servico_nome,
        ss.grupo AS situacao_grupo

    FROM especialistas e

    INNER JOIN dados_pessoais dp
        ON dp.id_especialista = e.id_especialista

    LEFT JOIN ramo r
        ON r.id_ramo = e.id_ramo

    LEFT JOIN quadros_servico q
        ON q.id_quadro = e.id_quadro

    LEFT JOIN patentes p
        ON p.id_patente = e.id_patente

    LEFT JOIN sexos sx
        ON sx.id_sexo = dp.id_sexo

    LEFT JOIN paises pn
        ON pn.id_pais = dp.id_pais_nascimento

    LEFT JOIN paises pnat
        ON pnat.id_pais = dp.id_pais_nacionalidade

    LEFT JOIN estados_civis ec
        ON ec.id_estado_civil = dp.id_estado_civil

    LEFT JOIN dados_servico ds
        ON ds.id_especialista = e.id_especialista

    LEFT JOIN funcoes f
        ON f.id_funcao = ds.id_funcao

    LEFT JOIN unidades u
        ON u.id_unidade = ds.id_unidade

    LEFT JOIN departamentos dep
        ON dep.id_departamento = ds.id_departamento

    LEFT JOIN situacoes_servico ss
        ON ss.id_situacao_servico = ds.id_situacao_servico

    WHERE e.id_especialista = ?
");

$stmt->execute([$id]);

$especialista = $stmt->fetch();

if (!$especialista) {
    http_response_code(404);
    exit('Especialista não encontrado.');
}

/* ============================================================
   CONTROLO DE ACESSO
============================================================ */

$nivel = $_SESSION['user']['nivel'] ?? 'GLOBAL';

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
   PASTAS DE FICHEIROS
============================================================ */

$dirFotografias = __DIR__ . '/../../uploads/especialistas/fotografias';
$dirDocumentos  = __DIR__ . '/../../uploads/especialistas/documentos';

$urlFotografias = 'uploads/especialistas/fotografias';
$urlDocumentos  = 'uploads/especialistas/documentos';

if (!is_dir($dirFotografias)) {
    mkdir($dirFotografias, 0775, true);
}

if (!is_dir($dirDocumentos)) {
    mkdir($dirDocumentos, 0775, true);
}

/* ============================================================
   DOWNLOAD DE DOCUMENTO
============================================================ */

if (
    isset($_GET['acao']) &&
    $_GET['acao'] === 'download_documento'
) {

    $idDocumento = (int)($_GET['documento'] ?? 0);

    if (!$idDocumento) {
        http_response_code(400);
        exit('Documento inválido.');
    }

    $stmt = $pdo->prepare("
        SELECT
            nome_ficheiro,
            caminho_ficheiro
        FROM documentos
        WHERE id_documento = ?
          AND id_especialista = ?
    ");

    $stmt->execute([
        $idDocumento,
        $id
    ]);

    $documento = $stmt->fetch();

    if (!$documento) {
        http_response_code(404);
        exit('Documento não encontrado.');
    }

    $caminho = app_public_path($documento['caminho_ficheiro']);

    if (!is_file($caminho)) {
        http_response_code(404);
        exit('Ficheiro não encontrado no servidor.');
    }

    $nome = basename($documento['nome_ficheiro']);

    header('Content-Type: application/octet-stream');
    header(
        'Content-Disposition: attachment; filename="' .
        str_replace('"', '', $nome) .
        '"'
    );
    header('Content-Length: ' . filesize($caminho));
    header('X-Content-Type-Options: nosniff');

    readfile($caminho);
    exit;
}

/* ============================================================
   ABRIR / DOWNLOAD DE FOTOGRAFIA
============================================================ */

if (
    isset($_GET['acao']) &&
    $_GET['acao'] === 'download_fotografia'
) {

    $idFotografia = (int)($_GET['fotografia'] ?? 0);

    if (!$idFotografia) {
        http_response_code(400);
        exit('Fotografia inválida.');
    }

    $stmt = $pdo->prepare("
        SELECT
            nome_ficheiro,
            caminho_ficheiro
        FROM fotografias
        WHERE id_fotografia = ?
          AND id_especialista = ?
    ");

    $stmt->execute([
        $idFotografia,
        $id
    ]);

    $foto = $stmt->fetch();

    if (!$foto) {
        http_response_code(404);
        exit('Fotografia não encontrada.');
    }

    $caminho = app_public_path($foto['caminho_ficheiro']);

    if (!is_file($caminho)) {
        http_response_code(404);
        exit('Ficheiro não encontrado no servidor.');
    }

    $ext = strtolower(
        pathinfo($foto['nome_ficheiro'], PATHINFO_EXTENSION)
    );

    $tipos = [
        'jpg'  => 'image/jpeg',
        'jpeg' => 'image/jpeg',
        'png'  => 'image/png',
        'webp' => 'image/webp'
    ];

    $mime = $tipos[$ext] ?? 'application/octet-stream';

    header('Content-Type: ' . $mime);
    header(
        'Content-Disposition: inline; filename="' .
        str_replace('"', '', basename($foto['nome_ficheiro'])) .
        '"'
    );
    header('Content-Length: ' . filesize($caminho));
    header('X-Content-Type-Options: nosniff');

    readfile($caminho);
    exit;
}

/* ============================================================
   PROCESSAMENTO DE POST
============================================================ */

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $acao = $_POST['acao'] ?? '';

    /* ========================================================
       ADICIONAR FOTOGRAFIA
    ======================================================== */

    if ($acao === 'adicionar_fotografia') {

        if (
            !isset($_FILES['fotografia']) ||
            $_FILES['fotografia']['error'] !== UPLOAD_ERR_OK
        ) {
            redirecionar_especialista(
                $id,
                'ficheiro=erro_fotografia'
            );
        }

        $arquivo = $_FILES['fotografia'];

        if ($arquivo['size'] > 8 * 1024 * 1024) {
            redirecionar_especialista(
                $id,
                'ficheiro=fotografia_grande'
            );
        }

        $ext = strtolower(
            pathinfo($arquivo['name'], PATHINFO_EXTENSION)
        );

        $extPermitidas = [
            'jpg',
            'jpeg',
            'png',
            'webp'
        ];

        if (!in_array($ext, $extPermitidas, true)) {
            redirecionar_especialista(
                $id,
                'ficheiro=formato_fotografia'
            );
        }

        $nomeOriginal = basename($arquivo['name']);

        $nomeSeguro =
            bin2hex(random_bytes(12)) .
            '.' .
            $ext;

        $destinoFisico =
            $dirFotografias .
            DIRECTORY_SEPARATOR .
            $nomeSeguro;

        $caminhoBD =
            $urlFotografias .
            '/' .
            $nomeSeguro;

        if (!move_uploaded_file(
            $arquivo['tmp_name'],
            $destinoFisico
        )) {
            redirecionar_especialista(
                $id,
                'ficheiro=erro_upload_fotografia'
            );
        }

        $descricao = trim(
            $_POST['descricao_fotografia'] ?? ''
        );

        $principal = 1;

        try {

            $pdo->beginTransaction();

            if ($principal) {

                $stmt = $pdo->prepare("
                    UPDATE fotografias
                    SET principal = 0
                    WHERE id_especialista = ?
                ");

                $stmt->execute([$id]);
            }

            $stmt = $pdo->prepare("
                INSERT INTO fotografias (
                    id_especialista,
                    id_equipamento,
                    nome_ficheiro,
                    caminho_ficheiro,
                    principal,
                    descricao,
                    data_registo
                )
                VALUES (
                    ?,
                    NULL,
                    ?,
                    ?,
                    ?,
                    ?,
                    NOW()
                )
            ");

            $stmt->execute([
                $id,
                $nomeOriginal,
                $caminhoBD,
                $principal,
                $descricao ?: null
            ]);

            $pdo->commit();

            redirecionar_especialista(
                $id,
                'fotografia=adicionada'
            );

        } catch (Throwable $e) {

            if ($pdo->inTransaction()) {
                $pdo->rollBack();
            }

            if (is_file($destinoFisico)) {
                unlink($destinoFisico);
            }

            redirecionar_especialista(
                $id,
                'fotografia=erro'
            );
        }
    }

    /* ========================================================
       DEFINIR FOTOGRAFIA PRINCIPAL
    ======================================================== */

    if ($acao === 'definir_fotografia_principal') {

        $idFotografia =
            (int)($_POST['id_fotografia'] ?? 0);

        if ($idFotografia <= 0) {
            redirecionar_especialista(
                $id,
                'fotografia=erro'
            );
        }

        try {

            $pdo->beginTransaction();

            $stmt = $pdo->prepare("
                SELECT id_fotografia
                FROM fotografias
                WHERE id_fotografia = ?
                  AND id_especialista = ?
            ");

            $stmt->execute([
                $idFotografia,
                $id
            ]);

            if (!$stmt->fetchColumn()) {

                $pdo->rollBack();

                redirecionar_especialista(
                    $id,
                    'fotografia=nao_encontrada'
                );
            }

            $stmt = $pdo->prepare("
                UPDATE fotografias
                SET principal = 0
                WHERE id_especialista = ?
            ");

            $stmt->execute([$id]);

            $stmt = $pdo->prepare("
                UPDATE fotografias
                SET principal = 1
                WHERE id_fotografia = ?
                  AND id_especialista = ?
            ");

            $stmt->execute([
                $idFotografia,
                $id
            ]);

            $pdo->commit();

            redirecionar_especialista(
                $id,
                'fotografia=principal'
            );

        } catch (Throwable $e) {

            if ($pdo->inTransaction()) {
                $pdo->rollBack();
            }

            redirecionar_especialista(
                $id,
                'fotografia=erro'
            );
        }
    }

    /* ========================================================
       ELIMINAR FOTOGRAFIA
    ======================================================== */

    if ($acao === 'eliminar_fotografia') {

        $idFotografia =
            (int)($_POST['id_fotografia'] ?? 0);

        $stmt = $pdo->prepare("
            SELECT
                caminho_ficheiro
            FROM fotografias
            WHERE id_fotografia = ?
              AND id_especialista = ?
        ");

        $stmt->execute([
            $idFotografia,
            $id
        ]);

        $foto = $stmt->fetch();

        if (!$foto) {
            redirecionar_especialista(
                $id,
                'fotografia=nao_encontrada'
            );
        }

        try {

            $stmt = $pdo->prepare("
                DELETE FROM fotografias
                WHERE id_fotografia = ?
                  AND id_especialista = ?
            ");

            $stmt->execute([
                $idFotografia,
                $id
            ]);

            $caminho = app_public_path($foto['caminho_ficheiro']);

            if (is_file($caminho)) {
                unlink($caminho);
            }

            redirecionar_especialista(
                $id,
                'fotografia=eliminada'
            );

        } catch (Throwable $e) {

            redirecionar_especialista(
                $id,
                'fotografia=erro_eliminar'
            );
        }
    }

    /* ========================================================
       ADICIONAR DOCUMENTO
    ======================================================== */

    if ($acao === 'adicionar_documento') {

        if (
            !isset($_FILES['documento']) ||
            $_FILES['documento']['error'] !== UPLOAD_ERR_OK
        ) {
            redirecionar_especialista(
                $id,
                'documento=erro'
            );
        }

        $arquivo = $_FILES['documento'];

        if ($arquivo['size'] > 15 * 1024 * 1024) {
            redirecionar_especialista(
                $id,
                'documento=grande'
            );
        }

        $ext = strtolower(
            pathinfo($arquivo['name'], PATHINFO_EXTENSION)
        );

        $extPermitidas = [
            'pdf',
            'doc',
            'docx',
            'xls',
            'xlsx',
            'jpg',
            'jpeg',
            'png'
        ];

        if (!in_array($ext, $extPermitidas, true)) {
            redirecionar_especialista(
                $id,
                'documento=formato'
            );
        }

        $nomeOriginal = basename($arquivo['name']);

        $nomeSeguro =
            bin2hex(random_bytes(12)) .
            '.' .
            $ext;

        $destinoFisico =
            $dirDocumentos .
            DIRECTORY_SEPARATOR .
            $nomeSeguro;

        $caminhoBD =
            $urlDocumentos .
            '/' .
            $nomeSeguro;

        if (!move_uploaded_file(
            $arquivo['tmp_name'],
            $destinoFisico
        )) {
            redirecionar_especialista(
                $id,
                'documento=erro_upload'
            );
        }

        $idTipoDocumento =
            (int)($_POST['id_tipo_documento'] ?? 0);

        $titulo = trim(
            $_POST['titulo'] ?? ''
        );

        $dataDocumento =
            $_POST['data_documento'] ?: null;

        $dataValidade =
            $_POST['data_validade'] ?: null;

        $descricao = trim(
            $_POST['descricao_documento'] ?? ''
        );

        if ($titulo === '') {
            $titulo = $nomeOriginal;
        }

        try {

            $stmt = $pdo->prepare("
                INSERT INTO documentos (
                    id_especialista,
                    id_equipamento,
                    id_tipo_documento,
                    titulo,
                    nome_ficheiro,
                    caminho_ficheiro,
                    data_documento,
                    data_validade,
                    descricao,
                    data_registo
                )
                VALUES (
                    ?,
                    NULL,
                    ?,
                    ?,
                    ?,
                    ?,
                    ?,
                    ?,
                    ?,
                    NOW()
                )
            ");

            $stmt->execute([
                $id,
                $idTipoDocumento > 0
                    ? $idTipoDocumento
                    : null,
                $titulo,
                $nomeOriginal,
                $caminhoBD,
                $dataDocumento,
                $dataValidade,
                $descricao ?: null
            ]);

            redirecionar_especialista(
                $id,
                'documento=adicionado'
            );

        } catch (Throwable $e) {

            if (is_file($destinoFisico)) {
                unlink($destinoFisico);
            }

            redirecionar_especialista(
                $id,
                'documento=erro'
            );
        }
    }

    /* ========================================================
       ELIMINAR DOCUMENTO
    ======================================================== */

    if ($acao === 'eliminar_documento') {

        $idDocumento =
            (int)($_POST['id_documento'] ?? 0);

        $stmt = $pdo->prepare("
            SELECT
                caminho_ficheiro
            FROM documentos
            WHERE id_documento = ?
              AND id_especialista = ?
        ");

        $stmt->execute([
            $idDocumento,
            $id
        ]);

        $documento = $stmt->fetch();

        if (!$documento) {
            redirecionar_especialista(
                $id,
                'documento=nao_encontrado'
            );
        }

        try {

            $stmt = $pdo->prepare("
                DELETE FROM documentos
                WHERE id_documento = ?
                  AND id_especialista = ?
            ");

            $stmt->execute([
                $idDocumento,
                $id
            ]);

            $caminho = app_public_path($documento['caminho_ficheiro']);

            if (is_file($caminho)) {
                unlink($caminho);
            }

            redirecionar_especialista(
                $id,
                'documento=eliminado'
            );

        } catch (Throwable $e) {

            redirecionar_especialista(
                $id,
                'documento=erro_eliminar'
            );
        }
    }
}

/* ============================================================
   FAMILIARES
============================================================ */

$stmt = $pdo->prepare("
    SELECT
        f.id_familiar,
        f.nome,
        f.data_nascimento,
        f.profissao,
        f.telefone,
        f.morada,
        f.observacoes,
        gp.nome AS parentesco_nome

    FROM familiares f

    LEFT JOIN graus_parentesco gp
        ON gp.id_grau_parentesco = f.id_grau_parentesco

    WHERE f.id_especialista = ?

    ORDER BY f.nome ASC
");

$stmt->execute([$id]);

$familiares = $stmt->fetchAll();

/* ============================================================
   FORMAÇÕES
============================================================ */

$stmt = $pdo->prepare("
    SELECT
        fo.id_formacao,
        fo.categoria,
        fo.designacao,
        fo.local,
        fo.data_inicio,
        fo.data_fim,
        fo.duracao,
        fo.certificado,
        fo.observacoes,

        tf.nome AS tipo_formacao_nome,
        i.nome AS instituicao_nome,
        p.nome AS pais_nome

    FROM formacoes fo

    LEFT JOIN tipos_formacao tf
        ON tf.id_tipo_formacao = fo.id_tipo_formacao

    LEFT JOIN instituicoes i
        ON i.id_instituicao = fo.id_instituicao

    LEFT JOIN paises p
        ON p.id_pais = fo.id_pais

    WHERE fo.id_especialista = ?

    ORDER BY
        fo.data_fim DESC,
        fo.data_inicio DESC,
        fo.designacao ASC
");

$stmt->execute([$id]);

$formacoes = $stmt->fetchAll();

/* ============================================================
   HABILITAÇÕES LITERÁRIAS
============================================================ */

$stmt = $pdo->prepare("
    SELECT
        h.id_habilitacao,
        h.categoria,
        h.curso,
        h.data_inicio,
        h.data_conclusao,
        h.numero_certificado,
        h.observacoes,

        nh.nome AS nivel_habilitacao_nome,

        i.nome AS instituicao_nome,

        p.nome AS pais_nome

    FROM habilitacoes_literarias h

    LEFT JOIN niveis_habilitacao nh
        ON nh.id_nivel_habilitacao = h.id_nivel_habilitacao

    LEFT JOIN instituicoes i
        ON i.id_instituicao = h.id_instituicao

    LEFT JOIN paises p
        ON p.id_pais = h.id_pais

    WHERE h.id_especialista = ?

    ORDER BY
        h.data_conclusao DESC,
        h.data_inicio DESC,
        h.curso ASC
");

$stmt->execute([$id]);

$habilitacoes = $stmt->fetchAll();

/* ============================================================
   CONDECORAÇÕES
============================================================ */

$stmt = $pdo->prepare("
    SELECT
        c.id_condecoracao,
        c.entidade,
        c.data_atribuicao,
        c.motivo,
        c.numero_documento,
        c.observacoes,

        tc.nome AS tipo_condecoracao_nome

    FROM condecoracoes c

    LEFT JOIN tipos_condecoracao tc
        ON tc.id_tipo_condecoracao = c.id_tipo_condecoracao

    WHERE c.id_especialista = ?

    ORDER BY c.data_atribuicao DESC
");

$stmt->execute([$id]);

$condecoracoes = $stmt->fetchAll();

/* ============================================================
   FÉRIAS
============================================================ */

$stmt = $pdo->prepare("
    SELECT
        id_ferias,
        ano,
        data_inicio,
        data_fim,
        numero_dias,
        estado,
        observacoes

    FROM ferias

    WHERE id_especialista = ?

    ORDER BY ano DESC, data_inicio DESC
");

$stmt->execute([$id]);

$ferias = $stmt->fetchAll();

/* ============================================================
   FOTOGRAFIAS
============================================================ */

$stmt = $pdo->prepare("
    SELECT
        id_fotografia,
        nome_ficheiro,
        caminho_ficheiro,
        data_registo,
        principal,
        descricao

    FROM fotografias

    WHERE id_especialista = ?

    ORDER BY
        principal DESC,
        data_registo DESC
");

$stmt->execute([$id]);

$fotografias = $stmt->fetchAll();

/* ============================================================
   DOCUMENTOS
============================================================ */

$stmt = $pdo->prepare("
    SELECT
        d.id_documento,
        d.titulo,
        d.nome_ficheiro,
        d.caminho_ficheiro,
        d.data_documento,
        d.data_validade,
        d.descricao,
        d.data_registo,

        td.nome AS tipo_documento_nome

    FROM documentos d

    LEFT JOIN tipos_documento td
        ON td.id_tipo_documento = d.id_tipo_documento

    WHERE d.id_especialista = ?

    ORDER BY d.data_registo DESC
");

$stmt->execute([$id]);

$documentos = $stmt->fetchAll();

/* ============================================================
   TIPOS DE DOCUMENTO
============================================================ */

$stmt = $pdo->query("
    SELECT
        id_tipo_documento,
        nome
    FROM tipos_documento
    WHERE estado = 'ATIVO'
    ORDER BY nome ASC
");

$tiposDocumentos = $stmt->fetchAll();

/* ============================================================
   MENSAGENS
============================================================ */

$mensagem = '';

$mensagens = [

    'criado' =>
        'Especialista cadastrado com sucesso.',

    'atualizado' =>
        'Dados do especialista atualizados com sucesso.',

    'fotografia=adicionada' =>
        'Fotografia adicionada com sucesso.',

    'fotografia=principal' =>
        'Fotografia principal definida com sucesso.',

    'fotografia=eliminada' =>
        'Fotografia eliminada com sucesso.',

    'fotografia=erro' =>
        'Não foi possível processar a fotografia.',

    'fotografia=erro_eliminar' =>
        'Não foi possível eliminar a fotografia.',

    'fotografia=nao_encontrada' =>
        'Fotografia não encontrada.',

    'ficheiro=erro_fotografia' =>
        'Selecione uma fotografia válida.',

    'ficheiro=fotografia_grande' =>
        'A fotografia não pode ultrapassar 8 MB.',

    'ficheiro=formato_fotografia' =>
        'Formato de fotografia não permitido. Use JPG, JPEG, PNG ou WEBP.',

    'ficheiro=erro_upload_fotografia' =>
        'Não foi possível guardar a fotografia.',

    'documento=adicionado' =>
        'Documento adicionado com sucesso.',

    'documento=eliminado' =>
        'Documento eliminado com sucesso.',

    'documento=erro' =>
        'Não foi possível adicionar o documento.',

    'documento=grande' =>
        'O documento não pode ultrapassar 15 MB.',

    'documento=formato' =>
        'Formato de documento não permitido.',

    'documento=erro_upload' =>
        'Não foi possível guardar o documento.',

    'documento=erro_eliminar' =>
        'Não foi possível eliminar o documento.',

    'documento=nao_encontrado' =>
        'Documento não encontrado.'
];

if (isset($_GET['familiar'])) {

    $mapa = [
        'adicionado' =>
            'Familiar adicionado com sucesso.',
        'atualizado' =>
            'Familiar atualizado com sucesso.',
        'eliminado' =>
            'Familiar eliminado com sucesso.'
    ];

    $mensagem =
        $mapa[$_GET['familiar']] ?? '';
}

if (isset($_GET['formacao'])) {

    $mapa = [
        'adicionada' =>
            'Formação adicionada com sucesso.',
        'atualizada' =>
            'Formação atualizada com sucesso.',
        'eliminada' =>
            'Formação eliminada com sucesso.'
    ];

    $mensagem =
        $mapa[$_GET['formacao']] ?? '';
}

if (isset($_GET['habilitacao'])) {

    $mapa = [
        'adicionada' =>
            'Habilitação literária adicionada com sucesso.',
        'atualizada' =>
            'Habilitação literária atualizada com sucesso.',
        'eliminada' =>
            'Habilitação literária eliminada com sucesso.'
    ];

    $mensagem =
        $mapa[$_GET['habilitacao']] ?? '';
}

if (isset($_GET['condecoracao'])) {

    $mapa = [
        'adicionada' =>
            'Condecoração adicionada com sucesso.',
        'atualizada' =>
            'Condecoração atualizada com sucesso.',
        'eliminada' =>
            'Condecoração eliminada com sucesso.',
        'erro_eliminar' =>
            'Não foi possível eliminar a condecoração.'
    ];

    $mensagem =
        $mapa[$_GET['condecoracao']] ?? '';
}

foreach ($mensagens as $chave => $texto) {

    $parts = explode('=', $chave, 2);

    if (count($parts) === 1) {

        if (isset($_GET[$parts[0]])) {
            $mensagem = $texto;
        }

    } else {

        if (
            isset($_GET[$parts[0]]) &&
            $_GET[$parts[0]] === $parts[1]
        ) {
            $mensagem = $texto;
        }
    }
}
$page_title = "Especialista";
require __DIR__ . '/../../layout/header.php';
?>

<div class="module-page module-especialista">
<main class="page">

<?php if ($mensagem): ?>

<div class="alert-success">
<?= e($mensagem) ?>
</div>

<?php endif; ?>

<div class="profile-fixed-area">

<!-- ========================================================
     CABEÇALHO
========================================================= -->

<section class="profile-header">

<div class="profile-actions" aria-label="Ações da ficha">
    <a href="especialista_editar.php?id=<?= $id ?>" class="btn btn-primary btn-compact">✎ Editar</a>
    <div class="print-menu" id="printMenu">
        <button type="button" class="btn btn-secondary btn-compact" onclick="togglePrintMenu()">🖨 Imprimir ▾</button>
        <div class="print-menu-panel" id="printMenuPanel" role="menu" aria-label="Opções de impressão">
<div class="print-menu-title">Fichas principais</div>
            <a href="especialista.php?id=<?= $id ?>&imprimir=completa" target="_blank">📄 <strong>Ficha completa</strong><br><small>Resumo do militar + todos os blocos de dados, sem Fotografias e Documentos.</small></a>
            <a href="especialista.php?id=<?= $id ?>&imprimir=resumida" target="_blank">📋 <strong>Ficha resumida</strong><br><small>Apenas o resumo principal do militar.</small></a>
            <div class="print-menu-sep"></div>
            <div class="print-menu-title">Ficha por blocos</div>
            <a href="especialista.php?id=<?= $id ?>&imprimir=secao&secao=pessoais" target="_blank">👤 Dados Pessoais</a>
            <a href="especialista.php?id=<?= $id ?>&imprimir=secao&secao=servico" target="_blank">🪖 Dados de Serviço</a>
            <a href="especialista.php?id=<?= $id ?>&imprimir=secao&secao=familiares" target="_blank">👪 Familiares</a>
            <a href="especialista.php?id=<?= $id ?>&imprimir=secao&secao=formacoes" target="_blank">🎓 Formações</a>
            <a href="especialista.php?id=<?= $id ?>&imprimir=secao&secao=habilitacoes" target="_blank">📚 Habilitações Literárias</a>
            <a href="especialista.php?id=<?= $id ?>&imprimir=secao&secao=condecoracoes" target="_blank">🏅 Condecorações</a>
            <a href="especialista.php?id=<?= $id ?>&imprimir=secao&secao=ferias" target="_blank">🏖 Férias</a>
            <div class="print-menu-sep"></div>
            <div class="print-menu-note">Fotografias e Documentos não fazem parte das opções de impressão desta ficha.</div>
        </div>
    </div>
    <button type="button" class="btn btn-primary btn-compact" onclick="abrirSolicitacao()">▣ Solicitar</button>
    <a href="especialistas.php" class="btn btn-secondary btn-compact">← Voltar</a>
</div>

<div class="profile-main">

<div class="profile-photo" tabindex="0">

<?php
$fotoPrincipal = null;
foreach ($fotografias as $foto) {
    if ((int)$foto['principal'] === 1) {
        $fotoPrincipal = $foto;
        break;
    }
}
?>

<?php if ($fotoPrincipal): ?>
<img src="<?= e(app_file_url($fotoPrincipal['caminho_ficheiro'])) ?>" alt="Fotografia do especialista">
<?php else: ?>
<div class="profile-placeholder">👤</div>
<?php endif; ?>

<div class="profile-photo-controls" aria-label="Ações da fotografia">
    <form method="post" enctype="multipart/form-data" class="inline-form photo-upload-form" title="Escolher fotografia">
        <input type="hidden" name="acao" value="adicionar_fotografia">
        <input type="file" name="fotografia" accept=".jpg,.jpeg,.png,.webp,image/jpeg,image/png,image/webp" required style="display:none" onchange="this.form.submit()">
        <button type="button" class="photo-hover-btn" onclick="this.previousElementSibling.click()" aria-label="Escolher fotografia" title="Escolher fotografia">🖼</button>
    </form>
    <form method="post" enctype="multipart/form-data" class="inline-form camera-upload-form" title="Tirar fotografia">
        <input type="hidden" name="acao" value="adicionar_fotografia">
        <input type="file" name="fotografia" class="camera-file-input" accept="image/*" capture="environment" required style="display:none" onchange="this.form.submit()">
        <button type="button" class="photo-hover-btn" onclick="this.previousElementSibling.click()" aria-label="Tirar fotografia" title="Tirar fotografia">📷</button>
    </form>
    <?php if ($fotoPrincipal): ?>
    <a class="photo-hover-btn" title="Abrir / descarregar fotografia" target="_blank" href="especialista.php?id=<?= $id ?>&acao=download_fotografia&fotografia=<?= (int)$fotoPrincipal['id_fotografia'] ?>">↓</a>
    <a class="photo-hover-btn" title="Substituir fotografia" href="#fotografias" onclick="showSection('fotografias');setTimeout(function(){document.getElementById('fotografia').click();},50);return false;">↔</a>
    <?php endif; ?>
</div>

</div>

<div class="profile-info">
<h1 class="profile-name"><?= e($especialista['nome_completo']) ?></h1>

<div class="profile-summary-grid">
    <div class="profile-summary-item"><span class="summary-label">NIP</span><span class="summary-value"><?= e($especialista['nip'] ?: '—') ?></span></div>
    <div class="profile-summary-item"><span class="summary-label">Patente</span><span class="summary-value"><?= sigla_html($especialista['patente_nome'], $especialista['patente_sigla']) ?></span></div>
    <div class="profile-summary-item"><span class="summary-label">Ramo</span><span class="summary-value"><?= sigla_html($especialista['ramo_nome'], $especialista['ramo_sigla']) ?></span></div>
    <div class="profile-summary-item"><span class="summary-label">Unidade</span><span class="summary-value"><?= sigla_html($especialista['unidade_nome'], $especialista['unidade_sigla']) ?></span></div>
    <div class="profile-summary-item"><span class="summary-label">Quadro</span><span class="summary-value"><?= sigla_html($especialista['quadro_nome'], $especialista['quadro_sigla']) ?></span></div>
    <div class="profile-summary-item"><span class="summary-label">Situação</span><span class="summary-value"><?= e($especialista['situacao_servico_nome'] ?: '—') ?></span></div>
    <div class="profile-summary-item"><span class="summary-label">Estado</span><span class="summary-value"><?= e($especialista['estado_especialista'] ?: '—') ?></span></div>
    <div class="profile-summary-item"><span class="summary-label">N.º Ordem</span><span class="summary-value"><?= e($especialista['numero_ordem'] ?: '—') ?></span></div>
</div>
</div>
</div>



</section>

<!-- ========================================================
     NAVEGAÇÃO
========================================================= -->

<div class="tabs-placeholder" aria-hidden="true"></div>

<nav class="tabs" id="especialistaTabs" aria-label="Secções do especialista">
<a href="#pessoais" data-section="pessoais" class="active">Dados Pessoais</a>
<a href="#servico" data-section="servico">Serviço</a>
<a href="#familiares" data-section="familiares">Familiares</a>
<a href="#formacoes" data-section="formacoes">Formações</a>
<a href="#habilitacoes" data-section="habilitacoes">Habilitações</a>
<a href="#condecoracoes" data-section="condecoracoes">Condecorações</a>
<a href="#ferias" data-section="ferias">Férias</a>
<a href="#fotografias" data-section="fotografias">Fotografias</a>
<a href="#documentos" data-section="documentos">Documentos</a>
</nav>

</div><!-- /.profile-fixed-area -->

<section class="section section-panel" id="pessoais">

<div class="section-header"><div style="display:flex;align-items:center;gap:8px;flex-wrap:wrap;flex:1;">
<h2>Dados Pessoais</h2>
</div><div class="section-header-actions"><a href="especialista_editar.php?id=<?= $id ?>" class="btn btn-secondary btn-compact">✎ Editar</a></div></div>

<div class="info-grid">

<div class="info-item">
<span class="info-label">Nome Completo</span>
<div class="info-value">
<?= e($especialista['nome_completo']) ?>
</div>
</div>

<div class="info-item">
<span class="info-label">Data de Nascimento</span>
<div class="info-value">
<?= data_pt($especialista['data_nascimento']) ?>
</div>
</div>

<div class="info-item">
<span class="info-label">Sexo</span>
<div class="info-value">
<?= e($especialista['sexo_nome'] ?: '—') ?>
</div>
</div>

<div class="info-item">
<span class="info-label">Estado Civil</span>
<div class="info-value">
<?= e($especialista['estado_civil_nome'] ?: '—') ?>
</div>
</div>

<div class="info-item">
<span class="info-label">Nacionalidade</span>
<div class="info-value">
<?= e($especialista['nacionalidade_nome'] ?: '—') ?>
</div>
</div>

<div class="info-item">
<span class="info-label">País de Nascimento</span>
<div class="info-value">
<?= e($especialista['pais_nascimento_nome'] ?: '—') ?>
</div>
</div>

<div class="info-item">
<span class="info-label">Número do BI</span>
<div class="info-value">
<?= e($especialista['numero_bi'] ?: '—') ?>
</div>
</div>

<div class="info-item">
<span class="info-label">Emissão do BI</span>
<div class="info-value">
<?= data_pt($especialista['data_emissao_bi']) ?>
</div>
</div>

<div class="info-item">
<span class="info-label">Validade do BI</span>
<div class="info-value">
<?= data_pt($especialista['data_validade_bi']) ?>
</div>
</div>

<div class="info-item">
<span class="info-label">Local de Emissão</span>
<div class="info-value">
<?= e($especialista['local_emissao_bi'] ?: '—') ?>
</div>
</div>

<div class="info-item">
<span class="info-label">Telefone</span>
<div class="info-value">
<?= e($especialista['telefone'] ?: '—') ?>
</div>
</div>

<div class="info-item">
<span class="info-label">E-mail</span>
<div class="info-value">
<?= e($especialista['email'] ?: '—') ?>
</div>
</div>

<div class="info-item">
<span class="info-label">Morada</span>
<div class="info-value">
<?= e($especialista['morada'] ?: '—') ?>
</div>
</div>

</div>

</section>

<!-- ========================================================
     SERVIÇO
========================================================= -->

<section class="section section-panel" id="servico">

<div class="section-header"><div style="display:flex;align-items:center;gap:8px;flex-wrap:wrap;flex:1;">
<h2>Dados de Serviço</h2>
</div><div class="section-header-actions"><a href="especialista_editar.php?id=<?= $id ?>" class="btn btn-secondary btn-compact">✎ Editar</a></div></div>

<div class="info-grid">

<div class="info-item">
<span class="info-label">NIP</span>
<div class="info-value">
<?= e($especialista['nip'] ?: '—') ?>
</div>
</div>

<div class="info-item">
<span class="info-label">Número de Ordem</span>
<div class="info-value">
<?= e($especialista['numero_ordem'] ?: '—') ?>
</div>
</div>

<div class="info-item">
<span class="info-label">Número de Processo</span>
<div class="info-value">
<?= e($especialista['numero_processo'] ?: '—') ?>
</div>
</div>

<div class="info-item">
<span class="info-label">Data de Ingresso</span>
<div class="info-value">
<?= data_pt($especialista['data_ingresso']) ?>
</div>
</div>

<div class="info-item">
<span class="info-label">Data de Incorporação</span>
<div class="info-value">
<?= data_pt($especialista['data_incorporacao']) ?>
</div>
</div>

<div class="info-item">
<span class="info-label">Data de Promoção</span>
<div class="info-value">
<?= data_pt($especialista['data_promocao']) ?>
</div>
</div>

<div class="info-item">
<span class="info-label">Função</span>
<div class="info-value">
<?= e($especialista['funcao_nome'] ?: '—') ?>
</div>
</div>

<div class="info-item">
<span class="info-label">Cargo</span>
<div class="info-value">
<?= e($especialista['cargo'] ?: '—') ?>
</div>
</div>

<div class="info-item">
<span class="info-label">Unidade</span>
<div class="info-value">
<?= e($especialista['unidade_nome'] ?: '—') ?>
</div>
</div>

<div class="info-item">
<span class="info-label">Departamento</span>
<div class="info-value">
<?= e($especialista['departamento_nome'] ?: '—') ?>
</div>
</div>

<div class="info-item">
<span class="info-label">Situação de Serviço</span>
<div class="info-value">
<?= e($especialista['situacao_servico_nome'] ?: '—') ?>
</div>
</div>

<div class="info-item">
<span class="info-label">Início da Função</span>
<div class="info-value">
<?= data_pt($especialista['data_inicio_funcao']) ?>
</div>
</div>

<div class="info-item">
<span class="info-label">Fim da Função</span>
<div class="info-value">
<?= data_pt($especialista['data_fim_funcao']) ?>
</div>
</div>

</div>

<?php if ($especialista['observacoes_servico']): ?>

<div style="margin-top:20px">

<strong>Observações de Serviço</strong>

<p>
<?= nl2br(e($especialista['observacoes_servico'])) ?>
</p>

</div>

<?php endif; ?>

</section>

<!-- ========================================================
     FAMILIARES
========================================================= -->

<section class="section section-panel" id="familiares">

<div class="section-header"><div style="display:flex;align-items:center;gap:8px;flex-wrap:wrap;flex:1;">

<h2>Familiares</h2>

<a
href="familiar_novo.php?id_especialista=<?= $id ?>"
class="btn btn-primary"

>

* Novo Familiar

  </a>

</div><div class="section-header-actions"><button type="button" class="btn btn-danger btn-compact" onclick="eliminarRegistoSelecionado('familiares')">⌫ Eliminar</button></div></div>

<?php if (!$familiares): ?>

<div class="empty">
Nenhum familiar cadastrado.
</div>

<?php else: ?>

<div class="table-wrap">

<table>

<thead>

<tr>
<th>Nome</th>
<th>Parentesco</th>
<th>Data de Nascimento</th>
<th>Profissão</th>
<th>Telefone</th>
<th>Ações</th>
</tr>

</thead>

<tbody>

<?php foreach ($familiares as $f): ?>

<tr>

<td><?= e($f['nome']) ?></td>

<td><?= e($f['parentesco_nome'] ?: '—') ?></td>

<td><?= data_pt($f['data_nascimento']) ?></td>

<td><?= e($f['profissao'] ?: '—') ?></td>

<td><?= e($f['telefone'] ?: '—') ?></td>

<td>

<a
href="familiar_editar.php?id=<?= (int)$f['id_familiar'] ?>"
class="btn btn-small btn-secondary"

>

Editar </a>

<a
href="familiar_eliminar.php?id=<?= (int)$f['id_familiar'] ?>"
class="btn btn-small btn-danger"

>

Eliminar </a>

</td>

</tr>

<?php endforeach; ?>

</tbody>

</table>

</div>

<?php endif; ?>

</section>

<!-- ========================================================
     FORMAÇÕES
========================================================= -->

<section class="section section-panel" id="formacoes">

<div class="section-header"><div style="display:flex;align-items:center;gap:8px;flex-wrap:wrap;flex:1;">

<h2>Formações</h2>

<a
href="formacao_nova.php?id_especialista=<?= $id ?>"
class="btn btn-primary"

>

* Nova Formação

  </a>

</div><div class="section-header-actions"><button type="button" class="btn btn-danger btn-compact" onclick="eliminarRegistoSelecionado('formacoes')">⌫ Eliminar</button></div></div>

<?php if (!$formacoes): ?>

<div class="empty">
Nenhuma formação cadastrada.
</div>

<?php else: ?>

<div class="table-wrap">

<table>

<thead>

<tr>
<th>Categoria</th>
<th>Designação</th>
<th>Tipo</th>
<th>Instituição</th>
<th>País</th>
<th>Início</th>
<th>Fim</th>
<th>Certificado</th>
<th>Ações</th>
</tr>

</thead>

<tbody>

<?php foreach ($formacoes as $f): ?>

<tr>

<td>

<span class="badge <?= $f['categoria'] === 'MILITAR'
 ? 'badge-militar'
 : 'badge-geral' ?>">

<?= e($f['categoria']) ?>

</span>

</td>

<td><?= e($f['designacao']) ?></td>

<td><?= e($f['tipo_formacao_nome'] ?: '—') ?></td>

<td><?= e($f['instituicao_nome'] ?: '—') ?></td>

<td><?= e($f['pais_nome'] ?: '—') ?></td>

<td><?= data_pt($f['data_inicio']) ?></td>

<td><?= data_pt($f['data_fim']) ?></td>

<td><?= e($f['certificado'] ?: '—') ?></td>

<td>

<a
href="formacao_editar.php?id=<?= (int)$f['id_formacao'] ?>"
class="btn btn-small btn-secondary"

>

Editar </a>

<a
href="formacao_eliminar.php?id=<?= (int)$f['id_formacao'] ?>"
class="btn btn-small btn-danger"

>

Eliminar </a>

</td>

</tr>

<?php endforeach; ?>

</tbody>

</table>

</div>

<?php endif; ?>

</section>

<!-- ========================================================
     HABILITAÇÕES
========================================================= -->

<section class="section section-panel" id="habilitacoes">

<div class="section-header"><div style="display:flex;align-items:center;gap:8px;flex-wrap:wrap;flex:1;">

<h2>Habilitações Literárias</h2>

<a
href="habilitacao_nova.php?id_especialista=<?= $id ?>"
class="btn btn-primary"

>

* Nova Habilitação

  </a>

</div><div class="section-header-actions"><button type="button" class="btn btn-danger btn-compact" onclick="eliminarRegistoSelecionado('habilitacoes')">⌫ Eliminar</button></div></div>

<?php if (!$habilitacoes): ?>

<div class="empty">
Nenhuma habilitação literária cadastrada.
</div>

<?php else: ?>

<div class="table-wrap">

<table>

<thead>

<tr>
<th>Categoria</th>
<th>Nível</th>
<th>Curso</th>
<th>Instituição</th>
<th>País</th>
<th>Início</th>
<th>Conclusão</th>
<th>Certificado</th>
<th>Ações</th>
</tr>

</thead>

<tbody>

<?php foreach ($habilitacoes as $h): ?>

<tr>

<td>

<span class="badge <?= $h['categoria'] === 'MILITAR'
 ? 'badge-militar'
 : 'badge-geral' ?>">

<?= e($h['categoria']) ?>

</span>

</td>

<td><?= e($h['nivel_habilitacao_nome'] ?: '—') ?></td>

<td><?= e($h['curso'] ?: '—') ?></td>

<td><?= e($h['instituicao_nome'] ?: '—') ?></td>

<td><?= e($h['pais_nome'] ?: '—') ?></td>

<td><?= data_pt($h['data_inicio']) ?></td>

<td><?= data_pt($h['data_conclusao']) ?></td>

<td><?= e($h['numero_certificado'] ?: '—') ?></td>

<td>

<a
href="habilitacao_editar.php?id=<?= (int)$h['id_habilitacao'] ?>"
class="btn btn-small btn-secondary"

>

Editar </a>

<a
href="habilitacao_eliminar.php?id=<?= (int)$h['id_habilitacao'] ?>"
class="btn btn-small btn-danger"
onclick="return confirm('Deseja eliminar esta habilitação literária?');"

>

Eliminar </a>

</td>

</tr>

<?php endforeach; ?>

</tbody>

</table>

</div>

<?php endif; ?>

</section>

<!-- ========================================================
     CONDECORAÇÕES
========================================================= -->

<section class="section section-panel" id="condecoracoes">

<div class="section-header"><div style="display:flex;align-items:center;gap:8px;flex-wrap:wrap;flex:1;">

<h2>Condecorações</h2>

<a
href="condecoracao_nova.php?id_especialista=<?= $id ?>"
class="btn btn-primary"

>

* Nova Condecoração

  </a>

</div><div class="section-header-actions"><button type="button" class="btn btn-danger btn-compact" onclick="eliminarRegistoSelecionado('condecoracoes')">⌫ Eliminar</button></div></div>

<?php if (!$condecoracoes): ?>

<div class="empty">
Nenhuma condecoração cadastrada.
</div>

<?php else: ?>

<div class="table-wrap">

<table>

<thead>

<tr>
<th>Tipo</th>
<th>Entidade</th>
<th>Data</th>
<th>N.º Documento</th>
<th>Motivo</th>
<th>Ações</th>
</tr>

</thead>

<tbody>

<?php foreach ($condecoracoes as $c): ?>

<tr>

<td>
<?= e($c['tipo_condecoracao_nome'] ?: '—') ?>
</td>

<td>
<?= e($c['entidade'] ?: '—') ?>
</td>

<td>
<?= data_pt($c['data_atribuicao']) ?>
</td>

<td>
<?= e($c['numero_documento'] ?: '—') ?>
</td>

<td>
<?= e($c['motivo'] ?: '—') ?>
</td>

<td>

<a
href="condecoracao_edicoes.php?id=<?= (int)$c['id_condecoracao'] ?>"
class="btn btn-small btn-warning"

>

Editar </a>

<a
href="condecoracao_edicoes.php?id=<?= (int)$c['id_condecoracao'] ?>"
class="btn btn-small btn-danger"
onclick="return confirm('Tem certeza que deseja eliminar esta condecoração? Esta operação não poderá ser desfeita.');"

>

Eliminar </a>

</td>

</tr>

<?php endforeach; ?>

</tbody>

</table>

</div>

<?php endif; ?>

</section>

<!-- ========================================================
     FÉRIAS
========================================================= -->

<section class="section section-panel" id="ferias">

<div class="section-header"><div style="display:flex;align-items:center;gap:8px;flex-wrap:wrap;flex:1;">

<h2>Férias</h2>

<a href="ferias_nova.php?id_especialista=<?= $id ?>" class="btn btn-primary btn-compact">+ Novo Período</a>

<button type="button" class="btn btn-secondary btn-compact" onclick="abrirSolicitacao('Guia de Férias')">▣ Novo Pedido</button>

</div><div class="section-header-actions"><button type="button" class="btn btn-danger btn-compact" onclick="eliminarRegistoSelecionado('ferias')">⌫ Eliminar</button></div></div>

<?php if (!$ferias): ?>

<div class="empty">
Nenhum período de férias cadastrado.
</div>

<?php else: ?>

<div class="table-wrap">

<table>

<thead>

<tr>
<th>Ano</th>
<th>Início</th>
<th>Fim</th>
<th>Dias</th>
<th>Estado</th>
<th>Ações</th>
</tr>

</thead>

<tbody>

<?php foreach ($ferias as $feria): ?>

<tr>

<td><?= e($feria['ano']) ?></td>

<td><?= data_pt($feria['data_inicio']) ?></td>

<td><?= data_pt($feria['data_fim']) ?></td>

<td><?= (int)$feria['numero_dias'] ?></td>

<td><?= e($feria['estado']) ?></td>

<td>

<a
href="ferias_editar.php?id=<?= (int)$feria['id_ferias'] ?>"
class="btn btn-small btn-secondary"

>

Editar </a>

<a
href="ferias_eliminar.php?id=<?= (int)$feria['id_ferias'] ?>"
class="btn btn-small btn-danger"

>

Eliminar </a>

</td>

</tr>

<?php endforeach; ?>

</tbody>

</table>

</div>

<?php endif; ?>

</section>

<!-- ========================================================
     FOTOGRAFIAS
========================================================= -->

<section class="section section-panel" id="fotografias">

<div class="section-header"><div style="display:flex;align-items:center;gap:8px;flex-wrap:wrap;flex:1;">

<h2>Fotografias</h2>

</div><div class="section-header-actions"></div></div>

<!-- FORMULÁRIO DE FOTOGRAFIA -->

<div class="form-box">

<h3 style="margin-top:0;">
Adicionar Fotografia
</h3>

<form
    method="post"
    enctype="multipart/form-data"
>

<input
type="hidden"
name="acao"
value="adicionar_fotografia"

>

<div class="form-grid">

<div class="form-group">

<label for="fotografia">
Fotografia
</label>

<input
type="file"
id="fotografia"
name="fotografia"
accept=".jpg,.jpeg,.png,.webp,image/jpeg,image/png,image/webp"
required

>

<span class="file-help">
Formatos: JPG, JPEG, PNG ou WEBP. Máximo: 8 MB.
</span>

</div>

<div class="form-group">

<label for="descricao_fotografia">
Descrição
</label>

<input
type="text"
id="descricao_fotografia"
name="descricao_fotografia"
maxlength="255"
placeholder="Ex.: Fotografia de identificação"

>

</div>

</div>

<div style="margin-top:12px;">

<label>

<input
type="checkbox"
name="principal"
value="1"

>

Definir esta fotografia como principal

</label>

</div>

<div class="form-actions">

<button
type="submit"
class="btn btn-primary"

>

Adicionar Fotografia </button>

</div>

</form>

</div>

<?php if (!$fotografias): ?>

<div class="empty">
Nenhuma fotografia cadastrada.
</div>

<?php else: ?>

<div class="photo-grid">

<?php foreach ($fotografias as $foto): ?>

<div class="photo-card">

<a
href="especialista.php?id=<?= $id ?>&acao=download_fotografia&fotografia=<?= (int)$foto['id_fotografia'] ?>"
target="_blank"

>

<img
src="<?= e(app_file_url($foto['caminho_ficheiro'])) ?>"
alt="<?= e($foto['descricao'] ?: $foto['nome_ficheiro']) ?>"

>

</a>

<div class="photo-info">

<?php if ((int)$foto['principal'] === 1): ?>

<br>

<span class="badge badge-ativo">
Principal
</span>

<?php endif; ?>

<?php if ($foto['descricao']): ?>

<p>
<?= e($foto['descricao']) ?>
</p>

<?php endif; ?>

<div class="document-actions">

<?php if ((int)$foto['principal'] !== 1): ?>

<form
    method="post"
    class="inline-form"
>

<input
type="hidden"
name="acao"
value="definir_fotografia_principal"

>

<input
type="hidden"
name="id_fotografia"
value="<?= (int)$foto['id_fotografia'] ?>"

>

<button
type="submit"
class="btn btn-small btn-warning"

>

Principal </button>

</form>

<?php endif; ?>

<a
href="especialista.php?id=<?= $id ?>&acao=download_fotografia&fotografia=<?= (int)$foto['id_fotografia'] ?>"
target="_blank"
class="btn btn-small btn-secondary"

>

Abrir </a>

<form
    method="post"
    class="inline-form"
    onsubmit="return confirm('Deseja eliminar esta fotografia?');"
>

<input
type="hidden"
name="acao"
value="eliminar_fotografia"

>

<input
type="hidden"
name="id_fotografia"
value="<?= (int)$foto['id_fotografia'] ?>"

>

<button
type="submit"
class="btn btn-small btn-danger"

>

Eliminar </button>

</form>

</div>

</div>

</div>

<?php endforeach; ?>

</div>

<?php endif; ?>

</section>

<!-- ========================================================
     DOCUMENTOS
========================================================= -->

<section class="section section-panel" id="documentos">

<div class="section-header"><div style="display:flex;align-items:center;gap:8px;flex-wrap:wrap;flex:1;">

<h2>Documentos</h2>

</div><div class="section-header-actions"></div></div>

<!-- FORMULÁRIO DE DOCUMENTO -->

<div class="form-box">

<h3 style="margin-top:0;">
Adicionar Documento
</h3>

<form
    method="post"
    enctype="multipart/form-data"
>

<input
type="hidden"
name="acao"
value="adicionar_documento"

>

<div class="form-grid">

<div class="form-group">

<label for="documento">
Ficheiro
</label>

<input
type="file"
id="documento"
name="documento"
accept=".pdf,.doc,.docx,.xls,.xlsx,.jpg,.jpeg,.png"
required

>

<span class="file-help">
PDF, DOC, DOCX, XLS, XLSX, JPG, JPEG ou PNG.
Máximo: 15 MB.
</span>

</div>

<div class="form-group">

<label for="id_tipo_documento">
Tipo de Documento
</label>

<select
id="id_tipo_documento"
name="id_tipo_documento"

>

<option value="">
-- Selecionar tipo --
</option>

<?php foreach ($tiposDocumentos as $tipo): ?>

<option
    value="<?= (int)$tipo['id_tipo_documento'] ?>"
>
<?= e($tipo['nome']) ?>
</option>

<?php endforeach; ?>

</select>

</div>

<div class="form-group">

<label for="titulo">
Título
</label>

<input
type="text"
id="titulo"
name="titulo"
maxlength="255"
placeholder="Título do documento"

>

</div>

<div class="form-group">

<label for="data_documento">
Data do Documento
</label>

<input
type="date"
id="data_documento"
name="data_documento"

>

</div>

<div class="form-group">

<label for="data_validade">
Data de Validade
</label>

<input
type="date"
id="data_validade"
name="data_validade"

>

</div>

<div class="form-group full">

<label for="descricao_documento">
Descrição / Observações
</label>

<textarea
    id="descricao_documento"
    name="descricao_documento"
    placeholder="Descrição ou observações do documento"
></textarea>

</div>

</div>

<div class="form-actions">

<button
type="submit"
class="btn btn-primary"

>

Adicionar Documento </button>

</div>

</form>

</div>

<?php if (!$documentos): ?>

<div class="empty">
Nenhum documento cadastrado.
</div>

<?php else: ?>

<div class="table-wrap">

<table>

<thead>

<tr>

<th>Tipo</th>
<th>Título</th>
<th>Data</th>
<th>Validade</th>
<th>Ficheiro</th>
<th>Ações</th>

</tr>

</thead>

<tbody>

<?php foreach ($documentos as $doc): ?>

<tr>

<td>
<?= e($doc['tipo_documento_nome'] ?: '—') ?>
</td>

<td>

<strong>
<?= e($doc['titulo']) ?>
</strong>

<?php if ($doc['descricao']): ?>

<br>

<small>
<?= e($doc['descricao']) ?>
</small>

<?php endif; ?>

</td>

<td>
<?= data_pt($doc['data_documento']) ?>
</td>

<td>
<?= data_pt($doc['data_validade']) ?>
</td>

<td>

<?= e($doc['nome_ficheiro']) ?>

</td>

<td>

<div class="document-actions">

<a
href="especialista.php?id=<?= $id ?>&acao=download_documento&documento=<?= (int)$doc['id_documento'] ?>"
class="btn btn-small btn-primary"

>

Baixar </a>

<a
href="documento_editar.php?id=<?= (int)$doc['id_documento'] ?>"
class="btn btn-small btn-secondary"

>

Editar </a>

<form
    method="post"
    class="inline-form"
    onsubmit="return confirm('Deseja eliminar este documento? Esta operação não poderá ser desfeita.');"
>

<input
type="hidden"
name="acao"
value="eliminar_documento"

>

<input
type="hidden"
name="id_documento"
value="<?= (int)$doc['id_documento'] ?>"

>

<button
type="submit"
class="btn btn-small btn-danger"

>

Eliminar </button>

</form>

</div>

</td>

</tr>

<?php endforeach; ?>

</tbody>

</table>

</div>

<?php endif; ?>

</section>

<!-- ========================================================
     OBSERVAÇÕES GERAIS
========================================================= -->

<section class="section section-panel" id="observacoes-gerais">

<div class="section-header">

<h2>Observações Gerais</h2>

</div>

<?php if ($especialista['observacoes_especialista']): ?>

<p>
<?= nl2br(e($especialista['observacoes_especialista'])) ?>
</p>

<?php else: ?>

<div class="empty">
Não existem observações gerais.
</div>

<?php endif; ?>

</section>

</main>


<script>
function showSection(id){
    const panels=document.querySelectorAll('.section-panel[id]');
    const tabs=document.querySelectorAll('.tabs a[data-section]');
    panels.forEach(p=>p.classList.toggle('active-section',p.id===id));
    tabs.forEach(t=>t.classList.toggle('active',t.dataset.section===id));
    if(history.replaceState && !document.body.classList.contains('print-mode')){
        history.replaceState(null,'','#'+id);
    }
}

document.addEventListener('DOMContentLoaded',function(){
    const initial=location.hash ? location.hash.substring(1) : 'pessoais';
    const valid=document.getElementById(initial) && document.querySelector('.tabs a[data-section="'+initial+'"]');
    showSection(valid ? initial : 'pessoais');
    const sectionTabs=Array.from(document.querySelectorAll('.tabs a[data-section]'));
    sectionTabs.forEach(function(tab,index){
        tab.addEventListener('mouseenter',function(){showSection(this.dataset.section);});
        tab.addEventListener('focus',function(){showSection(this.dataset.section);});
        tab.addEventListener('click',function(e){e.preventDefault();showSection(this.dataset.section);});
        tab.addEventListener('keydown',function(e){
            if(['ArrowLeft','ArrowRight','Home','End'].includes(e.key)){
                e.preventDefault();
                let nextIndex=index;
                if(e.key==='ArrowLeft') nextIndex=(index-1+sectionTabs.length)%sectionTabs.length;
                if(e.key==='ArrowRight') nextIndex=(index+1)%sectionTabs.length;
                if(e.key==='Home') nextIndex=0;
                if(e.key==='End') nextIndex=sectionTabs.length-1;
                const nextTab=sectionTabs[nextIndex];
                nextTab.focus();
                showSection(nextTab.dataset.section);
            }
        });
    });

    const modal=document.getElementById('requestModal');
    if(modal){modal.addEventListener('click',function(e){if(e.target===modal)fecharSolicitacao();});}
    document.addEventListener('click',function(e){const m=document.getElementById('printMenu');if(m&&!m.contains(e.target))m.classList.remove('open');});

    /* ========================================================
       BARRA DE SECÇÕES FIXA DURANTE A ROLAGEM
    ======================================================== */
    const especialistaTabs=document.getElementById('especialistaTabs');
    const tabsPlaceholder=document.querySelector('.tabs-placeholder');

    if(especialistaTabs && tabsPlaceholder){
        let tabsFixas=false;

        function atualizarTabsFixas(){
            const topbarValue=getComputedStyle(document.documentElement).getPropertyValue('--topbar').trim();
            const limite=(parseInt(topbarValue,10) || 72) + 2;
            const posicao=tabsPlaceholder.getBoundingClientRect().top;

            if(!tabsFixas && posicao <= limite){
                const rect=tabsPlaceholder.getBoundingClientRect();
                tabsPlaceholder.style.height=especialistaTabs.offsetHeight+'px';
                tabsPlaceholder.classList.add('active');
                especialistaTabs.style.setProperty('--tabs-left',rect.left+'px');
                especialistaTabs.style.setProperty('--tabs-width',rect.width+'px');
                especialistaTabs.classList.add('tabs-fixed');
                tabsFixas=true;
                return;
            }

            if(tabsFixas && posicao > limite){
                especialistaTabs.classList.remove('tabs-fixed');
                especialistaTabs.style.removeProperty('--tabs-left');
                especialistaTabs.style.removeProperty('--tabs-width');
                tabsPlaceholder.classList.remove('active');
                tabsPlaceholder.style.height='0px';
                tabsFixas=false;
            }
        }

        window.addEventListener('scroll',atualizarTabsFixas,{passive:true});
        window.addEventListener('resize',function(){
            if(tabsFixas){
                const rect=tabsPlaceholder.getBoundingClientRect();
                tabsPlaceholder.style.height=especialistaTabs.offsetHeight+'px';
                especialistaTabs.style.setProperty('--tabs-left',rect.left+'px');
                especialistaTabs.style.setProperty('--tabs-width',rect.width+'px');
            }
            atualizarTabsFixas();
        });
        atualizarTabsFixas();
    }

    /* ========================================================
       ROLAGEM INDEPENDENTE DO MENU IMPRIMIR
    ======================================================== */
    const printPanel=document.querySelector('.print-menu-panel');
    if(printPanel){
        printPanel.addEventListener('wheel',function(e){
            const podeSubir=this.scrollTop>0;
            const podeDescer=this.scrollTop+this.clientHeight<this.scrollHeight;
            if((e.deltaY<0 && podeSubir) || (e.deltaY>0 && podeDescer)){
                e.stopPropagation();
            }
        },{passive:true});
    }

    <?php if ($modoImpressao): ?>
    document.body.classList.add('print-mode');
    <?php if ($modoImpressao === 'secao'): ?>document.body.classList.add('print-secao');<?php endif; ?>
    <?php if ($modoImpressao === 'resumida'): ?>document.body.classList.add('print-resumida');<?php endif; ?>
    <?php if ($modoImpressao === 'completa'): ?>document.body.classList.add('print-completa');<?php endif; ?>
    <?php if ($modoImpressao === 'completa'): ?>
    document.querySelectorAll('.section-panel').forEach(function(p){
        if(['pessoais','servico','familiares','formacoes','habilitacoes','condecoracoes','ferias'].includes(p.id)){
            p.classList.add('active-section');
        }else{
            p.classList.remove('active-section');
        }
    });
    <?php endif; ?>
    <?php if ($modoImpressao === 'secao'): ?>
    document.querySelectorAll('.section-panel').forEach(function(p){p.classList.remove('active-section');});
    document.getElementById('<?= e($secaoImpressao) ?>')?.classList.add('active-section');
    <?php endif; ?>
    setTimeout(function(){window.print();},300);
    <?php endif; ?>
});

function togglePrintMenu(){
    const m=document.getElementById('printMenu');
    if(m)m.classList.toggle('open');
}

document.addEventListener('keydown',function(e){
    const m=document.getElementById('printMenu');
    if(e.key==='Escape' && m){
        m.classList.remove('open');
    }
});
function abrirSolicitacao(preselecionado){const modal=document.getElementById('requestModal');if(!modal)return;modal.classList.add('open');if(preselecionado){modal.dataset.preselecionado=preselecionado;}}
function fecharSolicitacao(){const modal=document.getElementById('requestModal');if(modal)modal.classList.remove('open');}
function selecionarSolicitacao(nome){
    const msg='Pedido selecionado: '+nome+'\n\nA emissão será ligada ao módulo de Configurações, onde serão definidos os modelos, campos e regras de cada documento.';
    alert(msg);
    fecharSolicitacao();
}
function eliminarRegistoSelecionado(secao){
    const section=document.getElementById(secao);
    if(!section){return;}
    const btn=section.querySelector('a[href*="_eliminar.php"]');
    if(btn){
        if(confirm('Deseja eliminar o primeiro registo desta secção? Esta operação poderá não ser desfeita.')){window.location.href=btn.href;}
        return;
    }
    const form=section.querySelector('form input[name="acao"][value*="eliminar"]');
    if(form){
        if(confirm('Deseja eliminar o primeiro registo desta secção? Esta operação poderá não ser desfeita.')){form.closest('form').submit();}
        return;
    }
    alert('A eliminação é feita individualmente nos registos desta secção.');
}
</script>


<div id="cameraModal" class="camera-modal" aria-hidden="true">
  <div class="camera-modal-backdrop" data-camera-close></div>
  <div class="camera-dialog" role="dialog" aria-modal="true" aria-labelledby="cameraTitle">
    <div class="camera-dialog-head">
      <div>
        <h2 id="cameraTitle">Tirar fotografia</h2>
        <p>Posicione o especialista e capture a fotografia.</p>
      </div>
      <button type="button" class="camera-close" data-camera-close aria-label="Fechar">×</button>
    </div>
    <video id="cameraVideo" autoplay playsinline></video>
    <canvas id="cameraCanvas" hidden></canvas>
    <div id="cameraStatus" class="camera-status">A preparar a câmara…</div>
    <div class="camera-actions">
      <button type="button" class="btn btn-secondary" data-camera-close>Cancelar</button>
      <button type="button" class="btn btn-primary" id="capturePhoto">Capturar fotografia</button>
    </div>
  </div>
</div>

<div id="requestModal" class="request-modal" role="dialog" aria-modal="true" aria-labelledby="requestTitle">
  <div class="request-modal-card">
    <div style="display:flex;justify-content:space-between;align-items:center;gap:12px;">
      <div>
        <h2 id="requestTitle" style="margin:0;">Solicitar documento</h2>
        <p style="margin:6px 0 0;color:#667085;">Selecione o tipo de documento a solicitar para este militar.</p>
      </div>
      <button type="button" class="btn btn-secondary btn-compact" onclick="fecharSolicitacao()">Fechar</button>
    </div>
    <div class="request-options">
      <button type="button" class="request-option" onclick="selecionarSolicitacao('Declaração de Efetividade')"><strong>Declaração de Efetividade</strong><br><small>Modelo configurável.</small></button>
      <button type="button" class="request-option" onclick="selecionarSolicitacao('Extrato de Serviço')"><strong>Extrato de Serviço</strong><br><small>Modelo configurável.</small></button>
      <button type="button" class="request-option" onclick="selecionarSolicitacao('Declaração de Serviço')"><strong>Declaração de Serviço</strong><br><small>Modelo configurável.</small></button>
      <button type="button" class="request-option" onclick="selecionarSolicitacao('Outro documento')"><strong>Outros documentos</strong><br><small>Serão acrescentados em Configurações.</small></button>
    </div>
  </div>
</div>
</div>

<script>
(function(){
    const modal = document.getElementById('cameraModal');
    const video = document.getElementById('cameraVideo');
    const canvas = document.getElementById('cameraCanvas');
    const capture = document.getElementById('capturePhoto');
    const status = document.getElementById('cameraStatus');
    const cameraInput = document.querySelector('.camera-file-input');
    let stream = null;

    function stopCamera(){
        if(stream){ stream.getTracks().forEach(t => t.stop()); stream = null; }
        if(video) video.srcObject = null;
    }
    function closeCamera(){
        if(!modal) return;
        modal.classList.remove('aberto');
        modal.setAttribute('aria-hidden','true');
        stopCamera();
    }
    async function openCamera(){
        if(!modal) return;
        if(!navigator.mediaDevices || !navigator.mediaDevices.getUserMedia){
            if(cameraInput) cameraInput.click();
            return;
        }
        modal.classList.add('aberto');
        modal.setAttribute('aria-hidden','false');
        status.textContent='A preparar a câmara…';
        try{
            stream = await navigator.mediaDevices.getUserMedia({video:{facingMode:{ideal:'environment'}},audio:false});
            video.srcObject = stream;
            await video.play();
            status.textContent='Posicione o especialista e clique em Capturar fotografia.';
        }catch(err){
            status.textContent='Não foi possível abrir a câmara. Será aberto o seletor de fotografia.';
            setTimeout(function(){ closeCamera(); if(cameraInput) cameraInput.click(); }, 900);
        }
    }
    document.querySelectorAll('.camera-upload-form .photo-hover-btn').forEach(function(btn){
        btn.addEventListener('click', function(e){
            e.preventDefault();
            openCamera();
        });
    });
    document.querySelectorAll('[data-camera-close]').forEach(function(el){ el.addEventListener('click', closeCamera); });
    if(capture){
        capture.addEventListener('click', function(){
            if(!stream || !video.videoWidth) return;
            canvas.width=video.videoWidth; canvas.height=video.videoHeight;
            canvas.getContext('2d').drawImage(video,0,0,canvas.width,canvas.height);
            canvas.toBlob(function(blob){
                if(!blob || !cameraInput) return;
                try{
                    const file = new File([blob], 'foto_camera_' + Date.now() + '.jpg', {type:'image/jpeg'});
                    const dt = new DataTransfer(); dt.items.add(file); cameraInput.files = dt.files;
                    closeCamera();
                    cameraInput.closest('form').submit();
                }catch(err){
                    status.textContent='Não foi possível preparar a fotografia.';
                }
            },'image/jpeg',0.92);
        });
    }
    document.addEventListener('keydown', function(e){ if(e.key==='Escape') closeCamera(); });
})();
</script>

<?php require __DIR__ . '/../../layout/footer.php'; ?>
