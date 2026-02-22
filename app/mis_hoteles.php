<?php 
require 'includes/db.php'; 
require 'includes/header.php'; 

if (!isset($_SESSION['user_id']) || ($_SESSION['user_role'] != 'dueño' && $_SESSION['user_role'] != 'admin')) {
    header("Location: index.php");
    exit;
}

$id_usuario = $_SESSION['user_id'];
$mensaje = '';

if (isset($_GET['msg']) && $_GET['msg'] == 'borrado') {
    $mensaje = "<div class='alert alert-warning'>El hotel ha sido eliminado correctamente.</div>";
}

$stmtServicios = $pdo->query("SELECT * FROM servicio");
$servicios_disponibles = $stmtServicios->fetchAll();

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['crear_hotel'])) {
    $nombre = $_POST['nombre'];
    $direccion = $_POST['direccion'];
    $ciudad = $_POST['ciudad'];
    $desc = $_POST['descripcion'];
    $servicios_seleccionados = $_POST['servicios'] ?? []; 
    $nombre_imagen = null;

    if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] == 0) {
        $dir = 'uploads/';
        if (!is_dir($dir)) mkdir($dir);

        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mime = finfo_file($finfo, $_FILES['imagen']['tmp_name']);
        finfo_close($finfo);
        $ext = strtolower(pathinfo($_FILES['imagen']['name'], PATHINFO_EXTENSION));
        $allowedMimes = ['image/png', 'image/jpeg'];
        $allowedExts = ['png', 'jpg', 'jpeg'];

        if (!in_array($mime, $allowedMimes) || !in_array($ext, $allowedExts)) {
            $mensaje = "<div class='alert alert-danger'>Tipo de archivo no permitido. Solo PNG, JPG y JPEG son aceptados.</div>";
        } else {
            $nombre_archivo = uniqid() . '_' . basename($_FILES['imagen']['name']);
            if (move_uploaded_file($_FILES['imagen']['tmp_name'], $dir . $nombre_archivo)) {
                $nombre_imagen = $nombre_archivo;
            }
        }
    }

    try {
        $pdo->beginTransaction(); 
        $sql = "INSERT INTO hotel (id_usuario, nombre, direccion, ciudad, descripcion, imagen) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$id_usuario, $nombre, $direccion, $ciudad, $desc, $nombre_imagen]);
        $id_nuevo_hotel = $pdo->lastInsertId(); 

        if (!empty($servicios_seleccionados)) {
            $sqlServ = "INSERT INTO hotel_servicio (id_hotel, id_servicio) VALUES (?, ?)";
            $stmtServ = $pdo->prepare($sqlServ);
            foreach($servicios_seleccionados as $id_servicio) {
                $stmtServ->execute([$id_nuevo_hotel, $id_servicio]);
            }
        }
        $pdo->commit();
        $mensaje = "<div class='alert alert-success'>Hotel y servicios registrados correctamente!</div>";
    } catch (Exception $e) {
        $pdo->rollBack();
        $mensaje = "<div class='alert alert-danger'>Error al guardar: " . $e->getMessage() . "</div>";
    }
}

if ($_SESSION['user_role'] == 'admin') {
    $stmt = $pdo->query("SELECT * FROM hotel");
    $mis_hoteles = $stmt->fetchAll();
    $titulo_panel = "Panel de Administración (Todos los Hoteles)";
} else {
    $stmt = $pdo->prepare("SELECT * FROM hotel WHERE id_usuario = ?");
    $stmt->execute([$id_usuario]);
    $mis_hoteles = $stmt->fetchAll();
    $titulo_panel = "Gestionar mis Alojamientos";
}
?>

