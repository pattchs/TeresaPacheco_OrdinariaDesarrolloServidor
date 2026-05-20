<?php
// registro.php - Registro de nuevos usuarios (clientes)
require_once 'includes/config.php';
require_once 'includes/auth.php';

if (estaLogueado()) {
    header('Location: index.php');
    exit;
}

$error   = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre           = trim($_POST['nombre'] ?? '');
    $email            = trim($_POST['email'] ?? '');
    $password         = $_POST['password'] ?? '';
    $fecha_nacimiento = $_POST['fecha_nacimiento'] ?? '';

    if (!$nombre || !$email || !$password || !$fecha_nacimiento) {
        $error = 'Todos los campos son obligatorios.';
    } elseif (strlen($password) < 6) {
        $error = 'La contraseña debe tener al menos 6 caracteres.';
    } else {
        $db   = getDB();
        $stmt = $db->prepare("SELECT id FROM usuarios WHERE email = ?");
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $error = 'Ese email ya está registrado.';
        } else {
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $ins  = $db->prepare("INSERT INTO usuarios (nombre, email, password, fecha_nacimiento, rol) VALUES (?,?,?,?,'cliente')");
            $ins->bind_param('ssss', $nombre, $email, $hash, $fecha_nacimiento);
            if ($ins->execute()) {
                $success = '¡Registro completado! Ya puedes iniciar sesión.';
            } else {
                $error = 'Error al registrar. Inténtalo de nuevo.';
            }
        }
        $db->close();
    }
}

require_once 'includes/header.php';
?>
<div class="container">
    <div style="max-width:520px;margin:0 auto;">

        <!-- Header bento -->
        <div style="display:grid;grid-template-columns:1fr auto;gap:16px;margin-bottom:16px;align-items:stretch;">
            <div class="card card-accent-yellow" style="padding:24px;">
                <h1 style="margin-bottom:4px;">Crear cuenta</h1>
                <p style="color:#7a7880;font-size:13px;">Únete al parque y registra tus aventuras</p>
            </div>
            <div class="card" style="display:flex;align-items:center;justify-content:center;padding:24px;font-size:48px;">
                🎟️
            </div>
        </div>

        <div class="card">
            <?php if ($error): ?>
                <div class="alert alert-error"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>

            <?php if ($success): ?>
                <div class="alert alert-success">
                    <?= htmlspecialchars($success) ?>
                </div>
                <a href="index.php" class="btn btn-primary" style="width:100%;justify-content:center;padding:13px;">
                    Ir al login →
                </a>
            <?php else: ?>
            <form method="POST">
                <div class="form-group">
                    <label>Nombre completo</label>
                    <input type="text" name="nombre" required placeholder="Juan García">
                </div>
                <div class="form-group">
                    <label>Email</label>
                    <input type="email" name="email" required placeholder="juan@ejemplo.com">
                </div>
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px;">
                    <div class="form-group">
                        <label>Fecha de nacimiento</label>
                        <input type="date" name="fecha_nacimiento" required max="<?= date('Y-m-d') ?>">
                    </div>
                    <div class="form-group">
                        <label>Contraseña (mín. 6)</label>
                        <input type="password" name="password" required>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary" style="width:100%;padding:13px;font-size:15px;justify-content:center;margin-top:8px;">
                    Crear cuenta →
                </button>
            </form>
            <p style="margin-top:20px;font-size:13px;color:#7a7880;text-align:center;">
                ¿Ya tienes cuenta? <a href="index.php" style="color:#f0a500;font-weight:600;">Inicia sesión</a>
            </p>
            <?php endif; ?>
        </div>
    </div>
</div>
<?php require_once 'includes/footer.php'; ?>
