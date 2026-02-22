<?php
require 'includes/db.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_usuario = $_SESSION['user_id'];
    $id_habitacion = $_POST['id_habitacion'];
    $inicio = $_POST['fecha_inicio'];
    $fin = $_POST['fecha_fin'];

    if (empty($inicio) || empty($fin)) {
        require 'includes/header.php';
        echo "<div class='container mt-5'><div class='alert alert-warning'>Por favor, selecciona fecha de llegada y salida. <a href='javascript:history.back()'>Volver</a></div></div>";
        require 'includes/footer.php';
        exit;
    }

    if ($inicio >= $fin) {
        require 'includes/header.php';
        echo "<div class='container mt-5'><div class='alert alert-danger'>Error: La fecha de salida debe ser posterior a la de entrada. <a href='javascript:history.back()'>Volver</a></div></div>";
        require 'includes/footer.php';
        exit;
    }

    $sqlCheck = "SELECT * FROM reserva 
                 WHERE id_habitacion = ? 
                 AND estado = 'confirmada' 
                 AND (fecha_inicio < ? AND fecha_fin > ?)";

    $stmtCheck = $pdo->prepare($sqlCheck);
    $stmtCheck->execute([$id_habitacion, $fin, $inicio]);

    if ($stmtCheck->rowCount() > 0) {
        require 'includes/header.php';
        echo "<div class='container mt-5'>
                <div class='alert alert-danger text-center'>
                    <h3>Habitación Ocupada</h3>
                    <p>Lo sentimos, ya existe una reserva para esas fechas en esta habitación.</p>
                    <p>Por favor, elige otras fechas u otra habitación.</p>
                    <a href='javascript:history.back()' class='btn btn-secondary'>Volver e intentar de nuevo</a>
                </div>
              </div>";
        require 'includes/footer.php';
        exit;
    }

    $stmt = $pdo->prepare("SELECT precio_noche FROM habitacion WHERE id_habitacion = ?");
    $stmt->execute([$id_habitacion]);
    $hab = $stmt->fetch();

    if ($hab) {
        $fecha1 = new DateTime($inicio);
        $fecha2 = new DateTime($fin);
        $dias = $fecha1->diff($fecha2)->days;
        $total = $hab['precio_noche'] * $dias;

        $sql = "INSERT INTO reserva (id_usuario, id_habitacion, fecha_inicio, fecha_fin, precio_total, estado) 
                VALUES (?, ?, ?, ?, ?, 'confirmada')";
        $stmtInsert = $pdo->prepare($sql);
        
        if($stmtInsert->execute([$id_usuario, $id_habitacion, $inicio, $fin, $total])) {
            header("Location: mis_reservas.php?status=success");
        } else {
            echo "Error al guardar en base de datos.";
        }
    }
}
?>