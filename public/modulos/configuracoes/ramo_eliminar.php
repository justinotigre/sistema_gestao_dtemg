<?php
require_once __DIR__ . '/../../../App/auth.php';
require_login();


$pdo = db();

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if (!$id || $id <= 0) {
    header('Location: ramos.php');
    exit;
}

try {

    /*
    |--------------------------------------------------------------------------
    | VERIFICAR SE O RAMO EXISTE
    |--------------------------------------------------------------------------
    */

    $stmt = $pdo->prepare("
        SELECT id_ramo, nome
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
    | ELIMINAR
    |--------------------------------------------------------------------------
    */

    $stmt = $pdo->prepare("
        DELETE FROM ramo
        WHERE id_ramo = :id
        LIMIT 1
    ");

    $stmt->execute([
        ':id' => $id
    ]);


    /*
    |--------------------------------------------------------------------------
    | VOLTAR PARA RAMOS
    |--------------------------------------------------------------------------
    */

    header('Location: ramos.php?eliminado=1');
    exit;

} catch (PDOException $e) {

    header('Location: ramos.php?erro=eliminar');
    exit;
}