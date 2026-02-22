<?php 
require 'includes/db.php'; 
require 'includes/header.php'; 

if (!isset($_SESSION['user_id']) || ($_SESSION['user_role'] != 'dueño' && $_SESSION['user_role'] != 'admin')) {
    header("Location: index.php");
    exit;
}

$id_hotel = $_GET['id_hotel'] ?? 0;
$id_usuario = $_SESSION['user_id'];
$rol = $_SESSION['user_role'];

$tipos_validos = ['Individual','Doble','Suite','Familiar','Apartamento'];

$query = "SELECT * FROM hotel WHERE id_hotel = ?";
if ($rol == 'admin') {
    $stmtCheck = $pdo->prepare($query);
    $stmtCheck->execute([$id_hotel]);
} else {
    $stmtCheck = $pdo->prepare($query . " AND id_usuario = ?");
    $stmtCheck->execute([$id_hotel, $id_usuario]);
}
$hotel = $stmtCheck->fetch();

if (!$hotel) die("<div class='container mt-5'>Hotel no encontrado o sin permisos.</div>");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $numero = trim($_POST['numero'] ?? '');
    $tipo = $_POST['tipo'] ?? '';
    $precio = $_POST['precio'] ?? '';
    $capacidad = $_POST['capacidad'] ?? '';
    $desc = trim($_POST['descripcion'] ?? '');

    $errores = [];

    if ($numero === '' || !preg_match('/^[0-9]{1,5}$/', $numero)) {
        $errores[] = 'El campo Puerta/Nombre debe tener solo números (máx. 5 dígitos).';
    }

    if (!in_array($tipo, $tipos_validos)) {
        $errores[] = 'Tipo de habitación inválido.';
    }

    if (!is_numeric($precio) || floatval($precio) < 1) {
        $errores[] = 'El precio por noche debe ser un número y al menos 1.';
    } else {
        $precio = number_format((float)$precio, 2, '.', '');
    }

    if (!is_numeric($capacidad) || intval($capacidad) < 1) {
        $errores[] = 'La capacidad debe ser un número entero y al menos 1.';
    } else {
        $capacidad = intval($capacidad);
    }

    if (empty($errores)) {
        $sql = "INSERT INTO habitacion (id_hotel, numero_puerta, tipo, precio_noche, capacidad, descripcion) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        if ($stmt->execute([$id_hotel, $numero, $tipo, $precio, $capacidad, $desc])) {
            $mensaje = "Habitación agregada con éxito";
        } else {
            $mensaje = "<div class='alert alert-danger'>Error al guardar la habitación.</div>";
        }
    } else {
        $mensaje = "<div class='alert alert-danger'>" . htmlspecialchars($errores[0]) . "</div>";
    }
}

$stmtHab = $pdo->prepare("SELECT * FROM habitacion WHERE id_hotel = ?");
$stmtHab->execute([$id_hotel]);
$habitaciones = $stmtHab->fetchAll();
?>

<div class="container mt-5">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2>Habitaciones: <?php echo htmlspecialchars($hotel['nombre']); ?></h2>
        <a href="mis_hoteles.php" class="btn btn-secondary">Volver</a>
    </div>

    <?php if(isset($mensaje)) echo "<div class='alert alert-success'>$mensaje</div>"; ?>

    <div class="row mt-4">
        <div class="col-md-4">
            <div class="card p-3 bg-light shadow-sm">
                <h5>+ Nueva Habitación</h5>
                <form method="POST">
                    <div class="mb-2"><label>Puerta/Nombre</label><input type="text" name="numero" class="form-control" required maxlength="5" pattern="[0-9]{1,5}" title="Solo números (0-9), máximo 5 dígitos" value="<?php echo isset($numero) ? htmlspecialchars($numero) : ''; ?>"></div>
                    <div class="mb-2"><label>Tipo</label>
                        <select name="tipo" class="form-select">
                            <?php foreach ($tipos_validos as $t): ?>
                                <option value="<?php echo $t; ?>" <?php echo (isset($tipo) && $tipo === $t) ? 'selected' : ''; ?>><?php echo $t; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-2"><label>Precio Noche</label><input type="number" step="0.01" min="1" name="precio" class="form-control" required value="<?php echo isset($precio) ? htmlspecialchars($precio) : ''; ?>"></div>
                    <div class="mb-2"><label>Capacidad</label><input type="number" min="1" name="capacidad" class="form-control" required value="<?php echo isset($capacidad) ? htmlspecialchars($capacidad) : ''; ?>"></div>
                    <div class="mb-3"><label>Detalles</label><textarea name="descripcion" class="form-control" rows="2"><?php echo isset($desc) ? htmlspecialchars($desc) : ''; ?></textarea></div>
                    <button type="submit" class="btn btn-success w-100">Agregar</button>
                </form>
            </div>
        </div>

        <div class="col-md-8">
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead class="table-dark">
                        <tr><th>Puerta</th><th>Tipo</th><th>Precio</th><th>Capacidad</th></tr>
                    </thead>
                    <tbody>
                        <?php foreach($habitaciones as $h): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($h['numero_puerta']); ?></td>
                            <td><?php echo htmlspecialchars($h['tipo']); ?></td>
                            <td>$<?php echo $h['precio_noche']; ?></td>
                            <td><?php echo $h['capacidad']; ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<?php require 'includes/footer.php'; ?>