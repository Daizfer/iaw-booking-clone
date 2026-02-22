<?php
require 'includes/db.php';

if (!isset($_SESSION['user_id']) || ($_SESSION['user_role'] != 'dueño' && $_SESSION['user_role'] != 'admin')) {
    header("Location: index.php");
    exit;
}

$id_hotel = $_GET['id'] ?? 0;
$id_usuario = $_SESSION['user_id'];
$rol = $_SESSION['user_role'];

if ($rol != 'admin') {
    $stmt = $pdo->prepare("SELECT id_hotel FROM hotel WHERE id_hotel = ? AND id_usuario = ?");
    $stmt->execute([$id_hotel, $id_usuario]);
    if (!$stmt->fetch()) {
        die("<div class='alert alert-danger'>No tienes permiso para borrar este hotel.</div>");
    }
}

try {
    $pdo->beginTransaction();

    $sqlDeleteReservas = "DELETE FROM reserva 
                          WHERE id_habitacion IN (SELECT id_habitacion FROM habitacion WHERE id_hotel = ?)";
    $pdo->prepare($sqlDeleteReservas)->execute([$id_hotel]);

    $pdo->prepare("DELETE FROM resena WHERE id_hotel = ?")->execute([$id_hotel]);

    $pdo->prepare("DELETE FROM hotel_servicio WHERE id_hotel = ?")->execute([$id_hotel]);

    $pdo->prepare("DELETE FROM habitacion WHERE id_hotel = ?")->execute([$id_hotel]);

    $pdo->prepare("DELETE FROM hotel WHERE id_hotel = ?")->execute([$id_hotel]);

    $pdo->commit();

    header("Location: mis_hoteles.php?msg=borrado");

} catch (Exception $e) {
    $pdo->rollBack();
    die("Error crítico al borrar: " . $e->getMessage());
}
?>