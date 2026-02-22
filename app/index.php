<?php 
require 'includes/db.php'; 
require 'includes/header.php'; 

$ciudad = $_GET['ciudad'] ?? '';
$params = [];

$sql = "SELECT h.* FROM hotel h 
    JOIN usuario u ON h.id_usuario = u.id_usuario 
    WHERE u.estado = 'activo'";

if (!empty($ciudad)) {
    $sql .= " AND h.ciudad LIKE ?";
    $params[] = "%$ciudad%";
}

$sql .= " ORDER BY h.nombre ASC";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$hoteles = $stmt->fetchAll();
?>

<div class="hero-section text-center text-white d-flex align-items-center justify-content-center" 
     style="background-color: #003580; min-height: 350px; margin-bottom: 40px;">
    
    <div class="container">
        <h1 class="display-4 fw-bold mb-3">Encuentra tu próxima estancia</h1>
        <p class="lead mb-4">Hoteles, apartamentos y casas rurales al mejor precio.</p>
        
        <form class="d-flex justify-content-center" method="GET" action="index.php">
            <div class="input-group input-group-lg shadow-lg" style="max-width: 700px; width: 100%;">
                <span class="input-group-text bg-white border-0 ps-4 text-primary">Ubicación:</span>
                <input type="text" 
                       name="ciudad" 
                       class="form-control border-0 p-3" 
                       placeholder="¿A dónde quieres ir? (Ej: Madrid)" 
                       value="<?php echo htmlspecialchars($ciudad); ?>">
                <button class="btn btn-warning fw-bold px-4 text-dark" type="submit">Buscar</button>
            </div>
        </form>
    </div>
</div>

<div class="container mb-5">
    
    <?php if ($ciudad): ?>
        <div class="d-flex align-items-center gap-2 mb-4">
            <h3 class="mb-0">Resultados para "<?php echo htmlspecialchars($ciudad); ?>"</h3>
            <a href="index.php" class="btn btn-outline-secondary btn-sm rounded-pill">Borrar filtros</a>
        </div>
    <?php else: ?>
        <h3 class="mb-4">Alojamientos Destacados</h3>
    <?php endif; ?>

    <?php if (count($hoteles) == 0): ?>
        <div class="alert alert-light border text-center py-5 shadow-sm">
            <h4 class="text-muted">No encontramos alojamientos disponibles en este momento</h4>
            <p class="mb-3">Intenta buscar otra ciudad o revisa en otro momento. Gracias.</p>
            <a href="index.php" class="btn btn-primary mt-2">Ver todos los hoteles</a>
        </div>
    <?php endif; ?>

    <div class="row">
    <?php foreach($hoteles as $hotel): ?>
        <?php 
            $ruta_imagen = "uploads/" . $hotel['imagen'];
            $img = ($hotel['imagen'] && file_exists($ruta_imagen)) ? $ruta_imagen : "https://via.placeholder.com/400x250?text=" . urlencode($hotel['nombre']);
        ?>

        <div class="col-md-4 mb-4">
            <div class="card hotel-card">
                <img src="<?php echo $img; ?>" class="card-img-top card-img-top-fixed" loading="lazy" alt="Foto Hotel">
                
                <div class="card-body">
                    <h5 class="card-title text-primary fw-bold"><?php echo htmlspecialchars($hotel['nombre']); ?></h5>
                    <p class="text-muted mb-2"><small><?php echo htmlspecialchars($hotel['ciudad']); ?></small></p>
                    
                    <p class="card-text">
                        <?php echo substr($hotel['descripcion'], 0, 90); ?>...
                    </p>
                    
                    <div class="mt-3">
                        <a href="hotel.php?id=<?php echo $hotel['id_hotel']; ?>" class="btn btn-primary w-100 fw-bold">Ver Disponibilidad</a>
                    </div>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
    </div>
</div>

<!-- Removed unused .hover-effect rule for cleanup -->

<?php require 'includes/footer.php'; ?>