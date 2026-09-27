<?php
require_once __DIR__ . '/../../App/auth.php';
require_login();


$pdo = db();

$mensagem = '';
$tipo_mensagem = '';


// ============================================================
// FUNÇÕES AUXILIARES
// ============================================================

function e($valor)
{
    return htmlspecialchars((string)$valor, ENT_QUOTES, 'UTF-8');
}

function redirect_equipment($params = [])
{
    $url = 'equipamentos.php';

    if (!empty($params)) {
        $url .= '?' . http_build_query($params);
    }

    header('Location: ' . $url);
    exit;
}

function garantir_pasta($pasta)
{
    if (!is_dir($pasta)) {
        mkdir($pasta, 0775, true);
    }
}

function nome_seguro_ficheiro($nome)
{
    $nome = basename($nome);

    $nome = preg_replace(
        '/[^A-Za-z0-9_\-.]/',
        '_',
        $nome
    );

    return $nome;
}


// ============================================================
// PASTAS DE UPLOAD
// ============================================================

$diretorio_fotos = __DIR__ . '/../uploads/equipamentos/fotografias';
$diretorio_documentos = __DIR__ . '/../uploads/equipamentos/documentos';

garantir_pasta($diretorio_fotos);
garantir_pasta($diretorio_documentos);


// ============================================================
// DOWNLOAD DE FOTOGRAFIA
// ============================================================

if (isset($_GET['download_foto'])) {

    $id_fotografia = (int)$_GET['download_foto'];

    if ($id_fotografia <= 0) {
        http_response_code(400);
        exit('Fotografia inválida.');
    }

    $stmt = $pdo->prepare("
        SELECT
            id_fotografia,
            id_equipamento,
            nome_ficheiro,
            caminho_ficheiro
        FROM fotografias
        WHERE id_fotografia = :id
          AND id_equipamento IS NOT NULL
        LIMIT 1
    ");

    $stmt->execute([
        ':id' => $id_fotografia
    ]);

    $foto = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$foto) {
        http_response_code(404);
        exit('Fotografia não encontrada.');
    }

    $base = realpath($diretorio_fotos);

    if ($base === false) {
        http_response_code(404);
        exit('Diretório das fotografias não encontrado.');
    }

    $caminho = str_replace(
        '\\',
        '/',
        $foto['caminho_ficheiro']
    );

    $caminho = ltrim($caminho, '/');

    $arquivo = realpath(
        __DIR__ . '/' . $caminho
    );

    if (
        $arquivo === false ||
        strpos(
            $arquivo,
            $base . DIRECTORY_SEPARATOR
        ) !== 0
    ) {
        http_response_code(403);
        exit('Acesso ao ficheiro não permitido.');
    }

    if (!is_file($arquivo)) {
        http_response_code(404);
        exit('Ficheiro não encontrado.');
    }

    $nome = basename($foto['nome_ficheiro']);

    $extensao = strtolower(
        pathinfo($arquivo, PATHINFO_EXTENSION)
    );

    $mimes = [
        'jpg'  => 'image/jpeg',
        'jpeg' => 'image/jpeg',
        'png'  => 'image/png',
        'webp' => 'image/webp'
    ];

    $mime = $mimes[$extensao]
        ?? 'application/octet-stream';

    header('Content-Type: ' . $mime);
    header(
        'Content-Disposition: attachment; filename="' .
        str_replace('"', '', $nome) .
        '"'
    );
    header(
        'Content-Length: ' .
        filesize($arquivo)
    );
    header('Cache-Control: private, no-store');
    header('Pragma: no-cache');

    readfile($arquivo);
    exit;
}


// ============================================================
// DOWNLOAD DE DOCUMENTO
// ============================================================

if (isset($_GET['download_documento'])) {

    $id_documento = (int)$_GET['download_documento'];

    if ($id_documento <= 0) {
        http_response_code(400);
        exit('Documento inválido.');
    }

    $stmt = $pdo->prepare("
        SELECT
            id_documento,
            id_equipamento,
            nome_ficheiro,
            caminho_ficheiro
        FROM documentos
        WHERE id_documento = :id
          AND id_equipamento IS NOT NULL
        LIMIT 1
    ");

    $stmt->execute([
        ':id' => $id_documento
    ]);

    $documento = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$documento) {
        http_response_code(404);
        exit('Documento não encontrado.');
    }

    $base = realpath($diretorio_documentos);

    if ($base === false) {
        http_response_code(404);
        exit('Diretório dos documentos não encontrado.');
    }

    $caminho = str_replace(
        '\\',
        '/',
        $documento['caminho_ficheiro']
    );

    $caminho = ltrim($caminho, '/');

    $arquivo = realpath(
        __DIR__ . '/' . $caminho
    );

    if (
        $arquivo === false ||
        strpos(
            $arquivo,
            $base . DIRECTORY_SEPARATOR
        ) !== 0
    ) {
        http_response_code(403);
        exit('Acesso ao ficheiro não permitido.');
    }

    if (!is_file($arquivo)) {
        http_response_code(404);
        exit('Ficheiro não encontrado.');
    }

    $nome = basename($documento['nome_ficheiro']);

    $extensao = strtolower(
        pathinfo($arquivo, PATHINFO_EXTENSION)
    );

    $mimes = [
        'pdf'  => 'application/pdf',
        'doc'  => 'application/msword',
        'docx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
        'xls'  => 'application/vnd.ms-excel',
        'xlsx' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        'jpg'  => 'image/jpeg',
        'jpeg' => 'image/jpeg',
        'png'  => 'image/png'
    ];

    $mime = $mimes[$extensao]
        ?? 'application/octet-stream';

    header('Content-Type: ' . $mime);
    header(
        'Content-Disposition: attachment; filename="' .
        str_replace('"', '', $nome) .
        '"'
    );
    header(
        'Content-Length: ' .
        filesize($arquivo)
    );
    header('Cache-Control: private, no-store');
    header('Pragma: no-cache');

    readfile($arquivo);
    exit;
}


// ============================================================
// LISTAS DE APOIO
// ============================================================

