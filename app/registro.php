<?php 
require 'includes/db.php'; 
require 'includes/header.php'; 

$mensaje = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nombre = $_POST['nombre'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $rol = $_POST['rol'];

    $estado = ($rol == 'dueño') ? 'pendiente' : 'activo';

    $pass_encriptada = password_hash($password, PASSWORD_DEFAULT);

    $sql = "INSERT INTO usuario (nombre, email, password, rol, estado) VALUES (?, ?, ?, ?, ?)";
    $stmt = $pdo->prepare($sql);

    try {
        if ($stmt->execute([$nombre, $email, $pass_encriptada, $rol, $estado])) {
            
            if ($rol == 'dueño') {
                $mensaje = "
                <div class='alert alert-warning text-center shadow-sm'>
                    <h4>¡Cuenta Creada!</h4>
                    <p>Tu solicitud de <strong>Propietario</strong> ha sido registrada.</p>
                    <hr>
                    <p class='mb-0'>Un administrador debe <strong>aprobar tu cuenta</strong> antes de que puedas acceder.</p>
                    <p>Intenta iniciar sesión más tarde.</p>
                </div>";
            } else {
                $mensaje = "
                <div class='alert alert-success text-center shadow-sm'>
                    <h4>¡Bienvenido!
                    <p>Tu cuenta de cliente se ha creado correctamente.</p>
                    <a href='login.php' class='btn btn-success fw-bold'>Iniciar Sesión Ahora</a>
                </div>";
            }

        }
    } catch (PDOException $e) {
        if ($e->getCode() == 23000) {
            $mensaje = "<div class='alert alert-danger'>Este correo electrónico ya está registrado.</div>";
        } else {
            $mensaje = "<div class='alert alert-danger'>Error: " . $e->getMessage() . "</div>";
        }
    }
}
?>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            
            <?php echo $mensaje; ?>

            <div class="card shadow-lg border-0">
                <div class="card-header bg-primary text-white text-center py-3">
                    <h3 class="mb-0">Crear Nueva Cuenta</h3>
                </div>
                <div class="card-body p-4">
                    
                    <form method="POST" action="registro.php">
                        
                        <div class="mb-3">
                            <label class="form-label fw-bold">Nombre Completo</label>
                            <input type="text" name="nombre" class="form-control" required placeholder="Ej: Juan Pérez">
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Correo Electrónico</label>
                            <input type="email" name="email" class="form-control" required placeholder="nombre@ejemplo.com">
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Contraseña</label>
                            <input type="password" name="password" class="form-control" required placeholder="********">
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold">¿Qué quieres hacer?</label>
                            <select name="rol" class="form-select" required>
                                <option value="cliente">Quiero reservar hoteles (Cliente)</option>
                                <option value="dueño">Quiero publicar mis hoteles (Propietario)</option>
                            </select>
                            <div class="form-text text-muted mt-2">
                                * Nota: Las cuentas de propietario requieren aprobación manual del administrador.
                            </div>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary btn-lg fw-bold">Registrarse</button>
                        </div>

                    </form>
                </div>
                <div class="card-footer text-center py-3 bg-light">
                    ¿Ya tienes cuenta? <a href="login.php" class="fw-bold text-decoration-none">Inicia sesión aquí</a>
                </div>
            </div>

        </div>
    </div>
</div>

<div style="height: 50px;"></div>

<?php require 'includes/footer.php'; ?>