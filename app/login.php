<?php 
require 'includes/db.php'; 

$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $stmt = $pdo->prepare("SELECT * FROM usuario WHERE email = ?");
    $stmt->execute([$email]);
    $usuario = $stmt->fetch();

    if ($usuario) {
        if (password_verify($password, $usuario['password'])) {
            
            if ($usuario['estado'] === 'pendiente') {
                $error = "<b>Tu cuenta está en revisión.</b><br>Un administrador debe aprobar tu solicitud antes de poder acceder.";
            } 
            elseif ($usuario['estado'] === 'bloqueado') {
                $error = "<b>Acceso denegado.</b><br>Tu cuenta ha sido bloqueada por un administrador.";
            } 
                $_SESSION['user_id'] = $usuario['id_usuario'];
                $_SESSION['user_name'] = $usuario['nombre'];
                $_SESSION['user_role'] = $usuario['rol'];
                
                header("Location: index.php");
                exit;

        } else {
            $error = "Contraseña incorrecta.";
        }
    } else {
        $error = "No existe una cuenta con este correo.";
    }
}

require 'includes/header.php'; 
?>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-5">
            
            <div class="card shadow border-0">
                <div class="card-header bg-dark text-white text-center py-3">
                    <h3 class="mb-0">Iniciar Sesión</h3>
                </div>
                <div class="card-body p-4">
                    
                    <?php if($error): ?>
                        <div class="alert alert-danger text-center"><?php echo $error; ?></div>
                    <?php endif; ?>

                    <?php if(isset($_GET['msg']) && $_GET['msg']=='registrado'): ?>
                        <div class="alert alert-success text-center">¡Registro exitoso! Ya puedes entrar.</div>
                    <?php endif; ?>

                    <form method="POST">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Correo Electrónico</label>
                            <input type="email" name="email" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Contraseña</label>
                            <input type="password" name="password" class="form-control" required>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary btn-lg fw-bold">Entrar</button>
                        </div>
                    </form>

                </div>
                <div class="card-footer text-center py-3 bg-light">
                    ¿No tienes cuenta? <a href="registro.php" class="fw-bold text-decoration-none">Regístrate aquí</a>
                </div>
            </div>

        </div>
    </div>
</div>

<?php require 'includes/footer.php'; ?>