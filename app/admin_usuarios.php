<?php
require 'includes/db.php';
require 'includes/header.php';

if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] != 'admin') {
    echo "<script>window.location='index.php';</script>";
    exit;
}

$mensaje = "";

if (isset($_GET['accion']) && isset($_GET['id'])) {
    $id_usuario = $_GET['id'];
    $accion = $_GET['accion'];
    
    if ($id_usuario == $_SESSION['user_id']) {
        $mensaje = "<div class='alert alert-danger'>No puedes realizar acciones sobre tu propia cuenta.</div>";
    } else {
        if ($accion == 'aprobar') {
            $pdo->prepare("UPDATE usuario SET estado = 'activo' WHERE id_usuario = ?")->execute([$id_usuario]);
            $mensaje = "<div class='alert alert-success'>Cuenta activada correctamente.</div>";
        }
        elseif ($accion == 'bloquear') {
            $pdo->prepare("UPDATE usuario SET estado = 'bloqueado' WHERE id_usuario = ?")->execute([$id_usuario]);
            $mensaje = "<div class='alert alert-danger'>Cuenta bloqueada temporalmente.</div>";
        }
        elseif ($accion == 'borrar') {
            try {
                $pdo->beginTransaction();
                $pdo->prepare("DELETE FROM reserva WHERE id_usuario = ?")->execute([$id_usuario]);
                $pdo->prepare("DELETE FROM resena WHERE id_usuario = ?")->execute([$id_usuario]);
                
                $stmtH = $pdo->prepare("SELECT id_hotel FROM hotel WHERE id_usuario = ?");
                $stmtH->execute([$id_usuario]);
                $hoteles = $stmtH->fetchAll(PDO::FETCH_COLUMN);
                foreach($hoteles as $id_hotel) {
                    $pdo->prepare("DELETE FROM reserva WHERE id_habitacion IN (SELECT id_habitacion FROM habitacion WHERE id_hotel = ?)")->execute([$id_hotel]);
                    $pdo->prepare("DELETE FROM resena WHERE id_hotel = ?")->execute([$id_hotel]);
                    $pdo->prepare("DELETE FROM hotel_servicio WHERE id_hotel = ?")->execute([$id_hotel]);
                    $pdo->prepare("DELETE FROM habitacion WHERE id_hotel = ?")->execute([$id_hotel]);
                }
                $pdo->prepare("DELETE FROM hotel WHERE id_usuario = ?")->execute([$id_usuario]);
                $pdo->prepare("DELETE FROM usuario WHERE id_usuario = ?")->execute([$id_usuario]);
                
                $pdo->commit();
                $mensaje = "<div class='alert alert-dark'>Usuario eliminado del sistema.</div>";
            } catch (Exception $e) {
                $pdo->rollBack();
                $mensaje = "<div class='alert alert-danger'>Error: " . $e->getMessage() . "</div>";
            }
        }
    }
}

$sql = "SELECT u.*, 
        (SELECT COUNT(*) FROM hotel h WHERE h.id_usuario = u.id_usuario) as num_hoteles,
        (SELECT COUNT(*) FROM reserva r WHERE r.id_usuario = u.id_usuario) as num_reservas
        FROM usuario u 
        ORDER BY u.rol ASC, u.nombre ASC";

$stmt = $pdo->query($sql);
$usuarios = $stmt->fetchAll();
?>

