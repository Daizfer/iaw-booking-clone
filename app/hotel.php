<?php 
require 'includes/db.php'; 
require 'includes/header.php'; 

$id_hotel = $_GET['id'] ?? 0;

$sql = "SELECT h.*, u.estado as estado_dueno 
    FROM hotel h 
    JOIN usuario u ON h.id_usuario = u.id_usuario 
    WHERE h.id_hotel = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$id_hotel]);
$hotel = $stmt->fetch();

if (!$hotel) {
    echo "<div class='container mt-5 alert alert-danger'>Hotel no encontrado. <a href='index.php'>Volver</a></div>";
    require 'includes/footer.php';
    exit;
}

if ($hotel['estado_dueno'] != 'activo' && (!isset($_SESSION['user_role']) || $_SESSION['user_role'] != 'admin')) {
    echo "<div class='container mt-5 alert alert-warning text-center p-5 shadow-sm'>
            <h3>Alojamiento No Disponible</h3>
            <p class='lead'>Este alojamiento está temporalmente desactivado por administración.</p>
            <a href='index.php' class='btn btn-primary mt-3'>Volver al inicio</a>
          </div>";
    require 'includes/footer.php';
    exit;
}

$stmtRoom = $pdo->prepare("SELECT * FROM habitacion WHERE id_hotel = ?");
$stmtRoom->execute([$id_hotel]);
$habitaciones = $stmtRoom->fetchAll();

$sqlResenas = "SELECT r.*, u.nombre as autor FROM resena r 
               JOIN usuario u ON r.id_usuario = u.id_usuario 
               WHERE r.id_hotel = ? ORDER BY r.fecha DESC";
$stmtRes = $pdo->prepare($sqlResenas);
$stmtRes->execute([$id_hotel]);
$resenas = $stmtRes->fetchAll();

$promedio = 0;
if (count($resenas) > 0) {
    $suma = 0;
    foreach($resenas as $r) $suma += $r['puntuacion'];
    $promedio = round($suma / count($resenas), 1);
}

$ruta_imagen = "uploads/" . $hotel['imagen'];
$img_src = ($hotel['imagen'] && file_exists($ruta_imagen)) ? $ruta_imagen : "https://via.placeholder.com/1200x400?text=" . urlencode($hotel['nombre']);
?>

