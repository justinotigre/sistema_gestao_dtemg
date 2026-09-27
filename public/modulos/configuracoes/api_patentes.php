<?php
require_once __DIR__ . '/../../../App/auth.php';
require_login();
header('Content-Type: application/json; charset=utf-8');

$pdo = db();

$idRamo = (int)($_GET['id_ramo'] ?? 0);

if (!$idRamo) {
    echo json_encode([]);
    exit;
}

$stmt = $pdo->prepare("
    SELECT
        id_patente,
        nome,
        sigla,
        ordem
    FROM patentes
    WHERE id_ramo = ?
    AND estado = 'ATIVO'
    ORDER BY ordem ASC, nome ASC
");

$stmt->execute([$idRamo]);

echo json_encode(
    $stmt->fetchAll(PDO::FETCH_ASSOC),
    JSON_UNESCAPED_UNICODE
);