$ramos = $pdo->query("
    SELECT id_ramo, nome, sigla
    FROM ramo
    WHERE estado = 'ATIVO'
    ORDER BY nome ASC
")->fetchAll(PDO::FETCH_ASSOC);


$unidades = $pdo->query("
    SELECT id_unidade, id_ramo, nome, sigla
    FROM unidades
    WHERE estado = 'ATIVO'
    ORDER BY nome ASC
")->fetchAll(PDO::FETCH_ASSOC);


$tipos_equipamento = $pdo->query("
    SELECT id_tipo_equipamento, nome
    FROM tipos_equipamento
    WHERE estado = 'ATIVO'
    ORDER BY nome ASC
")->fetchAll(PDO::FETCH_ASSOC);


$estados_equipamento = $pdo->query("
    SELECT id_estado_equipamento, nome
    FROM estados_equipamento
    WHERE estado = 'ATIVO'
    ORDER BY nome ASC
")->fetchAll(PDO::FETCH_ASSOC);


$tipos_documento = $pdo->query("
    SELECT
        id_tipo_documento,
        nome
    FROM tipos_documento
    WHERE estado = 'ATIVO'
    ORDER BY nome ASC
")->fetchAll(PDO::FETCH_ASSOC);


// ============================================================
// AÇÕES POST
// ============================================================

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $acao = $_POST['acao'] ?? '';


    // ========================================================
    // ADICIONAR EQUIPAMENTO
    // ========================================================

    if ($acao === 'adicionar') {

        $id_ramo = !empty($_POST['id_ramo'])
            ? (int)$_POST['id_ramo']
            : null;

        $id_unidade = !empty($_POST['id_unidade'])
            ? (int)$_POST['id_unidade']
            : null;

        $id_tipo_equipamento =
            !empty($_POST['id_tipo_equipamento'])
            ? (int)$_POST['id_tipo_equipamento']
            : null;

        $designacao = trim(
            $_POST['designacao'] ?? ''
        );

        $marca = trim(
            $_POST['marca'] ?? ''
        );

        $modelo = trim(
            $_POST['modelo'] ?? ''
        );

        $numero_serie = trim(
            $_POST['numero_serie'] ?? ''
        );

        $numero_patrimonio = trim(
            $_POST['numero_patrimonio'] ?? ''
        );

        $data_aquisicao =
            !empty($_POST['data_aquisicao'])
            ? $_POST['data_aquisicao']
            : null;

        $id_estado_equipamento =
            !empty($_POST['id_estado_equipamento'])
            ? (int)$_POST['id_estado_equipamento']
            : null;

        $localizacao = trim(
            $_POST['localizacao'] ?? ''
        );

        $descricao = trim(
            $_POST['descricao'] ?? ''
        );

        $observacoes = trim(
            $_POST['observacoes'] ?? ''
        );


        if ($id_ramo <= 0) {

            $mensagem = 'Selecione o ramo.';
            $tipo_mensagem = 'erro';

        } elseif ($designacao === '') {

            $mensagem =
                'A designação do equipamento é obrigatória.';

            $tipo_mensagem = 'erro';

        } else {

            try {

                $stmt = $pdo->prepare("
                    INSERT INTO equipamentos (
                        id_ramo,
                        id_unidade,
                        id_tipo_equipamento,
                        designacao,
                        marca,
                        modelo,
                        numero_serie,
                        numero_patrimonio,
                        data_aquisicao,
                        id_estado_equipamento,
                        localizacao,
                        descricao,
                        observacoes
                    )
                    VALUES (
                        :id_ramo,
                        :id_unidade,
                        :id_tipo_equipamento,
                        :designacao,
                        :marca,
                        :modelo,
                        :numero_serie,
                        :numero_patrimonio,
                        :data_aquisicao,
                        :id_estado_equipamento,
                        :localizacao,
                        :descricao,
                        :observacoes
                    )
                ");

                $stmt->execute([

                    ':id_ramo' =>
                        $id_ramo,

                    ':id_unidade' =>
                        $id_unidade,

                    ':id_tipo_equipamento' =>
                        $id_tipo_equipamento,

                    ':designacao' =>
                        $designacao,

                    ':marca' =>
                        $marca !== ''
                            ? $marca
                            : null,

                    ':modelo' =>
                        $modelo !== ''
                            ? $modelo
                            : null,

                    ':numero_serie' =>
                        $numero_serie !== ''
                            ? $numero_serie
                            : null,

                    ':numero_patrimonio' =>
                        $numero_patrimonio !== ''
                            ? $numero_patrimonio
                            : null,

                    ':data_aquisicao' =>
                        $data_aquisicao,

                    ':id_estado_equipamento' =>
                        $id_estado_equipamento,

                    ':localizacao' =>
                        $localizacao !== ''
                            ? $localizacao
                            : null,

                    ':descricao' =>
                        $descricao !== ''
                            ? $descricao
                            : null,

                    ':observacoes' =>
                        $observacoes !== ''
                            ? $observacoes
                            : null
                ]);

                redirect_equipment([
                    'sucesso' => 'adicionado'
                ]);
            }

            catch (PDOException $e) {

                if ($e->getCode() == '23000') {

                    $mensagem =
                        'Não foi possível cadastrar. ' .
                        'O número de série já pode estar registado.';

                } else {

                    $mensagem =
                        'Erro ao cadastrar o equipamento.';
                }

                $tipo_mensagem = 'erro';
            }
        }
    }


    // ========================================================
    // ATUALIZAR EQUIPAMENTO
    // ========================================================

    elseif ($acao === 'atualizar') {

        $id_equipamento =
            (int)($_POST['id_equipamento'] ?? 0);

        $id_ramo =
            !empty($_POST['id_ramo'])
                ? (int)$_POST['id_ramo']
                : null;

        $id_unidade =
            !empty($_POST['id_unidade'])
                ? (int)$_POST['id_unidade']
                : null;

        $id_tipo_equipamento =
            !empty($_POST['id_tipo_equipamento'])
                ? (int)$_POST['id_tipo_equipamento']
                : null;

        $designacao =
            trim($_POST['designacao'] ?? '');

        $marca =
            trim($_POST['marca'] ?? '');

        $modelo =
            trim($_POST['modelo'] ?? '');

        $numero_serie =
            trim($_POST['numero_serie'] ?? '');

        $numero_patrimonio =
            trim($_POST['numero_patrimonio'] ?? '');

        $data_aquisicao =
            !empty($_POST['data_aquisicao'])
                ? $_POST['data_aquisicao']
                : null;

        $id_estado_equipamento =
            !empty($_POST['id_estado_equipamento'])
                ? (int)$_POST['id_estado_equipamento']
                : null;

        $localizacao =
            trim($_POST['localizacao'] ?? '');

        $descricao =
            trim($_POST['descricao'] ?? '');

        $observacoes =
            trim($_POST['observacoes'] ?? '');


        if ($id_equipamento <= 0) {

            $mensagem =
                'Equipamento inválido.';

            $tipo_mensagem = 'erro';

        } elseif ($id_ramo <= 0) {

            $mensagem =
                'Selecione o ramo.';

            $tipo_mensagem = 'erro';

        } elseif ($designacao === '') {

            $mensagem =
                'A designação do equipamento é obrigatória.';

            $tipo_mensagem = 'erro';

        } else {

            try {

                $stmt = $pdo->prepare("
                    UPDATE equipamentos
                    SET
                        id_ramo = :id_ramo,
                        id_unidade = :id_unidade,
                        id_tipo_equipamento = :id_tipo_equipamento,
                        designacao = :designacao,
                        marca = :marca,
                        modelo = :modelo,
                        numero_serie = :numero_serie,
                        numero_patrimonio = :numero_patrimonio,
                        data_aquisicao = :data_aquisicao,
                        id_estado_equipamento = :id_estado_equipamento,
                        localizacao = :localizacao,
                        descricao = :descricao,
                        observacoes = :observacoes
                    WHERE id_equipamento = :id_equipamento
                ");

                $stmt->execute([

                    ':id_ramo' =>
                        $id_ramo,

                    ':id_unidade' =>
                        $id_unidade,

                    ':id_tipo_equipamento' =>
                        $id_tipo_equipamento,

                    ':designacao' =>
                        $designacao,

                    ':marca' =>
                        $marca !== ''
                            ? $marca
                            : null,

                    ':modelo' =>
                        $modelo !== ''
                            ? $modelo
                            : null,

                    ':numero_serie' =>
                        $numero_serie !== ''
                            ? $numero_serie
                            : null,

                    ':numero_patrimonio' =>
                        $numero_patrimonio !== ''
                            ? $numero_patrimonio
                            : null,

                    ':data_aquisicao' =>
                        $data_aquisicao,

                    ':id_estado_equipamento' =>
                        $id_estado_equipamento,

                    ':localizacao' =>
                        $localizacao !== ''
                            ? $localizacao
                            : null,

                    ':descricao' =>
                        $descricao !== ''
                            ? $descricao
                            : null,

                    ':observacoes' =>
                        $observacoes !== ''
                            ? $observacoes
                            : null,

                    ':id_equipamento' =>
                        $id_equipamento
                ]);

                redirect_equipment([
                    'sucesso' => 'atualizado'
                ]);
            }

            catch (PDOException $e) {

                if ($e->getCode() == '23000') {

                    $mensagem =
                        'Não foi possível atualizar. ' .
                        'O número de série já pode estar registado ' .
                        'noutro equipamento.';

                } else {

                    $mensagem =
                        'Erro ao atualizar o equipamento.';
                }

                $tipo_mensagem = 'erro';
            }
        }
    }


    // ========================================================
    // ELIMINAR EQUIPAMENTO
    // ========================================================

    elseif ($acao === 'eliminar') {

        $id_equipamento =
            (int)($_POST['id_equipamento'] ?? 0);

        if ($id_equipamento <= 0) {

            $mensagem =
                'Equipamento inválido.';

            $tipo_mensagem = 'erro';

        } else {

            try {

                $stmt = $pdo->prepare("
                    DELETE FROM equipamentos
                    WHERE id_equipamento = :id
                ");

                $stmt->execute([
                    ':id' => $id_equipamento
                ]);

                redirect_equipment([
                    'sucesso' => 'eliminado'
                ]);
            }

            catch (PDOException $e) {

                $mensagem =
                    'Não foi possível eliminar este equipamento. ' .
                    'Existem registos associados a ele.';

                $tipo_mensagem = 'erro';
            }
        }
    }


    // ========================================================
    // UPLOAD DE FOTOGRAFIA
    // ========================================================

    elseif ($acao === 'upload_foto') {

        $id_equipamento =
            (int)($_POST['id_equipamento'] ?? 0);

        if (
            $id_equipamento <= 0 ||
            empty($_FILES['fotografia']['name'])
        ) {

            $mensagem =
                'Selecione uma fotografia válida.';

            $tipo_mensagem = 'erro';

        } else {

            $arquivo = $_FILES['fotografia'];

            if ($arquivo['error'] !== UPLOAD_ERR_OK) {

                $mensagem =
                    'Erro durante o envio da fotografia.';

                $tipo_mensagem = 'erro';

            } elseif ($arquivo['size'] > 8 * 1024 * 1024) {

                $mensagem =
                    'A fotografia não pode ultrapassar 8 MB.';

                $tipo_mensagem = 'erro';

            } else {

                $extensao = strtolower(
                    pathinfo(
                        $arquivo['name'],
                        PATHINFO_EXTENSION
                    )
                );

                $permitidas = [
                    'jpg',
                    'jpeg',
                    'png',
                    'webp'
                ];

                if (!in_array($extensao, $permitidas, true)) {

                    $mensagem =
                        'Formato de fotografia não permitido.';

                    $tipo_mensagem = 'erro';

                } else {

                    $nome_original =
                        nome_seguro_ficheiro(
                            $arquivo['name']
                        );

                    $nome_unico =
                        date('YmdHis') .
                        '_' .
                        bin2hex(random_bytes(6)) .
                        '.' .
                        $extensao;

                    $destino =
                        $diretorio_fotos .
                        DIRECTORY_SEPARATOR .
                        $nome_unico;

                    if (
                        move_uploaded_file(
                            $arquivo['tmp_name'],
                            $destino
                        )
                    ) {

                        $caminho_bd =
                            'uploads/equipamentos/fotografias/' .
                            $nome_unico;

                        $principal =
                            !empty($_POST['principal'])
                                ? 1
                                : 0;

                        if ($principal) {

                            $stmt = $pdo->prepare("
                                UPDATE fotografias
                                SET principal = 0
                                WHERE id_equipamento = :id
                            ");

                            $stmt->execute([
                                ':id' =>
                                    $id_equipamento
                            ]);
                        }

                        $stmt = $pdo->prepare("
                            INSERT INTO fotografias (
                                id_especialista,
                                id_equipamento,
                                nome_ficheiro,
                                caminho_ficheiro,
                                principal,
                                descricao
                            )
                            VALUES (
                                NULL,
                                :id_equipamento,
                                :nome_ficheiro,
                                :caminho_ficheiro,
                                :principal,
                                :descricao
                            )
                        ");

                        $stmt->execute([

                            ':id_equipamento' =>
                                $id_equipamento,

                            ':nome_ficheiro' =>
                                $nome_original,

                            ':caminho_ficheiro' =>
                                $caminho_bd,

                            ':principal' =>
                                $principal,

                            ':descricao' =>
                                trim(
                                    $_POST['descricao_foto'] ?? ''
                                ) ?: null
                        ]);

                        redirect_equipment([
                            'sucesso' => 'foto_adicionada'
                        ]);

                    } else {

                        $mensagem =
                            'Não foi possível guardar a fotografia.';

                        $tipo_mensagem = 'erro';
                    }
                }
            }
        }
    }


    // ========================================================
    // DEFINIR FOTO PRINCIPAL
    // ========================================================

    elseif ($acao === 'foto_principal') {

        $id_fotografia =
            (int)($_POST['id_fotografia'] ?? 0);

        $id_equipamento =
            (int)($_POST['id_equipamento'] ?? 0);

        if (
            $id_fotografia <= 0 ||
            $id_equipamento <= 0
        ) {

            $mensagem =
                'Fotografia inválida.';

            $tipo_mensagem = 'erro';

        } else {

            try {

                $pdo->beginTransaction();

                $stmt = $pdo->prepare("
                    UPDATE fotografias
                    SET principal = 0
                    WHERE id_equipamento = :id
                ");

                $stmt->execute([
                    ':id' => $id_equipamento
                ]);

                $stmt = $pdo->prepare("
                    UPDATE fotografias
                    SET principal = 1
                    WHERE id_fotografia = :foto
                      AND id_equipamento = :id
                ");

                $stmt->execute([
                    ':foto' =>
                        $id_fotografia,

                    ':id' =>
                        $id_equipamento
                ]);

                $pdo->commit();

                redirect_equipment([
                    'sucesso' => 'foto_principal'
                ]);

            } catch (PDOException $e) {

                if ($pdo->inTransaction()) {
                    $pdo->rollBack();
                }

                $mensagem =
                    'Não foi possível definir a fotografia principal.';

                $tipo_mensagem = 'erro';
            }
        }
    }


    // ========================================================
    // ELIMINAR FOTOGRAFIA
    // ========================================================

    elseif ($acao === 'eliminar_foto') {

        $id_fotografia =
            (int)($_POST['id_fotografia'] ?? 0);

        if ($id_fotografia <= 0) {

            $mensagem =
                'Fotografia inválida.';

            $tipo_mensagem = 'erro';

        } else {

            try {

                $stmt = $pdo->prepare("
                    SELECT caminho_ficheiro
                    FROM fotografias
                    WHERE id_fotografia = :id
                ");

                $stmt->execute([
                    ':id' => $id_fotografia
                ]);

                $foto =
                    $stmt->fetch(PDO::FETCH_ASSOC);

                if ($foto) {

                    $arquivo =
                        __DIR__ .
                        '/' .
                        ltrim(
                            str_replace(
                                '\\',
                                '/',
                                $foto['caminho_ficheiro']
                            ),
                            '/'
                        );

                    if (is_file($arquivo)) {
                        @unlink($arquivo);
                    }

                    $stmt = $pdo->prepare("
                        DELETE FROM fotografias
                        WHERE id_fotografia = :id
                    ");

                    $stmt->execute([
                        ':id' =>
                            $id_fotografia
                    ]);
                }

                redirect_equipment([
                    'sucesso' => 'foto_eliminada'
                ]);

            } catch (PDOException $e) {

                $mensagem =
                    'Não foi possível eliminar a fotografia.';

                $tipo_mensagem = 'erro';
            }
        }
    }


    // ========================================================
    // UPLOAD DE DOCUMENTO
    // ========================================================

    elseif ($acao === 'upload_documento') {

        $id_equipamento =
            (int)($_POST['id_equipamento'] ?? 0);

        $id_tipo_documento =
            (int)($_POST['id_tipo_documento'] ?? 0);

        $titulo =
            trim($_POST['titulo'] ?? '');

        if (
            $id_equipamento <= 0 ||
            $id_tipo_documento <= 0 ||
            $titulo === '' ||
            empty($_FILES['documento']['name'])
        ) {

            $mensagem =
                'Preencha o título, tipo de documento e selecione o ficheiro.';

            $tipo_mensagem = 'erro';

        } else {

            $arquivo = $_FILES['documento'];

            if ($arquivo['error'] !== UPLOAD_ERR_OK) {

                $mensagem =
                    'Erro durante o envio do documento.';

                $tipo_mensagem = 'erro';

            } elseif ($arquivo['size'] > 15 * 1024 * 1024) {

                $mensagem =
                    'O documento não pode ultrapassar 15 MB.';

                $tipo_mensagem = 'erro';

            } else {

                $extensao = strtolower(
                    pathinfo(
                        $arquivo['name'],
                        PATHINFO_EXTENSION
                    )
                );

                $permitidas = [
                    'pdf',
                    'doc',
                    'docx',
                    'xls',
                    'xlsx',
                    'jpg',
                    'jpeg',
                    'png'
                ];

                if (!in_array($extensao, $permitidas, true)) {

                    $mensagem =
                        'Formato de documento não permitido.';

                    $tipo_mensagem = 'erro';

                } else {

                    $nome_original =
                        nome_seguro_ficheiro(
                            $arquivo['name']
                        );

                    $nome_unico =
                        date('YmdHis') .
                        '_' .
                        bin2hex(random_bytes(6)) .
                        '.' .
                        $extensao;

                    $destino =
                        $diretorio_documentos .
                        DIRECTORY_SEPARATOR .
                        $nome_unico;

                    if (
                        move_uploaded_file(
                            $arquivo['tmp_name'],
                            $destino
                        )
                    ) {

                        $caminho_bd =
                            'uploads/equipamentos/documentos/' .
                            $nome_unico;

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
                                descricao
                            )
                            VALUES (
                                NULL,
                                :id_equipamento,
                                :id_tipo_documento,
                                :titulo,
                                :nome_ficheiro,
                                :caminho_ficheiro,
                                :data_documento,
                                :data_validade,
                                :descricao
                            )
                        ");

                        $stmt->execute([

                            ':id_equipamento' =>
                                $id_equipamento,

                            ':id_tipo_documento' =>
                                $id_tipo_documento,

                            ':titulo' =>
                                $titulo,

                            ':nome_ficheiro' =>
                                $nome_original,

                            ':caminho_ficheiro' =>
                                $caminho_bd,

                            ':data_documento' =>
                                !empty($_POST['data_documento'])
                                    ? $_POST['data_documento']
                                    : null,

                            ':data_validade' =>
                                !empty($_POST['data_validade'])
                                    ? $_POST['data_validade']
                                    : null,

                            ':descricao' =>
                                trim(
                                    $_POST['descricao_documento'] ?? ''
                                ) ?: null
                        ]);

                        redirect_equipment([
                            'sucesso' => 'documento_adicionado'
                        ]);

                    } else {

                        $mensagem =
                            'Não foi possível guardar o documento.';

                        $tipo_mensagem = 'erro';
                    }
                }
            }
        }
    }


    // ========================================================
    // ELIMINAR DOCUMENTO
    // ========================================================

    elseif ($acao === 'eliminar_documento') {

        $id_documento =
            (int)($_POST['id_documento'] ?? 0);

        if ($id_documento <= 0) {

            $mensagem =
                'Documento inválido.';

            $tipo_mensagem = 'erro';

        } else {

            try {

                $stmt = $pdo->prepare("
                    SELECT caminho_ficheiro
                    FROM documentos
                    WHERE id_documento = :id
                ");

                $stmt->execute([
                    ':id' =>
                        $id_documento
                ]);

                $documento =
                    $stmt->fetch(PDO::FETCH_ASSOC);

                if ($documento) {

                    $arquivo =
                        __DIR__ .
                        '/' .
                        ltrim(
                            str_replace(
                                '\\',
                                '/',
                                $documento['caminho_ficheiro']
                            ),
                            '/'
                        );

                    if (is_file($arquivo)) {
                        @unlink($arquivo);
                    }

                    $stmt = $pdo->prepare("
                        DELETE FROM documentos
                        WHERE id_documento = :id
                    ");

                    $stmt->execute([
                        ':id' =>
                            $id_documento
                    ]);
                }

                redirect_equipment([
                    'sucesso' => 'documento_eliminado'
                ]);

            } catch (PDOException $e) {

                $mensagem =
                    'Não foi possível eliminar o documento.';

                $tipo_mensagem = 'erro';
            }
        }
    }
}


// ============================================================
// MENSAGENS
// ============================================================

if (isset($_GET['sucesso'])) {

    switch ($_GET['sucesso']) {

        case 'adicionado':
            $mensagem =
                'Equipamento cadastrado com sucesso.';
            $tipo_mensagem = 'sucesso';
            break;

        case 'atualizado':
            $mensagem =
                'Equipamento atualizado com sucesso.';
            $tipo_mensagem = 'sucesso';
            break;

        case 'eliminado':
            $mensagem =
                'Equipamento eliminado com sucesso.';
            $tipo_mensagem = 'sucesso';
            break;

        case 'foto_adicionada':
            $mensagem =
                'Fotografia importada com sucesso.';
            $tipo_mensagem = 'sucesso';
            break;

        case 'foto_principal':
            $mensagem =
                'Fotografia principal atualizada.';
            $tipo_mensagem = 'sucesso';
            break;

        case 'foto_eliminada':
            $mensagem =
                'Fotografia eliminada com sucesso.';
            $tipo_mensagem = 'sucesso';
            break;

        case 'documento_adicionado':
            $mensagem =
                'Documento importado com sucesso.';
            $tipo_mensagem = 'sucesso';
            break;

        case 'documento_eliminado':
            $mensagem =
                'Documento eliminado com sucesso.';
            $tipo_mensagem = 'sucesso';
            break;
    }
}


// ============================================================
// EDIÇÃO
// ============================================================

$equipamento_editar = null;

if (isset($_GET['editar'])) {

    $id_editar =
        (int)$_GET['editar'];

    if ($id_editar > 0) {

        $stmt = $pdo->prepare("
            SELECT
                id_equipamento,
                id_ramo,
                id_unidade,
                id_tipo_equipamento,
                designacao,
                marca,
                modelo,
                numero_serie,
                numero_patrimonio,
                data_aquisicao,
                id_estado_equipamento,
                localizacao,
                descricao,
                observacoes
            FROM equipamentos
            WHERE id_equipamento = :id
        ");

        $stmt->execute([
            ':id' =>
                $id_editar
        ]);

        $equipamento_editar =
            $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$equipamento_editar) {

            $mensagem =
                'Equipamento não encontrado.';

            $tipo_mensagem = 'erro';
        }
    }
}


// ============================================================
// PESQUISA
// ============================================================

$pesquisa =
    trim($_GET['pesquisa'] ?? '');

$sql = "
    SELECT
        e.*,

        r.nome AS ramo_nome,
        r.sigla AS ramo_sigla,

        u.nome AS unidade_nome,
        u.sigla AS unidade_sigla,

        te.nome AS tipo_nome,

        ee.nome AS estado_nome

    FROM equipamentos e

    INNER JOIN ramo r
        ON r.id_ramo = e.id_ramo

    LEFT JOIN unidades u
        ON u.id_unidade = e.id_unidade

    LEFT JOIN tipos_equipamento te
        ON te.id_tipo_equipamento =
           e.id_tipo_equipamento

    LEFT JOIN estados_equipamento ee
        ON ee.id_estado_equipamento =
           e.id_estado_equipamento
";

$params = [];

if ($pesquisa !== '') {

    $sql .= "
        WHERE
            e.designacao LIKE :pesquisa
            OR e.marca LIKE :pesquisa
            OR e.modelo LIKE :pesquisa
            OR e.numero_serie LIKE :pesquisa
            OR e.numero_patrimonio LIKE :pesquisa
            OR e.localizacao LIKE :pesquisa
            OR r.nome LIKE :pesquisa
            OR u.nome LIKE :pesquisa
            OR te.nome LIKE :pesquisa
            OR ee.nome LIKE :pesquisa
    ";

    $params[':pesquisa'] =
        '%' . $pesquisa . '%';
}

$sql .= "
    ORDER BY e.designacao ASC
";

$stmt =
    $pdo->prepare($sql);

$stmt->execute($params);

$equipamentos =
    $stmt->fetchAll(PDO::FETCH_ASSOC);


// ============================================================
// FOTOGRAFIAS E DOCUMENTOS
// ============================================================

$fotografias_equipamentos = [];
$documentos_equipamentos = [];

foreach ($equipamentos as $equipamento) {

    $id_eq =
        (int)$equipamento['id_equipamento'];


    // --------------------------------------------------------
    // FOTOGRAFIAS
    // --------------------------------------------------------

    $stmt = $pdo->prepare("
        SELECT
            id_fotografia,
            nome_ficheiro,
            caminho_ficheiro,
            principal,
            descricao,
            data_registo
        FROM fotografias
        WHERE id_equipamento = :id
        ORDER BY principal DESC, id_fotografia DESC
    ");

    $stmt->execute([
        ':id' =>
            $id_eq
    ]);

    $fotografias_equipamentos[$id_eq] =
        $stmt->fetchAll(PDO::FETCH_ASSOC);


    // --------------------------------------------------------
    // DOCUMENTOS
    // --------------------------------------------------------

    $stmt = $pdo->prepare("
        SELECT
            d.id_documento,
            d.id_tipo_documento,
            d.titulo,
            d.nome_ficheiro,
            d.caminho_ficheiro,
            d.data_documento,
            d.data_validade,
            d.descricao,
            td.nome AS tipo_documento
        FROM documentos d

        LEFT JOIN tipos_documento td
            ON td.id_tipo_documento =
               d.id_tipo_documento

        WHERE d.id_equipamento = :id

        ORDER BY d.id_documento DESC
    ");

    $stmt->execute([
        ':id' =>
            $id_eq
    ]);

    $documentos_equipamentos[$id_eq] =
        $stmt->fetchAll(PDO::FETCH_ASSOC);
}


$total_equipamentos =
    count($equipamentos);
$page_title = "Equipamentos";
require __DIR__ . '/../layout/header.php';
?>

<div class="module-page module-equipamentos">
<style>


.module-page.module-equipamentos * {
    box-sizing: border-box;
}

.module-page.module-equipamentos {
    margin: 0;
    padding: 0;
    background: #f4f6f8;
    color: #1f2937;
    font-family: Arial, Helvetica, sans-serif;
}

.module-page.module-equipamentos .container {
    width: 95%;
    max-width: 1600px;
    margin: 30px auto;
}

.module-page.module-equipamentos .cabecalho {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 20px;
    margin-bottom: 20px;
    flex-wrap: wrap;
}

.module-page.module-equipamentos .titulo {
    margin: 0;
    font-size: 28px;
    color: #172033;
}

.module-page.module-equipamentos .subtitulo {
    margin-top: 6px;
    color: #6b7280;
    font-size: 14px;
}

.module-page.module-equipamentos .card {
    background: #ffffff;
    border-radius: 10px;
    padding: 20px;
    margin-bottom: 20px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.06);
}

.module-page.module-equipamentos .grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 16px;
}

.module-page.module-equipamentos .campo {
    display: flex;
    flex-direction: column;
    gap: 7px;
}

.module-page.module-equipamentos .campo-largo {
    grid-column: span 2;
}

.module-page.module-equipamentos .campo-completo {
    grid-column: 1 / -1;
}

.module-page.module-equipamentos label {
    font-weight: 600;
    font-size: 14px;
    color: #374151;
}

.module-page.module-equipamentos input, .module-page.module-equipamentos select, .module-page.module-equipamentos textarea {
    width: 100%;
    border: 1px solid #d1d5db;
    border-radius: 7px;
    padding: 10px 12px;
    font-size: 14px;
    background: #ffffff;
    outline: none;
}

.module-page.module-equipamentos input:focus, .module-page.module-equipamentos select:focus, .module-page.module-equipamentos textarea:focus {
    border-color: #2563eb;
    box-shadow:
        0 0 0 2px rgba(37,99,235,0.10);
}

.module-page.module-equipamentos textarea {
    min-height: 100px;
    resize: vertical;
}

.module-page.module-equipamentos .acoes {
    display: flex;
    align-items: center;
    gap: 8px;
    flex-wrap: wrap;
}

.module-page.module-equipamentos .btn {
    border: none;
    border-radius: 7px;
    padding: 10px 15px;
    cursor: pointer;
    text-decoration: none;
    display: inline-block;
    font-size: 14px;
    font-weight: 600;
}

.module-page.module-equipamentos .btn-primary {
    background: #2563eb;
    color: white;
}

.module-page.module-equipamentos .btn-secondary {
    background: #6b7280;
    color: white;
}

.module-page.module-equipamentos .btn-warning {
    background: #d97706;
    color: white;
}

.module-page.module-equipamentos .btn-danger {
    background: #dc2626;
    color: white;
}

.module-page.module-equipamentos .btn-success {
    background: #15803d;
    color: white;
}

.module-page.module-equipamentos .btn-small {
    padding: 7px 10px;
    font-size: 12px;
}

.module-page.module-equipamentos .mensagem {
    padding: 13px 16px;
    border-radius: 7px;
    margin-bottom: 20px;
    font-size: 14px;
}

.module-page.module-equipamentos .mensagem-sucesso {
    background: #dcfce7;
    color: #166534;
    border: 1px solid #86efac;
}

.module-page.module-equipamentos .mensagem-erro {
    background: #fee2e2;
    color: #991b1b;
    border: 1px solid #fca5a5;
}

.module-page.module-equipamentos .barra-pesquisa {
    display: flex;
    gap: 10px;
    align-items: center;
}

.module-page.module-equipamentos .barra-pesquisa input {
    flex: 1;
}

.module-page.module-equipamentos .total {
    color: #6b7280;
    font-size: 14px;
    margin-bottom: 12px;
}

.module-page.module-equipamentos .tabela-container {
    overflow: hidden;
}

.module-page.module-equipamentos table {
    width: 100%;
    border-collapse: collapse;
    table-layout: fixed;
}

.module-page.module-equipamentos th, .module-page.module-equipamentos td {
    padding: 11px 10px;
    border-bottom: 1px solid #e5e7eb;
    text-align: left;
    vertical-align: top;
    font-size: 13px;
}

.module-page.module-equipamentos th {
    background: #f9fafb;
    color: #374151;
    font-weight: 700;
}

.module-page.module-equipamentos tr:hover td {
    background: #f9fafb;
}

.module-page.module-equipamentos .badge {
    display: inline-block;
    padding: 5px 8px;
    border-radius: 20px;
    background: #e5e7eb;
    color: #374151;
    font-size: 11px;
    font-weight: 600;
}

.module-page.module-equipamentos .badge-principal {
    background: #dbeafe;
    color: #1d4ed8;
}

.module-page.module-equipamentos .sem-registos {
    text-align: center;
    padding: 30px;
    color: #6b7280;
}

.module-page.module-equipamentos .secao-titulo {
    margin: 0 0 18px 0;
    font-size: 19px;
    color: #172033;
}

.module-page.module-equipamentos .obrigatorio {
    color: #dc2626;
}

.module-page.module-equipamentos .ficheiro-item {
    padding: 7px 0;
    border-bottom: 1px solid #eeeeee;
}

.module-page.module-equipamentos .ficheiro-item:last-child {
    border-bottom: none;
}

.module-page.module-equipamentos .ficheiro-nome {
    display: block;
    max-width: 220px;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
    font-size: 12px;
}

.module-page.module-equipamentos .ficheiro-acoes {
    display: flex;
    gap: 5px;
    flex-wrap: wrap;
    margin-top: 5px;
}

.module-page.module-equipamentos .form-ficheiro {
    margin-top: 8px;
    padding: 10px;
    background: #f9fafb;
    border-radius: 7px;
}

.module-page.module-equipamentos .form-ficheiro input, .module-page.module-equipamentos .form-ficheiro select {
    margin-bottom: 7px;
}

.module-page.module-equipamentos .form-ficheiro label {
    display: block;
    margin-bottom: 4px;
}

.module-page.module-equipamentos .secao-ficheiros {
    margin-top: 12px;
}

@media (max-width: 1100px) {

    .module-page.module-equipamentos .grid {
        grid-template-columns: repeat(2, 1fr);
    }

    .module-page.module-equipamentos .campo-largo {
        grid-column: span 2;
    }
}

@media (max-width: 700px) {

    .module-page.module-equipamentos .container {
        width: 94%;
        margin: 20px auto;
    }

    .module-page.module-equipamentos .grid {
        grid-template-columns: 1fr;
    }

    .module-page.module-equipamentos .campo-largo, .module-page.module-equipamentos .campo-completo {
        grid-column: span 1;
    }

    .module-page.module-equipamentos .barra-pesquisa {
        flex-direction: column;
        align-items: stretch;
    }

    .module-page.module-equipamentos .cabecalho {
        align-items: flex-start;
    }
}


</style>
<div class="container">


<!-- ======================================================
     CABEÇALHO
     ====================================================== -->

<div class="cabecalho">

    <div>

        <h1 class="titulo">
            Gestão de Equipamentos
        </h1>

        <div class="subtitulo">
            Cadastro, fotografias e documentos dos equipamentos
        </div>

    </div>

    <div class="acoes">

        <?php if ($equipamento_editar): ?>

            <a
                href="equipamentos.php"
                class="btn btn-secondary"
            >
                Cancelar edição
            </a>

        <?php else: ?>

            <a
                href="equipamentos.php?novo=1"
                class="btn btn-primary"
            >
                + Novo equipamento
            </a>

        <?php endif; ?>

    </div>

</div>


<!-- ======================================================
     MENSAGEM
     ====================================================== -->

<?php if ($mensagem !== ''): ?>

<div class="mensagem
    <?= $tipo_mensagem === 'sucesso'
        ? 'mensagem-sucesso'
        : 'mensagem-erro'
    ?>"
>

    <?= e($mensagem) ?>

</div>

<?php endif; ?>


<!-- ======================================================
     FORMULÁRIO EQUIPAMENTO
     ====================================================== -->

<?php if (
    $equipamento_editar ||
    isset($_GET['novo'])
): ?>

<div class="card">

    <h2 class="secao-titulo">

        <?= $equipamento_editar
            ? 'Editar equipamento'
            : 'Novo equipamento'
        ?>

    </h2>

    <form method="post">

        <input
            type="hidden"
            name="acao"
            value="<?= $equipamento_editar
                ? 'atualizar'
                : 'adicionar'
            ?>"
        >

        <?php if ($equipamento_editar): ?>

            <input
                type="hidden"
                name="id_equipamento"
                value="<?= (int)
                    $equipamento_editar['id_equipamento']
                ?>"
            >

        <?php endif; ?>


        <div class="grid">


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

                    <option value="">
                        Selecionar ramo
                    </option>

                    <?php foreach ($ramos as $ramo): ?>

                        <option
                            value="<?= (int)
                                $ramo['id_ramo']
                            ?>"
                            <?= (
                                $equipamento_editar &&
                                (int)$equipamento_editar['id_ramo']
                                ===
                                (int)$ramo['id_ramo']
                            )
                                ? 'selected'
                                : ''
                            ?>
                        >

                            <?= e($ramo['nome']) ?>

                            <?= !empty($ramo['sigla'])
                                ? ' - ' .
                                  e($ramo['sigla'])
                                : ''
                            ?>

                        </option>

                    <?php endforeach; ?>

                </select>

            </div>


            <div class="campo">

                <label>
                    Unidade
                </label>

                <select
                    name="id_unidade"
                    id="id_unidade"
                >

                    <option value="">
                        Selecionar unidade
                    </option>

                    <?php foreach ($unidades as $unidade): ?>

                        <option
                            value="<?= (int)
                                $unidade['id_unidade']
                            ?>"
                            data-ramo="<?= (int)
                                $unidade['id_ramo']
                            ?>"
                            <?= (
                                $equipamento_editar &&
                                (int)$equipamento_editar['id_unidade']
                                ===
                                (int)$unidade['id_unidade']
                            )
                                ? 'selected'
                                : ''
                            ?>
                        >

                            <?= e($unidade['nome']) ?>

                            <?= !empty($unidade['sigla'])
                                ? ' - ' .
                                  e($unidade['sigla'])
                                : ''
                            ?>

                        </option>

                    <?php endforeach; ?>

                </select>

            </div>


            <div class="campo">

                <label>
                    Tipo de equipamento
                </label>

                <select
                    name="id_tipo_equipamento"
                >

                    <option value="">
                        Selecionar tipo
                    </option>

                    <?php foreach (
                        $tipos_equipamento
                        as $tipo
                    ): ?>

                        <option
                            value="<?= (int)
                                $tipo['id_tipo_equipamento']
                            ?>"
                            <?= (
                                $equipamento_editar &&
                                (int)$equipamento_editar[
                                    'id_tipo_equipamento'
                                ]
                                ===
                                (int)$tipo[
                                    'id_tipo_equipamento'
                                ]
                            )
                                ? 'selected'
                                : ''
                            ?>
                        >

                            <?= e($tipo['nome']) ?>

                        </option>

                    <?php endforeach; ?>

                </select>

            </div>


            <div class="campo">

                <label>
                    Estado do equipamento
                </label>

                <select
                    name="id_estado_equipamento"
                >

                    <option value="">
                        Selecionar estado
                    </option>

                    <?php foreach (
                        $estados_equipamento
                        as $estado
                    ): ?>

                        <option
                            value="<?= (int)
                                $estado[
                                    'id_estado_equipamento'
                                ]
                            ?>"
                            <?= (
                                $equipamento_editar &&
                                (int)$equipamento_editar[
                                    'id_estado_equipamento'
                                ]
                                ===
                                (int)$estado[
                                    'id_estado_equipamento'
                                ]
                            )
                                ? 'selected'
                                : ''
                            ?>
                        >

                            <?= e($estado['nome']) ?>

                        </option>

                    <?php endforeach; ?>

                </select>

            </div>


            <div class="campo campo-largo">

                <label>
                    Designação
                    <span class="obrigatorio">*</span>
                </label>

                <input
                    type="text"
                    name="designacao"
                    maxlength="200"
                    required
                    value="<?= e(
                        $equipamento_editar[
                            'designacao'
                        ] ?? ''
                    ) ?>"
                    placeholder="Ex.: Computador portátil Dell"
                >

            </div>


            <div class="campo">

                <label>
                    Marca
                </label>

                <input
                    type="text"
                    name="marca"
                    maxlength="100"
                    value="<?= e(
                        $equipamento_editar[
                            'marca'
                        ] ?? ''
                    ) ?>"
                >

            </div>


            <div class="campo">

                <label>
                    Modelo
                </label>

                <input
                    type="text"
                    name="modelo"
                    maxlength="100"
                    value="<?= e(
                        $equipamento_editar[
                            'modelo'
                        ] ?? ''
                    ) ?>"
                >

            </div>


            <div class="campo">

                <label>
                    Número de série
                </label>

                <input
                    type="text"
                    name="numero_serie"
                    maxlength="100"
                    value="<?= e(
                        $equipamento_editar[
                            'numero_serie'
                        ] ?? ''
                    ) ?>"
                >

            </div>


            <div class="campo">

                <label>
                    Número de património
                </label>

                <input
                    type="text"
                    name="numero_patrimonio"
                    maxlength="100"
                    value="<?= e(
                        $equipamento_editar[
                            'numero_patrimonio'
                        ] ?? ''
                    ) ?>"
                >

            </div>


            <div class="campo">

                <label>
                    Data de aquisição
                </label>

                <input
                    type="date"
                    name="data_aquisicao"
                    value="<?= e(
                        $equipamento_editar[
                            'data_aquisicao'
                        ] ?? ''
                    ) ?>"
                >

            </div>


            <div class="campo campo-largo">

                <label>
                    Localização
                </label>

                <input
                    type="text"
                    name="localizacao"
                    maxlength="200"
                    value="<?= e(
                        $equipamento_editar[
                            'localizacao'
                        ] ?? ''
                    ) ?>"
                    placeholder="Ex.: Armazém central, Sala 02..."
                >

            </div>


            <div class="campo campo-completo">

                <label>
                    Descrição
                </label>

                <textarea
                    name="descricao"
                    placeholder="Descrição detalhada do equipamento..."
                ><?= e(
                    $equipamento_editar[
                        'descricao'
                    ] ?? ''
                ) ?></textarea>

            </div>


            <div class="campo campo-completo">

                <label>
                    Observações
                </label>

                <textarea
                    name="observacoes"
                    placeholder="Observações adicionais..."
                ><?= e(
                    $equipamento_editar[
                        'observacoes'
                    ] ?? ''
                ) ?></textarea>

            </div>

        </div>


        <div
            class="acoes"
            style="margin-top:20px;"
        >

            <button
                type="submit"
                class="btn btn-primary"
            >

                <?= $equipamento_editar
                    ? 'Guardar alterações'
                    : 'Cadastrar equipamento'
                ?>

            </button>

            <a
                href="equipamentos.php"
                class="btn btn-secondary"
            >
                Cancelar
            </a>

        </div>

    </form>

</div>

<?php endif; ?>


<!-- ======================================================
     PESQUISA
     ====================================================== -->

<div class="card">

    <form
        method="get"
        class="barra-pesquisa"
    >

        <input
            type="text"
            name="pesquisa"
            value="<?= e($pesquisa) ?>"
            placeholder="Pesquisar por designação, marca, modelo, série, património, ramo, unidade..."
        >

        <button
            type="submit"
            class="btn btn-primary"
        >
            Pesquisar
        </button>

        <?php if ($pesquisa !== ''): ?>

            <a
                href="equipamentos.php"
                class="btn btn-secondary"
            >
                Limpar
            </a>

        <?php endif; ?>

    </form>

</div>


<!-- ======================================================
     LISTAGEM
     ====================================================== -->

<div class="card">

    <div class="total">

        Total de equipamentos encontrados:
        <strong>
            <?= $total_equipamentos ?>
        </strong>

    </div>


    <div class="tabela-container">

        <table>

            <thead>

                <tr>

                    <th>ID</th>
                    <th>Equipamento</th>
                    <th>Tipo</th>
                    <th>Marca / Modelo</th>
                    <th>Nº Série</th>
                    <th>Nº Património</th>
                    <th>Ramo</th>
                    <th>Unidade</th>
                    <th>Estado</th>
                    <th>Localização</th>
                    <th>Ficheiros</th>
                    <th>Ações</th>

                </tr>

            </thead>


            <tbody>

            <?php if (empty($equipamentos)): ?>

                <tr>

                    <td
                        colspan="12"
                        class="sem-registos"
                    >
                        Nenhum equipamento encontrado.
                    </td>

                </tr>

            <?php else: ?>


                <?php foreach (
                    $equipamentos
                    as $equipamento
                ): ?>

                    <?php

                    $id_eq =
                        (int)$equipamento[
                            'id_equipamento'
                        ];

                    $fotos =
                        $fotografias_equipamentos[
                            $id_eq
                        ] ?? [];

                    $docs =
                        $documentos_equipamentos[
                            $id_eq
                        ] ?? [];

                    ?>


                    <tr>


                        <td>
                            <?= $id_eq ?>
                        </td>


                        <td>

                            <strong>
                                <?= e(
                                    $equipamento[
                                        'designacao'
                                    ]
                                ) ?>
                            </strong>

                            <?php if (
                                !empty(
                                    $equipamento[
                                        'descricao'
                                    ]
                                )
                            ): ?>

                                <br>

                                <small
                                    style="color:#6b7280;"
                                >
                                    <?= e(
                                        $equipamento[
                                            'descricao'
                                        ]
                                    ) ?>
                                </small>

                            <?php endif; ?>

                        </td>


                        <td>

                            <?= !empty(
                                $equipamento[
                                    'tipo_nome'
                                ]
                            )
                                ? e(
                                    $equipamento[
                                        'tipo_nome'
                                    ]
                                )
                                : '—'
                            ?>

                        </td>


                        <td>

                            <?php

                            $marca_modelo =
                                trim(
                                    (
                                        $equipamento[
                                            'marca'
                                        ] ?? ''
                                    ) .
                                    ' ' .
                                    (
                                        $equipamento[
                                            'modelo'
                                        ] ?? ''
                                    )
                                );

                            ?>

                            <?= $marca_modelo !== ''
                                ? e($marca_modelo)
                                : '—'
                            ?>

                        </td>


                        <td>

                            <?= !empty(
                                $equipamento[
                                    'numero_serie'
                                ]
                            )
                                ? e(
                                    $equipamento[
                                        'numero_serie'
                                    ]
                                )
                                : '—'
                            ?>

                        </td>


                        <td>

                            <?= !empty(
                                $equipamento[
                                    'numero_patrimonio'
                                ]
                            )
                                ? e(
                                    $equipamento[
                                        'numero_patrimonio'
                                    ]
                                )
                                : '—'
                            ?>

                        </td>


                        <td>

                            <?= e(
                                $equipamento[
                                    'ramo_nome'
                                ]
                            ) ?>

                            <?php if (
                                !empty(
                                    $equipamento[
                                        'ramo_sigla'
                                    ]
                                )
                            ): ?>

                                <br>

                                <span class="badge">
                                    <?= e(
                                        $equipamento[
                                            'ramo_sigla'
                                        ]
                                    ) ?>
                                </span>

                            <?php endif; ?>

                        </td>


                        <td>

                            <?php if (
                                !empty(
                                    $equipamento[
                                        'unidade_nome'
                                    ]
                                )
                            ): ?>

                                <?= e(
                                    $equipamento[
                                        'unidade_nome'
                                    ]
                                ) ?>

                                <?php if (
                                    !empty(
                                        $equipamento[
                                            'unidade_sigla'
                                        ]
                                    )
                                ): ?>

                                    <br>

                                    <span class="badge">
                                        <?= e(
                                            $equipamento[
                                                'unidade_sigla'
                                            ]
                                        ) ?>
                                    </span>

                                <?php endif; ?>

                            <?php else: ?>

                                —

                            <?php endif; ?>

                        </td>


                        <td>

                            <?php if (
                                !empty(
                                    $equipamento[
                                        'estado_nome'
                                    ]
                                )
                            ): ?>

                                <span class="badge">
                                    <?= e(
                                        $equipamento[
                                            'estado_nome'
                                        ]
                                    ) ?>
                                </span>

                            <?php else: ?>

                                —

                            <?php endif; ?>

                        </td>


                        <td>

                            <?= !empty(
                                $equipamento[
                                    'localizacao'
                                ]
                            )
                                ? e(
                                    $equipamento[
                                        'localizacao'
                                    ]
                                )
                                : '—'
                            ?>

                        </td>


                        <!-- ==================================================
                             FICHEIROS
                             ================================================== -->

                        <td class="celula-ficheiros">


                            <!-- FOTOGRAFIAS -->

                            <?php if (!empty($fotos)): ?>

                                <div>

                                    <strong>
                                        📷 Fotografias
                                    </strong>


                                    <?php foreach (
                                        $fotos
                                        as $foto
                                    ): ?>

                                        <div
                                            class="ficheiro-item"
                                        >

                                            <span
                                                class="ficheiro-nome"
                                                title="<?= e(
                                                    $foto[
                                                        'nome_ficheiro'
                                                    ]
                                                ) ?>"
                                            >

                                                📷
                                                <?= e(
                                                    $foto[
                                                        'nome_ficheiro'
                                                    ]
                                                ) ?>

                                            </span>


                                            <div
                                                class="ficheiro-acoes"
                                            >

                                                <?php if (
                                                    !empty(
                                                        $foto[
                                                            'principal'
                                                        ]
                                                    )
                                                ): ?>

                                                    <span
                                                        class="badge badge-principal"
                                                    >
                                                        Principal
                                                    </span>

                                                <?php else: ?>

                                                    <form
                                                        method="post"
                                                        style="display:inline;"
                                                    >

                                                        <input
                                                            type="hidden"
                                                            name="acao"
                                                            value="foto_principal"
                                                        >

                                                        <input
                                                            type="hidden"
                                                            name="id_fotografia"
                                                            value="<?= (int)
                                                                $foto[
                                                                    'id_fotografia'
                                                                ]
                                                            ?>"
                                                        >

                                                        <input
                                                            type="hidden"
                                                            name="id_equipamento"
                                                            value="<?= $id_eq ?>"
                                                        >

                                                        <button
                                                            type="submit"
                                                            class="btn btn-warning btn-small"
                                                        >
                                                            Principal
                                                        </button>

                                                    </form>

                                                <?php endif; ?>


                                                <a
                                                    href="equipamentos.php?download_foto=<?= (int)
                                                        $foto[
                                                            'id_fotografia'
                                                        ]
                                                    ?>"
                                                    class="btn btn-success btn-small"
                                                >
                                                    Baixar
                                                </a>


                                                <form
                                                    method="post"
                                                    style="display:inline;"
                                                    onsubmit="return confirm('Eliminar esta fotografia?');"
                                                >

                                                    <input
                                                        type="hidden"
                                                        name="acao"
                                                        value="eliminar_foto"
                                                    >

                                                    <input
                                                        type="hidden"
                                                        name="id_fotografia"
                                                        value="<?= (int)
                                                            $foto[
                                                                'id_fotografia'
                                                            ]
                                                        ?>"
                                                    >

                                                    <button
                                                        type="submit"
                                                        class="btn btn-danger btn-small"
                                                    >
                                                        Eliminar
                                                    </button>

                                                </form>

                                            </div>

                                        </div>

                                    <?php endforeach; ?>

                                </div>

                            <?php endif; ?>


                            <!-- DOCUMENTOS -->

                            <?php if (!empty($docs)): ?>

                                <div
                                    style="margin-top:12px;"
                                >

                                    <strong>
                                        📄 Documentos
                                    </strong>


                                    <?php foreach (
                                        $docs
                                        as $documento
                                    ): ?>

                                        <div
                                            class="ficheiro-item"
                                        >

                                            <span
                                                class="ficheiro-nome"
                                                title="<?= e(
                                                    $documento[
                                                        'nome_ficheiro'
                                                    ]
                                                ) ?>"
                                            >

                                                📄

                                                <?= e(
                                                    $documento[
                                                        'titulo'
                                                    ]
                                                    ?: $documento[
                                                        'nome_ficheiro'
                                                    ]
                                                ) ?>

                                            </span>


                                            <?php if (
                                                !empty(
                                                    $documento[
                                                        'tipo_documento'
                                                    ]
                                                )
                                            ): ?>

                                                <small
                                                    style="
                                                        color:#6b7280;
                                                    "
                                                >
                                                    <?= e(
                                                        $documento[
                                                            'tipo_documento'
                                                        ]
                                                    ) ?>
                                                </small>

                                            <?php endif; ?>


                                            <div
                                                class="ficheiro-acoes"
                                            >

                                                <a
                                                    href="equipamentos.php?download_documento=<?= (int)
                                                        $documento[
                                                            'id_documento'
                                                        ]
                                                    ?>"
                                                    class="btn btn-success btn-small"
                                                >
                                                    Baixar
                                                </a>


                                                <form
                                                    method="post"
                                                    style="display:inline;"
                                                    onsubmit="return confirm('Eliminar este documento?');"
                                                >

                                                    <input
                                                        type="hidden"
                                                        name="acao"
                                                        value="eliminar_documento"
                                                    >

                                                    <input
                                                        type="hidden"
                                                        name="id_documento"
                                                        value="<?= (int)
                                                            $documento[
                                                                'id_documento'
                                                            ]
                                                        ?>"
                                                    >

                                                    <button
                                                        type="submit"
                                                        class="btn btn-danger btn-small"
                                                    >
                                                        Eliminar
                                                    </button>

                                                </form>

                                            </div>

                                        </div>

                                    <?php endforeach; ?>

                                </div>

                            <?php endif; ?>


                            <?php if (
                                empty($fotos) &&
                                empty($docs)
                            ): ?>

                                <span
                                    style="
                                        color:#6b7280;
                                        font-size:12px;
                                    "
                                >
                                    Nenhum ficheiro
                                </span>

                            <?php endif; ?>


                            <!-- ==================================================
                                 IMPORTAR FOTOGRAFIA
                                 ================================================== -->

                            <div class="form-ficheiro">

                                <strong>
                                    Importar fotografia
                                </strong>

                                <form
                                    method="post"
                                    enctype="multipart/form-data"
                                >

                                    <input
                                        type="hidden"
                                        name="acao"
                                        value="upload_foto"
                                    >

                                    <input
                                        type="hidden"
                                        name="id_equipamento"
                                        value="<?= $id_eq ?>"
                                    >

                                    <input
                                        type="file"
                                        name="fotografia"
                                        accept=".jpg,.jpeg,.png,.webp"
                                        required
                                    >

                                    <input
                                        type="text"
                                        name="descricao_foto"
                                        placeholder="Descrição da fotografia"
                                        maxlength="500"
                                    >

                                    <label>

                                        <input
                                            type="checkbox"
                                            name="principal"
                                            value="1"
                                        >

                                        Definir como fotografia principal

                                    </label>

                                    <button
                                        type="submit"
                                        class="btn btn-primary btn-small"
                                    >
                                        Importar foto
                                    </button>

                                </form>

                            </div>


                            <!-- ==================================================
                                 IMPORTAR DOCUMENTO
                                 ================================================== -->

                            <div
                                class="form-ficheiro"
                                style="margin-top:10px;"
                            >

                                <strong>
                                    Importar documento
                                </strong>

                                <form
                                    method="post"
                                    enctype="multipart/form-data"
                                >

                                    <input
                                        type="hidden"
                                        name="acao"
                                        value="upload_documento"
                                    >

                                    <input
                                        type="hidden"
                                        name="id_equipamento"
                                        value="<?= $id_eq ?>"
                                    >


                                    <input
                                        type="text"
                                        name="titulo"
                                        placeholder="Título do documento"
                                        maxlength="200"
                                        required
                                    >


                                    <select
                                        name="id_tipo_documento"
                                        required
                                    >

                                        <option value="">
                                            Tipo de documento
                                        </option>

                                        <?php foreach (
                                            $tipos_documento
                                            as $tipo_doc
                                        ): ?>

                                            <option
                                                value="<?= (int)
                                                    $tipo_doc[
                                                        'id_tipo_documento'
                                                    ]
                                                ?>"
                                            >
                                                <?= e(
                                                    $tipo_doc['nome']
                                                ) ?>
                                            </option>

                                        <?php endforeach; ?>

                                    </select>


                                    <input
                                        type="file"
                                        name="documento"
                                        accept=".pdf,.doc,.docx,.xls,.xlsx,.jpg,.jpeg,.png"
                                        required
                                    >


                                    <input
                                        type="date"
                                        name="data_documento"
                                        title="Data do documento"
                                    >


                                    <input
                                        type="date"
                                        name="data_validade"
                                        title="Data de validade"
                                    >


                                    <textarea
                                        name="descricao_documento"
                                        placeholder="Descrição do documento"
                                        style="min-height:60px;"
                                    ></textarea>


                                    <button
                                        type="submit"
                                        class="btn btn-primary btn-small"
                                    >
                                        Importar documento
                                    </button>

                                </form>

                            </div>

                        </td>


                        <!-- ==================================================
                             AÇÕES
                             ================================================== -->

                        <td>

                            <div class="acoes">

                                <a
                                    href="equipamentos.php?editar=<?= $id_eq ?>"
                                    class="btn btn-warning btn-small"
                                >
                                    Editar
                                </a>


                                <form
                                    method="post"
                                    style="display:inline;"
                                    onsubmit="return confirmarEliminacao();"
                                >

                                    <input
                                        type="hidden"
                                        name="acao"
                                        value="eliminar"
                                    >

                                    <input
                                        type="hidden"
                                        name="id_equipamento"
                                        value="<?= $id_eq ?>"
                                    >

                                    <button
                                        type="submit"
                                        class="btn btn-danger btn-small"
                                    >
                                        Eliminar
                                    </button>

                                </form>

                            </div>

                        </td>

                    </tr>

                <?php endforeach; ?>

            <?php endif; ?>

            </tbody>

        </table>

    </div>

</div>

</div>


<script>


// ============================================================
// CONFIRMAR ELIMINAÇÃO
// ============================================================

function confirmarEliminacao()
{
    return confirm(
        'Tem certeza que deseja eliminar este equipamento?\n\n' +
        'Se existirem fotografias ou documentos associados, ' +
        'a eliminação poderá ser bloqueada pela base de dados.'
    );
}


// ============================================================
// FILTRAR UNIDADES PELO RAMO
// ============================================================

const ramoSelect =
    document.getElementById('id_ramo');

const unidadeSelect =
    document.getElementById('id_unidade');


function atualizarUnidades()
{
    if (
        !ramoSelect ||
        !unidadeSelect
    ) {
        return;
    }

    const ramoSelecionado =
        ramoSelect.value;

    const opcoes =
        unidadeSelect.querySelectorAll(
            'option'
        );

    opcoes.forEach(
        function(opcao, indice)
        {

            if (indice === 0) {

                opcao.hidden = false;
                return;
            }

            const idRamo =
                opcao.getAttribute(
                    'data-ramo'
                );

            if (
                ramoSelecionado === '' ||
                idRamo === ramoSelecionado
            ) {

                opcao.hidden = false;

            } else {

                opcao.hidden = true;

                if (opcao.selected) {
                    unidadeSelect.value = '';
                }
            }
        }
    );
}


if (ramoSelect) {

    ramoSelect.addEventListener(
        'change',
        atualizarUnidades
    );

    atualizarUnidades();
}

</script>
</div>

<?php require __DIR__ . '/../layout/footer.php'; ?>