<style>
    .flatpickr-day { background: #e8f5e9; border: 1px solid #c8e6c9; color: #2e7d32; font-weight: bold; }
    .flatpickr-day.flatpickr-disabled, .flatpickr-day.flatpickr-disabled:hover {
        background: #ffcdd2 !important; border-color: #ef9a9a !important; color: #c62828 !important; text-decoration: line-through; opacity: 0.9; cursor: not-allowed;
    }
    .flatpickr-day.selected, .flatpickr-day.startRange, .flatpickr-day.endRange {
        background: #003580 !important; border-color: #003580 !important; color: white !important;
    }
    .flatpickr-day:not(.flatpickr-disabled):hover { background: #4caf50 !important; color: white !important; border-color: #4caf50 !important; }
    .hero-header { position: relative; width: 100%; height: 400px; overflow: hidden; }
    .hero-header img { width: 100%; height: 100%; object-fit: cover; display: block; }
    .hero-header .hero-overlay { position: absolute; inset: 0; background: linear-gradient(to bottom, rgba(0,0,0,0.6), rgba(0,0,0,0.35)); }
    .hero-header .hero-content { position: absolute; bottom: 30px; left: 0; right: 0; z-index: 2; }
    .hero-title { color: #ffffff; text-shadow: 0 4px 18px rgba(0,0,0,0.6); }
    .hero-sub { color: #f1f5f9; }
    .hotel-descripcion-texto { color: #212529; }
    .card-servicios { background-color: #ffffff; color: #212529; }
    .card-servicios .fs-5 { color: #212529; }
    .card-resena .card-text { color: #212529; }
</style>

<div class="hero-header">
    <img src="<?php echo $img_src; ?>" loading="lazy" alt="<?php echo htmlspecialchars($hotel['nombre']); ?>">
    <div class="hero-overlay"></div>
    <div class="container hero-content">
        <h1 class="hero-title display-4 fw-bold"><?php echo htmlspecialchars($hotel['nombre']); ?></h1>
        <div class="d-flex align-items-center gap-3">
            <p class="hero-sub lead mb-0"><?php echo htmlspecialchars($hotel['ciudad']); ?></p>
            <?php if($promedio > 0): ?>
                <span class="badge bg-warning text-dark fs-5">★ <?php echo $promedio; ?> / 5</span>
            <?php endif; ?>
        </div>
    </div>
</div>

<div class="container mt-5">
    <div class="row">
        <div class="col-md-8">
            <h3 class="mb-3 text-info">Sobre este alojamiento</h3>
            
            <div class="mb-4 p-3 rounded-3 shadow-sm" style="background: rgba(59, 130, 246, 0.08); border-left: 5px solid #3b82f6;">
                <p class="mb-0 fs-5 text-dark">
                    <span class="text-primary fw-bold">Dirección:</span>
                    <?php echo htmlspecialchars($hotel['direccion']); ?>
                </p>
            </div>

            <p class="hotel-descripcion-texto"><?php echo nl2br(htmlspecialchars($hotel['descripcion'])); ?></p>
        </div>

        <div class="col-md-4">
            <div class="card card-servicios shadow-sm p-4">
                <h5 class="mb-3 text-warning">Servicios Incluidos</h5>
                <?php
                $sqlServicios = "SELECT s.nombre FROM servicio s JOIN hotel_servicio hs ON s.id_servicio = hs.id_servicio WHERE hs.id_hotel = ?";
                $stmtServ = $pdo->prepare($sqlServicios);
                $stmtServ->execute([$id_hotel]);
                $servicios_hotel = $stmtServ->fetchAll();
                ?>
                <?php if(count($servicios_hotel) > 0): ?>
                    <ul class="list-unstyled">
                        <?php foreach($servicios_hotel as $srv): ?>
                            <li class="mb-2 fs-5"><?php echo htmlspecialchars($srv['nombre']); ?></li>
                        <?php endforeach; ?>
                    </ul>
                <?php else: ?>
                    <p class="text-white-50">Servicios no especificados.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <hr class="my-5 border-secondary">

    <h3 class="mb-4 text-info">Habitaciones Disponibles</h3>
    
    <?php if(count($habitaciones) == 0): ?>
        <div class="alert alert-warning">No hay habitaciones disponibles actualmente.</div>
    <?php else: ?>
    
    <div class="table-responsive shadow-sm border rounded mb-5">
        <table class="table table-hover table-dark align-middle mb-0">
            <thead>
                <tr>
                    <th class="py-3 ps-4">Habitación</th>
                    <th>Detalles</th>
                    <th>Precio</th>
                    <th class="text-end pe-4">Reserva</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($habitaciones as $h): ?>
                
                <?php 
                    $sqlFechas = "SELECT fecha_inicio, fecha_fin FROM reserva 
                                  WHERE id_habitacion = ? AND estado = 'confirmada' AND fecha_fin >= CURDATE()";
                    $stmtFechas = $pdo->prepare($sqlFechas);
                    $stmtFechas->execute([$h['id_habitacion']]);
                    $fechas_db = $stmtFechas->fetchAll(PDO::FETCH_ASSOC);

                    $disable_fechas = [];
                    foreach($fechas_db as $f) {
                        $disable_fechas[] = ['from' => $f['fecha_inicio'], 'to' => $f['fecha_fin']];
                    }
                    $json_fechas = json_encode($disable_fechas);
                ?>

                <tr>
                    <td class="ps-4">
                        <span class="badge bg-primary mb-1"><?php echo htmlspecialchars($h['numero_puerta']); ?></span><br>
                        <strong><?php echo htmlspecialchars($h['tipo']); ?></strong>
                    </td>
                    <td>
                        Capacidad: <?php echo $h['capacidad']; ?> pers.<br>
                        <small class="text-white-50"><?php echo htmlspecialchars($h['descripcion']); ?></small>
                    </td>
                    <td class="text-success fw-bold fs-5">$<?php echo $h['precio_noche']; ?></td>
                    
                    <td class="text-end pe-4" style="width: 350px;">
                        <?php if(isset($_SESSION['user_id'])): ?>
                            <form action="reservar.php" method="POST" class="d-flex flex-column gap-2 align-items-end booking-form">
                                <input type="hidden" name="id_habitacion" value="<?php echo $h['id_habitacion']; ?>">
                                <div class="d-flex gap-2 w-100">
                                    <input type="text" name="fecha_inicio" class="form-control form-control-sm fecha-input" 
                                           placeholder="Llegada" readonly data-blocked='<?php echo $json_fechas; ?>'>
                                    <input type="text" name="fecha_fin" class="form-control form-control-sm fecha-input" 
                                           placeholder="Salida" readonly data-blocked='<?php echo $json_fechas; ?>'>
                                </div>
                                <button type="submit" class="btn btn-warning fw-bold btn-sm w-100 shadow-sm">Reservar Ahora</button>
                            </form>
                        <?php else: ?>
                            <a href="login.php" class="btn btn-outline-primary btn-sm w-100">Inicia sesión para reservar</a>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <?php endif; ?>

    <hr class="my-5 border-secondary" id="resenas">
    <div class="row">
        <div class="col-md-7">
            <h3 class="mb-4 text-info">Opiniones (<?php echo count($resenas); ?>)</h3>
            <?php if(count($resenas) > 0): ?>
                <?php foreach($resenas as $r): ?>
                    <div class="card card-resena mb-3 border-0 shadow-sm">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <h5 class="card-title fw-bold text-info mb-0"><?php echo htmlspecialchars($r['autor']); ?></h5>
                                <span class="badge bg-warning text-dark">★ <?php echo $r['puntuacion']; ?></span>
                            </div>
                            <h6 class="card-subtitle my-2 text-white-50 small"><?php echo date('d/m/Y', strtotime($r['fecha'])); ?></h6>
                            <p class="card-text">"<?php echo htmlspecialchars($r['comentario']); ?>"</p>
                            
                            <?php if(!empty($r['respuesta_owner'])): ?>
                                <div class="mt-3 ms-4 p-3 bg-dark border-start border-4 border-primary rounded">
                                    <p class="mb-0 small">
                                        <strong class="text-primary">Respuesta del alojamiento:</strong><br>
                                        <em class="text-white-50"><?php echo htmlspecialchars($r['respuesta_owner']); ?></em>
                                    </p>
                                </div>
                            <?php endif; ?>

                            <div class="mt-3 d-flex justify-content-end gap-2">
                                <?php if(isset($_SESSION['user_id']) && ( $_SESSION['user_id'] == $r['id_usuario'] || (isset($_SESSION['user_role']) && $_SESSION['user_role'] == 'admin') )): ?>
                                    <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#editResena<?php echo $r['id_resena']; ?>">Editar</button>
                                <?php endif; ?>

                                <?php if(isset($_SESSION['user_role']) && $_SESSION['user_role'] == 'admin'): ?>
                                    <a href="eliminar_resena.php?id=<?php echo $r['id_resena']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('¿Eliminar reseña? Esta acción es irreversible.')">Eliminar</a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                    <div class="modal fade" id="editResena<?php echo $r['id_resena']; ?>" tabindex="-1">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Editar Reseña</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body">
                                    <form method="POST" action="guardar_resena.php">
                                        <input type="hidden" name="id_hotel" value="<?php echo $id_hotel; ?>">
                                        <input type="hidden" name="id_resena" value="<?php echo $r['id_resena']; ?>">
                                        <div class="mb-3">
                                            <label class="form-label">Puntuación</label>
                                            <select name="puntuacion" class="form-select">
                                                <?php for($i=5;$i>=1;$i--): ?>
                                                    <option value="<?php echo $i; ?>" <?php if($r['puntuacion'] == $i) echo 'selected'; ?>><?php echo str_repeat('★',$i); ?></option>
                                                <?php endfor; ?>
                                            </select>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Comentario</label>
                                            <textarea name="comentario" class="form-control" rows="4"><?php echo htmlspecialchars($r['comentario']); ?></textarea>
                                        </div>
                                        <div class="d-grid">
                                            <button class="btn btn-primary">Guardar cambios</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="alert alert-dark border text-center text-white-50">No hay opiniones disponibles aún.</div>
            <?php endif; ?>
        </div>

        <div class="col-md-5">
            <div class="card card-formulario border-0 p-4 sticky-top" style="top: 20px;">
                <h4 class="text-info mb-3">Deja tu opinión</h4>
                <?php if(isset($_SESSION['user_id'])): ?>
                    <form action="guardar_resena.php" method="POST">
                        <input type="hidden" name="id_hotel" value="<?php echo $id_hotel; ?>">
                        <div class="mb-3">
                            <label class="text-white-50 small mb-1">Tu puntuación</label>
                            <select name="puntuacion" class="form-select">
                                <option value="5">5 Estrellas - Excelente</option>
                                <option value="4">4 Estrellas - Muy bueno</option>
                                <option value="3">3 Estrellas - Normal</option>
                                <option value="2">2 Estrellas - Regular</option>
                                <option value="1">1 Estrella - Malo</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <textarea name="comentario" class="form-control" rows="3" placeholder="Cuéntanos tu experiencia..." required></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary w-100 fw-bold">Publicar opinión</button>
                    </form>
                <?php else: ?>
                    <a href="login.php" class="btn btn-outline-primary w-100">Inicia sesión para opinar</a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/flatpickr" async></script>
<script src="https://npmcdn.com/flatpickr/dist/l10n/es.js" async></script>
<script>
    function initFlatpickr() {
        if (typeof flatpickr === 'undefined') {
            setTimeout(initFlatpickr, 50);
            return;
        }

        document.querySelectorAll('.booking-form').forEach(form => {
            const inputStart = form.querySelector('input[name="fecha_inicio"]');
            const inputEnd = form.querySelector('input[name="fecha_fin"]');
            if (!inputStart || !inputEnd) return;

            let blocked = [];
            try { blocked = JSON.parse(inputStart.getAttribute('data-blocked') || '[]'); } catch (e) {}

            const pickerEnd = flatpickr(inputEnd, {
                locale: 'es', minDate: 'today', dateFormat: 'Y-m-d', disable: blocked, allowInput: false
            });

            const pickerStart = flatpickr(inputStart, {
                locale: 'es', minDate: 'today', dateFormat: 'Y-m-d', disable: blocked, allowInput: false,
                onChange: (dates) => {
                    if (dates.length > 0) {
                        const next = new Date(dates[0]);
                        next.setDate(next.getDate() + 1);
                        pickerEnd.set('minDate', next);
                    }
                }
            });

            inputStart.addEventListener('click', () => pickerStart.open());
            inputStart.addEventListener('focus', () => pickerStart.open());
            inputEnd.addEventListener('click', () => pickerEnd.open());
            inputEnd.addEventListener('focus', () => pickerEnd.open());

            form.addEventListener('submit', (e) => {
                if (!inputStart.value || !inputEnd.value) {
                    e.preventDefault();
                    alert('Por favor, selecciona las fechas antes de reservar.');
                    return;
                }
                if (new Date(inputEnd.value) <= new Date(inputStart.value)) {
                    e.preventDefault();
                    alert('La fecha de salida debe ser posterior a la de llegada.')
                }
            });
        });
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initFlatpickr);
    } else {
        initFlatpickr();
    }
</script>

<?php require 'includes/footer.php'; ?>