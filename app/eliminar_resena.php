<?php
require 'includes/db.php';

if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] != 'admin') {
    header('Location: index.php');
    exit;
}

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($id > 0) {
    $stmt = $pdo->prepare("DELETE FROM resena WHERE id_resena = ?");
    $stmt->execute([$id]);
}

header("Location: " . ($_SERVER['HTTP_REFERER'] ?? 'index.php'));
exit;
?>