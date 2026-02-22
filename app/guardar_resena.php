<?php
require 'includes/db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_SESSION['user_id'])) {
    $id_hotel = $_POST['id_hotel'];
    $id_usuario = $_SESSION['user_id'];
    $puntuacion = intval($_POST['puntuacion']);
    $comentario = trim($_POST['comentario']);

    if ($puntuacion < 1) $puntuacion = 1;
    if ($puntuacion > 5) $puntuacion = 5;

    if (isset($_POST['id_resena']) && !empty($_POST['id_resena'])) {
        $id_resena = intval($_POST['id_resena']);
        if (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin') {
            $stmt = $pdo->prepare("UPDATE resena SET puntuacion = ?, comentario = ?, fecha = NOW() WHERE id_resena = ?");
            $ok = $stmt->execute([$puntuacion, $comentario, $id_resena]);
        } else {
            $stmt = $pdo->prepare("UPDATE resena SET puntuacion = ?, comentario = ?, fecha = NOW() WHERE id_resena = ? AND id_usuario = ?");
            $ok = $stmt->execute([$puntuacion, $comentario, $id_resena, $id_usuario]);
        }

        if ($ok) {
            header("Location: hotel.php?id=$id_hotel#resenas");
            exit;
        } else {
            echo "Error al actualizar reseña";
            exit;
        }
    }

    $sql = "INSERT INTO resena (id_hotel, id_usuario, puntuacion, comentario, fecha) VALUES (?, ?, ?, ?, NOW())";
    $stmt = $pdo->prepare($sql);
    
    if ($stmt->execute([$id_hotel, $id_usuario, $puntuacion, $comentario])) {
        header("Location: hotel.php?id=$id_hotel#resenas");
    } else {
        echo "Error al guardar reseña";
    }
} else {
    header("Location: index.php");
}
?>