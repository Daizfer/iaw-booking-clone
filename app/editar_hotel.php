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

if (!$hotel) die("<div class='container mt-5 alert alert-danger'>No tienes permiso.</div>");

$stmtServ = $pdo->query("SELECT * FROM servicio");
$todos_servicios = $stmtServ->fetchAll();

$stmtMisServ = $pdo->prepare("SELECT id_servicio FROM hotel_servicio WHERE id_hotel = ?");
$stmtMisServ->execute([$id_hotel]);
$mis_servicios_ids = $stmtMisServ->fetchAll(PDO::FETCH_COLUMN);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nombre = $_POST['nombre'];
    $direccion = $_POST['direccion'];
    $ciudad = $_POST['ciudad'];
    $desc = $_POST['descripcion'];
    $servicios_seleccionados = $_POST['servicios'] ?? [];
    
    $nombre_imagen = $hotel['imagen']; 
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
            $pdo->rollBack();
            die("<div class='container mt-5'><div class='alert alert-danger'>Tipo de archivo no permitido. Solo PNG, JPG y JPEG son aceptados.</div></div>");
        }

        $nombre_archivo = uniqid() . '_' . basename($_FILES['imagen']['name']);
        if (move_uploaded_file($_FILES['imagen']['tmp_name'], $dir . $nombre_archivo)) {
            $nombre_imagen = $nombre_archivo; 
        }
    }

    try {
        $pdo->beginTransaction();
        $sql = "UPDATE hotel SET nombre=?, direccion=?, ciudad=?, descripcion=?, imagen=? WHERE id_hotel=?";
        $stmtUpdate = $pdo->prepare($sql);
        $stmtUpdate->execute([$nombre, $direccion, $ciudad, $desc, $nombre_imagen, $id_hotel]);

        $pdo->prepare("DELETE FROM hotel_servicio WHERE id_hotel = ?")->execute([$id_hotel]);
        
        if (!empty($servicios_seleccionados)) {
            $stmtInsertServ = $pdo->prepare("INSERT INTO hotel_servicio (id_hotel, id_servicio) VALUES (?, ?)");
            foreach($servicios_seleccionados as $id_srv) {
                $stmtInsertServ->execute([$id_hotel, $id_srv]);
            }
        }
        $pdo->commit();
        echo "<script>alert('Hotel actualizado correctamente'); window.location='mis_hoteles.php';</script>";
    } catch (Exception $e) {
        $pdo->rollBack();
        echo "Error: " . $e->getMessage();
    }
}
?>

<div class="container mt-5">
    <div class="card shadow">
        <div class="card-header bg-warning text-dark">
            <h4 class="mb-0">Editar: <?php echo htmlspecialchars($hotel['nombre']); ?></h4>
        </div>
        <div class="card-body">
            <form method="POST" enctype="multipart/form-data">
                <div class="mb-3"><label>Nombre</label><input type="text" name="nombre" class="form-control" value="<?php echo htmlspecialchars($hotel['nombre']); ?>" required></div>
                <div class="row">
                    <div class="col-md-6 mb-3"><label>Dirección</label><input type="text" name="direccion" class="form-control" value="<?php echo htmlspecialchars($hotel['direccion']); ?>" required></div>
                    <div class="col-md-6 mb-3"><label>Ciudad</label><input type="text" name="ciudad" class="form-control" value="<?php echo htmlspecialchars($hotel['ciudad']); ?>" required></div>
                </div>
                <div class="mb-3"><label>Descripción</label><textarea name="descripcion" class="form-control" rows="4"><?php echo htmlspecialchars($hotel['descripcion']); ?></textarea></div>

                <div class="mb-4">
                    <label class="fw-bold">Imagen</label><br>
                    <?php if($hotel['imagen']): ?><img src="uploads/<?php echo $hotel['imagen']; ?>" width="100" class="mb-2 rounded"><?php endif; ?>
                    <input type="file" name="imagen" class="form-control" accept="image/png,image/jpeg">
                </div>

                <div class="mb-4">
                    <label class="fw-bold">Servicios</label>
                    <div class="row">
                    <?php foreach($todos_servicios as $srv): ?>
                        <?php $checked = in_array($srv['id_servicio'], $mis_servicios_ids) ? 'checked' : ''; ?>
                        <div class="col-md-4">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="servicios[]" value="<?php echo $srv['id_servicio']; ?>" <?php echo $checked; ?>>
                                <label class="form-check-label"><?php echo htmlspecialchars($srv['nombre']); ?></label>
                            </div>
                        </div>
                    <?php endforeach; ?>
                    </div>
                </div>

                <button type="submit" class="btn btn-success fw-bold">Guardar Cambios</button>
                <a href="mis_hoteles.php" class="btn btn-secondary">Cancelar</a>
            </form>
        </div>
    </div>
</div>
<?php require 'includes/footer.php'; ?>