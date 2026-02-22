<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Hostelero - Reservas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <link rel="stylesheet" href="css/style.css">
    <style>
        html, body { height: 100%; margin: 0; }
        body { display: flex; flex-direction: column; min-height: 100vh; }
        footer { margin-top: auto; flex-shrink: 0; }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark sticky-top">
  <div class="container">
    <a class="navbar-brand fs-4" href="index.php">Hostelero</a>
    
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
      <span class="navbar-toggler-icon"></span>
    </button>
    
    <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
      <ul class="navbar-nav align-items-center">
        <?php if(isset($_SESSION['user_id'])): ?>
            
            <?php if($_SESSION['user_role'] == 'dueño' || $_SESSION['user_role'] == 'admin'): ?>
                <li class="nav-item">
                    <a class="btn btn-outline-light btn-sm me-2 fw-bold" href="mis_hoteles.php">
                        <?php echo ($_SESSION['user_role'] == 'admin') ? 'Panel Admin' : 'Gestionar Hoteles'; ?>
                    </a>
                </li>
            <?php endif; ?>

            <?php if($_SESSION['user_role'] == 'admin'): ?>
                <li class="nav-item">
                    <a class="btn btn-warning btn-sm me-3 fw-bold text-dark" href="admin_usuarios.php">
                        Usuarios
                    </a>
                </li>
            <?php endif; ?>

            <li class="nav-item text-white me-3">
                <?php echo htmlspecialchars($_SESSION['user_name']); ?>
                <span class="badge bg-warning text-dark" style="font-size: 0.7em;">
                    <?php echo strtoupper($_SESSION['user_role']); ?>
                </span>
            </li>
            
            <li class="nav-item"><a class="btn btn-warning btn-sm text-dark fw-bold me-2" href="mis_reservas.php">Mis Viajes</a></li>
            <li class="nav-item"><a class="btn btn-danger btn-sm text-white fw-bold ms-2" href="logout.php">Salir</a></li>

        <?php else: ?>
            <li class="nav-item"><a class="nav-link text-white" href="login.php">Iniciar Sesión</a></li>
            <li class="nav-item"><a class="btn btn-light btn-sm ms-2 text-primary fw-bold" href="registro.php">Registrarse</a></li>
        <?php endif; ?>
      </ul>
    </div>
  </div>
</nav>
<main class="flex-fill">