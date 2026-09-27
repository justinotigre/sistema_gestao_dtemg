<?php
require_once __DIR__ . '/../../../App/auth.php';
require_login();


$pdo = db();

$id = (int)($_GET['id'] ?? 0);
$estado = $_GET['estado'] ?? '';

if (
    $id <= 0 ||
    !in_array($estado, ['ATIVO', 'INATIVO'], true)
) {
    header('Location: ramos.php');
    exit;
}

$stmt = $pdo->prepare("
    UPDATE ramo
    SET estado = ?
    WHERE id_ramo = ?
");

$stmt->execute([
    $estado,
    $id
]);

header('Location: ramos.php?estado=1');
exit;