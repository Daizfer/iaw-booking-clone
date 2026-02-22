<?php 
require 'includes/db.php'; 
require 'includes/header.php'; 

if (!isset($_SESSION['user_id']) || ($_SESSION['user_role'] != 'dueño' && $_SESSION['user_role'] != 'admin')) {
    header("Location: index.php");
    exit;
}

$id_hotel = $_GET['id'] ?? 0;
$id_usuario = $_SESSION['user_id'];
$rol = $_SESSION['user_role'];

if ($rol == 'admin') {
    $stmt = $pdo->prepare("SELECT * FROM hotel WHERE id_hotel = ?");
    $stmt->execute([$id_hotel]);
} else {
    $stmt = $pdo->prepare("SELECT * FROM hotel WHERE id_hotel = ? AND id_usuario = ?");
    $stmt->execute([$id_hotel, $id_usuario]);
}

$hotel = $stmt->fetch();

if (!$hotel) die("<div class='alert alert-danger container mt-5'>Acceso denegado o hotel no encontrado.</div>");

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['responder_resena'])) {
    $id_resena = $_POST['id_resena'];
    $respuesta = $_POST['respuesta'];
    
    $stmtUpdate = $pdo->prepare("UPDATE resena SET respuesta_owner = ? WHERE id_resena = ?");
    $stmtUpdate->execute([$respuesta, $id_resena]);
    echo "<div class='alert alert-success'>Respuesta guardada correctamente.</div>";
}

$sqlReservas = "SELECT r.*, u.nombre as cliente, u.email, h.numero_puerta 
                FROM reserva r 
                JOIN habitacion h ON r.id_habitacion = h.id_habitacion
                JOIN usuario u ON r.id_usuario = u.id_usuario
                WHERE h.id_hotel = ? 
                ORDER BY r.fecha_inicio DESC";
$stmtRes = $pdo->prepare($sqlReservas);
$stmtRes->execute([$id_hotel]);
$reservas = $stmtRes->fetchAll();

$sqlReviews = "SELECT r.*, u.nombre as autor FROM resena r 
               JOIN usuario u ON r.id_usuario = u.id_usuario 
               WHERE r.id_hotel = ? ORDER BY r.fecha DESC";
$stmtRev = $pdo->prepare($sqlReviews);
$stmtRev->execute([$id_hotel]);
$reviews = $stmtRev->fetchAll();
?>

<div class="container mt-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="text-dark">
            <?php if($rol == 'admin') echo "<span class='badge bg-danger'>Modo Admin</span>"; ?>
            Gestión: <?php echo htmlspecialchars($hotel['nombre']); ?>
        </h2>
        <a href="mis_hoteles.php" class="btn btn-secondary">Volver al listado</a>
    </div>

    <ul class="nav nav-tabs" id="myTab" role="tablist">
        <li class="nav-item">
            <button class="nav-link active text-dark" id="reservas-tab" data-bs-toggle="tab" data-bs-target="#reservas" type="button">Reservas Recibidas</button>
        </li>
        <li class="nav-item">
            <button class="nav-link text-dark" id="resenas-tab" data-bs-toggle="tab" data-bs-target="#resenas" type="button">Opiniones y Respuestas</button>
        </li>
    </ul>

    <div class="tab-content mt-4" id="myTabContent">
        <div class="tab-pane fade show active" id="reservas">
            <?php if(count($reservas) == 0): ?>
                <div class="alert alert-info">Aún no hay reservas en este hotel.</div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-hover border">
                        <thead class="table-dark">
                            <tr>
                                <th>Cliente</th>
                                <th>Habitación</th>
                                <th>Entrada</th>
                                <th>Salida</th>
                                <th>Total</th>
                                <th>Estado</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($reservas as $res): ?>
                            <tr>
                                <td>
                                    <strong><?php echo htmlspecialchars($res['cliente']); ?></strong><br>
                                    <small class="text-muted"><?php echo htmlspecialchars($res['email']); ?></small>
                                </td>
                                <td><?php echo htmlspecialchars($res['numero_puerta']); ?></td>
                                <td><?php echo date('d/m/Y', strtotime($res['fecha_inicio'])); ?></td>
                                <td><?php echo date('d/m/Y', strtotime($res['fecha_fin'])); ?></td>
                                <td class="fw-bold text-success">$<?php echo $res['precio_total']; ?></td>
                                <td>
                                    <?php 
                                        $hoy = date('Y-m-d');
                                        if($res['fecha_fin'] < $hoy) echo '<span class="badge bg-secondary">Finalizada</span>';
                                        elseif($res['fecha_inicio'] <= $hoy && $res['fecha_fin'] >= $hoy) echo '<span class="badge bg-success">En curso</span>';
                                        else echo '<span class="badge bg-primary">Próxima</span>';
                                    ?>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>

        <div class="tab-pane fade" id="resenas">
            <?php if(count($reviews) == 0): ?>
                <div class="alert alert-info">No hay opiniones todavía.</div>
            <?php else: ?>
                <?php foreach($reviews as $rev): ?>
                    <div class="card mb-3 shadow-sm">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <h5 class="card-title text-dark"><?php echo htmlspecialchars($rev['autor']); ?></h5>
                                <span class="badge bg-warning text-dark">★ <?php echo $rev['puntuacion']; ?>/5</span>
                            </div>
                            <p class="card-text mt-2">"<?php echo htmlspecialchars($rev['comentario']); ?>"</p>
                            
                            <div class="mt-3 p-3 bg-light rounded border">
                                <?php if($rev['respuesta_owner']): ?>
                                    <strong class="text-primary">Respuesta:</strong>
                                    <p class="mb-0 mt-1 fst-italic"><?php echo htmlspecialchars($rev['respuesta_owner']); ?></p>
                                    <button class="btn btn-link btn-sm p-0 mt-2" onclick="document.getElementById('form_reply_<?php echo $rev['id_resena']; ?>').style.display='block'">Editar</button>
                                <?php else: ?>
                                    <strong class="text-muted">Sin respuesta.</strong>
                                    <button class="btn btn-outline-primary btn-sm mt-2 d-block" onclick="document.getElementById('form_reply_<?php echo $rev['id_resena']; ?>').style.display='block'">Responder</button>
                                <?php endif; ?>

                                <?php if(isset($_SESSION['user_role']) && $_SESSION['user_role'] == 'admin'): ?>
                                    <a href="eliminar_resena.php?id=<?php echo $rev['id_resena']; ?>" class="btn btn-danger btn-sm ms-2" onclick="return confirm('¿Eliminar reseña? Esta acción es irreversible.')">Eliminar</a>
                                <?php endif; ?>

                                <form method="POST" id="form_reply_<?php echo $rev['id_resena']; ?>" style="display: none;" class="mt-3">
                                    <input type="hidden" name="id_resena" value="<?php echo $rev['id_resena']; ?>">
                                    <textarea name="respuesta" class="form-control mb-2" rows="2"><?php echo htmlspecialchars($rev['respuesta_owner'] ?? ''); ?></textarea>
                                    <button type="submit" name="responder_resena" class="btn btn-primary btn-sm">Guardar</button>
                                </form>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</div>
<?php require 'includes/footer.php'; ?>