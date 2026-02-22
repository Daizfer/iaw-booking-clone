<?php 
require 'includes/db.php'; 
require 'includes/header.php'; 

if (!isset($_SESSION['user_id'])) header("Location: login.php");

$id_user = $_SESSION['user_id'];
$sql = "SELECT r.*, h.nombre as hotel, hab.numero_puerta 
    FROM reserva r 
    JOIN habitacion hab ON r.id_habitacion = hab.id_habitacion
    JOIN hotel h ON hab.id_hotel = h.id_hotel
    WHERE r.id_usuario = ? 
    ORDER BY r.fecha_inicio DESC";

$stmt = $pdo->prepare($sql);
$stmt->execute([$id_user]);
$reservas = $stmt->fetchAll();
?>

<div class="container mt-5">
    <h2>Mis Reservas</h2>
    <?php if(isset($_GET['status'])) echo "<div class='alert alert-success'>¡Reserva confirmada con éxito!</div>"; ?>
    
    <div class="table-responsive mt-4">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Hotel</th>
                    <th>Habitación</th>
                    <th>Entrada</th>
                    <th>Salida</th>
                    <th>Total</th>
                    <th>Estado</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($reservas as $r): ?>
                <tr>
                    <td><?php echo htmlspecialchars($r['hotel']); ?></td>
                    <td><?php echo htmlspecialchars($r['numero_puerta']); ?></td>
                    <td><?php echo $r['fecha_inicio']; ?></td>
                    <td><?php echo $r['fecha_fin']; ?></td>
                    <td>$<?php echo $r['precio_total']; ?></td>
                    <td>
                        <span class="badge bg-<?php echo $r['estado'] == 'confirmada' ? 'success' : 'warning'; ?>">
                            <?php echo ucfirst($r['estado']); ?>
                        </span>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
<?php require 'includes/footer.php'; ?>