<div class="container mt-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Gestión de Usuarios</h2>
        <span class="badge bg-primary fs-5"><?php echo count($usuarios); ?> Registrados</span>
    </div>
    
    <?php echo $mensaje; ?>

    <div class="card shadow border-0">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover table-striped mb-0 align-middle text-center">
                    <thead class="table-dark">
                        <tr>
                            <th class="text-start ps-4">Usuario</th>
                            <th>Rol</th>
                            <th>Datos</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($usuarios as $u): ?>
                        <tr>
                            <td class="text-start ps-4">
                                <strong><?php echo htmlspecialchars($u['nombre']); ?></strong><br>
                                <small class="text-muted"><?php echo htmlspecialchars($u['email']); ?></small>
                            </td>
                            
                            <td>
                                <?php if($u['rol'] == 'admin'): ?>
                                    <span class="badge bg-danger">ADMINISTRADOR</span>
                                <?php elseif($u['rol'] == 'dueño'): ?>
                                    <span class="badge bg-info text-dark">PROPIETARIO</span>
                                <?php else: ?>
                                    <span class="badge bg-success">CLIENTE</span>
                                <?php endif; ?>
                            </td>

                            <td>
                                <?php if($u['rol'] == 'dueño'): ?>
                                    <small class="text-muted">Tiene <b><?php echo $u['num_hoteles']; ?></b> hoteles</small>
                                <?php elseif($u['rol'] == 'cliente'): ?>
                                    <small class="text-muted">Hizo <b><?php echo $u['num_reservas']; ?></b> reservas</small>
                                <?php else: ?>
                                    -
                                <?php endif; ?>
                            </td>

                            <td>
                                <?php 
                                    if($u['estado'] == 'pendiente') echo '<span class="badge bg-warning text-dark">Esperando Aprobación</span>';
                                    elseif($u['estado'] == 'activo') echo '<span class="badge bg-success">Activo</span>';
                                    else echo '<span class="badge bg-secondary">Bloqueado</span>';
                                ?>
                            </td>

                            <td>
                                <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modalUser<?php echo $u['id_usuario']; ?>" title="Ver Detalles">
                                    Info
                                </button>
                            </td>
                        </tr>

                        <div class="modal fade" id="modalUser<?php echo $u['id_usuario']; ?>" tabindex="-1">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header bg-light">
                                        <h5 class="modal-title">Ficha de Usuario</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body text-start">
                                        
                                        <div class="text-center mb-3">
                                            <h4 class="mb-0"><?php echo htmlspecialchars($u['nombre']); ?></h4>
                                            <p class="text-muted"><?php echo htmlspecialchars($u['email']); ?></p>
                                            <span class="badge bg-secondary">ID: #<?php echo $u['id_usuario']; ?></span>
                                        </div>

                                        <ul class="list-group list-group-flush mb-3">
                                            <li class="list-group-item d-flex justify-content-between">
                                                <span>Tipo de cuenta:</span>
                                                <strong><?php echo strtoupper($u['rol']); ?></strong>
                                            </li>
                                            <li class="list-group-item d-flex justify-content-between">
                                                <span>Estado actual:</span>
                                                <strong><?php echo strtoupper($u['estado']); ?></strong>
                                            </li>
                                            
                                            <?php if($u['rol'] == 'dueño'): ?>
                                            <li class="list-group-item d-flex justify-content-between bg-light">
                                                <span>Propiedades registradas:</span>
                                                <strong><?php echo $u['num_hoteles']; ?> Hoteles</strong>
                                            </li>
                                            <?php endif; ?>
                                            
                                            <?php if($u['rol'] == 'cliente'): ?>
                                            <li class="list-group-item d-flex justify-content-between bg-light">
                                                <span>Reservas realizadas:</span>
                                                <strong><?php echo $u['num_reservas']; ?> Viajes</strong>
                                            </li>
                                            <?php endif; ?>
                                        </ul>

                                        <?php if($u['id_usuario'] != $_SESSION['user_id']): ?>
                                            <div class="d-grid gap-2">
                                                
                                                <?php if($u['estado'] != 'activo'): ?>
                                                    <a href="admin_usuarios.php?accion=aprobar&id=<?php echo $u['id_usuario']; ?>" class="btn btn-success fw-bold">Aprobar / Activar Cuenta</a>
                                                <?php endif; ?>

                                                <?php if($u['estado'] == 'activo'): ?>
                                                    <a href="admin_usuarios.php?accion=bloquear&id=<?php echo $u['id_usuario']; ?>" class="btn btn-warning fw-bold">Bloquear Acceso</a>
                                                <?php endif; ?>

                                                <hr>
                                                <a href="admin_usuarios.php?accion=borrar&id=<?php echo $u['id_usuario']; ?>" 
                                                   class="btn btn-outline-danger btn-sm"
                                                   onclick="return confirm('¿ESTÁS SEGURO?\n\nSe borrará al usuario y TODOS sus datos (Hoteles, Reservas, etc).\n\nEsta acción no se puede deshacer.');">
                                                    Eliminar Usuario Definitivamente
                                                </a>
                                            </div>
                                        <?php else: ?>
                                            <div class="alert alert-info text-center mb-0">Esta es tu cuenta de Administrador.</div>
                                        <?php endif; ?>

                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div style="height: 100px;"></div>
<?php require 'includes/footer.php'; ?>