<?php
// index.php - Página de inicio / Login
require_once 'includes/config.php';
require_once 'includes/auth.php';

if (estaLogueado()) {
    if (esAdmin()) header('Location: admin/index.php');
    else header('Location: cliente/perfil.php');
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email    = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($email && $password) {
        $db   = getDB();
        $stmt = $db->prepare("SELECT id, nombre, password, rol FROM usuarios WHERE email = ?");
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $result = $stmt->get_result();
        $user   = $result->fetch_assoc();

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['usuario_id'] = $user['id'];
            $_SESSION['nombre']     = $user['nombre'];
            $_SESSION['rol']        = $user['rol'];

            if ($user['rol'] === 'admin') header('Location: admin/index.php');
            else header('Location: cliente/perfil.php');
            exit;
        } else {
            $error = 'Email o contraseña incorrectos.';
        }
        $db->close();
    } else {
        $error = 'Rellena todos los campos.';
    }
}

require_once 'includes/header.php';
?>
<div class="container">
    <div style="max-width:900px;margin:0 auto;">

        <!-- Bento login grid -->
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;align-items:start;">

            <!-- Left: branding bento -->
            <div style="display:flex;flex-direction:column;gap:16px;">
                <div class="card card-accent-yellow" style="padding:36px;">
                    <div style="font-size:64px;margin-bottom:16px;">🎠</div>
                    <h1 style="font-size:36px;margin-bottom:8px;line-height:1.1;">Nava<br>Park2</h1>
                    <p style="color:#7a7880;font-size:14px;line-height:1.6;">
                        El parque de atracciones de<br>
                        <strong style="color:#1f2937;">Navarredonda de Gredos</strong>
                    </p>
                </div>
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;">
                    <div class="card" style="text-align:center;padding:20px;">
                        <div style="font-size:28px;font-family:'Syne',sans-serif;font-weight:800;color:#f0a500;">5</div>
                        <div style="font-size:11px;color:#7a7880;text-transform:uppercase;letter-spacing:.05em;margin-top:4px;">Atracciones</div>
                    </div>
                    <div class="card" style="text-align:center;padding:20px;">
                        <div style="font-size:28px;font-family:'Syne',sans-serif;font-weight:800;color:#3d6be4;">🎡</div>
                        <div style="font-size:11px;color:#7a7880;text-transform:uppercase;letter-spacing:.05em;margin-top:4px;">Para todos</div>
                    </div>
                </div>
            </div>

            <!-- Right: login form -->
            <div class="card" style="padding:36px;">
                <h2 style="margin-bottom:6px;">Bienvenido de nuevo</h2>
                <p style="color:#7a7880;font-size:13px;margin-bottom:28px;">Accede a tu cuenta del parque</p>

                <?php if ($error): ?>
                    <div class="alert alert-error"><?= htmlspecialchars($error) ?></div>
                <?php endif; ?>

                <form method="POST">
                    <div class="form-group">
                        <label>Email</label>
                        <input type="email" name="email" required placeholder="tu@email.com">
                    </div>
                    <div class="form-group">
                        <label>Contraseña</label>
                        <input type="password" name="password" required placeholder="••••••••">
                    </div>
                    <button type="submit" class="btn btn-primary" style="width:100%;padding:13px;font-size:15px;justify-content:center;margin-top:6px;">
                        Entrar al parque →
                    </button>
                </form>

                <div style="margin-top:24px;padding-top:20px;border-top:1px solid #2a2a2e;">
                    <p style="font-size:13px;color:#7a7880;text-align:center;margin-bottom:12px;">
                        ¿No tienes cuenta? <a href="registro.php" style="color:#1f2937;font-weight:600;">Regístrate aquí</a>
                    </p>
                    <div style="background:#0e0e0f;border:1px solid #2a2a2e;border-radius:10px;padding:12px;font-size:11px;color:#3a3a3e;text-align:center;">
                        Demo admin: admin@navapark2.com / password
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
<?php require_once 'includes/footer.php'; ?>