<div class="container mt-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><?php echo $titulo_panel; ?></h2>
        <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modalNuevoHotel">
            + Nuevo Alojamiento
        </button>
    </div>

    <?php echo $mensaje; ?>

    <div class="row">
        <?php foreach($mis_hoteles as $hotel): ?>
        <div class="col-md-6 mb-4">
            <div class="card shadow-sm h-100">
                <div class="row g-0">
                    <div class="col-md-4">
                        <?php 
                          $img = ($hotel['imagen'] && file_exists("uploads/".$hotel['imagen'])) ? "uploads/".$hotel['imagen'] : "https://via.placeholder.com/400x300?text=Sin+Foto"; 
                        ?>
                        <img src="<?php echo $img; ?>" class="hotel-img-horizontal" alt="Foto del hotel">
                    </div>
                    <div class="col-md-8">
                        <div class="card-body">
                            <h5 class="card-title"><?php echo htmlspecialchars($hotel['nombre']); ?></h5>
                            <p class="text-muted mb-1"><?php echo htmlspecialchars($hotel['ciudad']); ?></p>
                            
                            <div class="d-flex flex-wrap gap-2 mt-3">
                                
                                <a href="gestion_hotel.php?id=<?php echo $hotel['id_hotel']; ?>" class="btn btn-info btn-sm text-white fw-bold" title="Ver Reservas y Opiniones">
                                    Gestión
                                </a>

                                <a href="hotel.php?id=<?php echo $hotel['id_hotel']; ?>" class="btn btn-outline-primary btn-sm" title="Ver página pública">Ver</a>
                                
                                <a href="editar_hotel.php?id=<?php echo $hotel['id_hotel']; ?>" class="btn btn-warning btn-sm" title="Modificar datos">Editar</a>
                                
                                <a href="agregar_habitacion.php?id_hotel=<?php echo $hotel['id_hotel']; ?>" class="btn btn-primary btn-sm" title="Gestionar habitaciones">Habitaciones</a>
                                
                                <a href="borrar_hotel.php?id=<?php echo $hotel['id_hotel']; ?>" 
                                   class="btn btn-danger btn-sm" 
                                   onclick="return confirm('¿Estás seguro de que quieres ELIMINAR este hotel?\n\nSe borrarán también todas sus habitaciones y reservas.');" 
                                   title="Eliminar Hotel">
                                   Eliminar
                                </a>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</div>

<div class="modal fade" id="modalNuevoHotel" tabindex="-1">
  <div class="modal-dialog modal-lg"> <div class="modal-content">
      <form method="POST" enctype="multipart/form-data">
          <div class="modal-header">
            <h5 class="modal-title">Registrar Nueva Propiedad</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
          </div>
          <div class="modal-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3"><label>Nombre del Hotel</label><input type="text" name="nombre" class="form-control" required></div>
                    <div class="mb-3"><label>Dirección</label><input type="text" name="direccion" class="form-control" required></div>
                    <div class="mb-3"><label>Ciudad</label><input type="text" name="ciudad" class="form-control" required></div>
                    <div class="mb-3"><label class="fw-bold">Foto Principal</label><input type="file" name="imagen" class="form-control" accept="image/png,image/jpeg"></div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3"><label>Descripción</label><textarea name="descripcion" class="form-control" rows="3"></textarea></div>
                    
                    <label class="fw-bold mb-2">Servicios Disponibles:</label>
                    <div class="card p-2" style="max-height: 200px; overflow-y: auto;">
                        <?php foreach($servicios_disponibles as $srv): ?>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="servicios[]" value="<?php echo $srv['id_servicio']; ?>" id="srv<?php echo $srv['id_servicio']; ?>">
                                <label class="form-check-label" for="srv<?php echo $srv['id_servicio']; ?>">
                                    <?php echo htmlspecialchars($srv['nombre']); ?>
                                </label>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
            <input type="hidden" name="crear_hotel" value="1">
          </div>
          <div class="modal-footer">
            <button type="submit" class="btn btn-primary">Guardar Hotel</button>
          </div>
      </form>
    </div>
  </div>
</div>

<?php require 'includes/footer.php'; ?>