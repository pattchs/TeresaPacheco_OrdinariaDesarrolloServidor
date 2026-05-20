<?php
// cliente/viaje.php - Registrar un viaje en una atracción
require_once '../includes/config.php';
require_once '../includes/auth.php';
requiereLogin();

if (esAdmin()) {
    header('Location: ../admin/index.php');
    exit;
}

$db      = getDB();
$uid     = $_SESSION['usuario_id'];
$mensaje = '';
$error   = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $atraccion_id = intval($_POST['atraccion_id'] ?? 0);
    $hora         = trim($_POST['hora'] ?? '');

    if ($atraccion_id && $hora) {
        $check = $db->prepare("SELECT id FROM atracciones WHERE id = ?");
        $check->bind_param('i', $atraccion_id);
        $check->execute();
        $check->store_result();

        if ($check->num_rows > 0) {
            $stmt = $db->prepare("INSERT INTO viajes (usuario_id, atraccion_id, hora) VALUES (?,?,?)");
            $stmt->bind_param('iis', $uid, $atraccion_id, $hora);
            if ($stmt->execute()) {
                $mensaje = '¡Viaje registrado! ¡Que lo disfrutes!';
            } else {
                $error = 'Error al guardar el viaje.';
            }
        } else {
            $error = 'Atracción no válida.';
        }
    } else {
        $error = 'Selecciona una atracción y una hora.';
    }
}

$atracciones = $db->query("SELECT id, nombre, tematica FROM atracciones ORDER BY nombre");
$db->close();

require_once '../includes/header.php';
?>
<div class="container">
    <div style="max-width:560px;margin:0 auto;">

        <!-- Header bento row -->
        <div style="display:grid;grid-template-columns:auto 1fr;gap:16px;margin-bottom:16px;align-items:stretch;">
            <div class="card" style="display:flex;align-items:center;justify-content:center;padding:24px;font-size:52px;">
                🎫
            </div>
            <div class="card card-accent-yellow" style="padding:24px;">
                <h1 style="margin-bottom:4px;">Registrar viaje</h1>
                <p style="color:#7a7880;font-size:13px;line-height:1.5;">Indica en qué atracción has montado y a qué hora.</p>
            </div>
        </div>

        <div class="card">
            <?php if ($mensaje): ?>
                <div class="alert alert-success"><?= htmlspecialchars($mensaje) ?></div>
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;margin-top:8px;">
                    <a href="perfil.php" class="btn btn-secondary" style="justify-content:center;">← Mi perfil</a>
                    <a href="viaje.php" class="btn btn-primary" style="justify-content:center;">+ Otro viaje</a>
                </div>
            <?php else: ?>

                <?php if ($error): ?>
                    <div class="alert alert-error"><?= htmlspecialchars($error) ?></div>
                <?php endif; ?>

                <form method="POST">
                    <div class="form-group">
                        <label>Atracción *</label>
                        <select name="atraccion_id" required>
                            <option value="">-- Selecciona una atracción --</option>
                            <?php if ($atracciones): while ($a = $atracciones->fetch_assoc()): ?>
                                    <option value="<?= $a['id'] ?>">
                                        <?= htmlspecialchars($a['nombre']) ?> · <?= htmlspecialchars($a['tematica']) ?>
                                    </option>
                            <?php endwhile;
                            endif; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Hora del viaje *</label>
                        <input type="datetime-local"
                            name="hora"
                            required
                            value="<?= date('Y-m-d\TH:i') ?>"
                            max="<?= date('Y-m-d\TH:i') ?>">
                    </div>
                    <button type="submit" class="btn btn-primary" style="width:100%;padding:13px;font-size:15px;justify-content:center;margin-top:6px;">
                        ✨¡Registrar viaje!
                    </button>
                </form>
                <p style="margin-top:16px;text-align:center;">
                    <a href="perfil.php" style="color:#7a7880;font-size:13px;">← Volver a mi perfil</a>
                </p>
            <?php endif; ?>
        </div>
    </div>
</div>
<?php require_once '../includes/footer.php'; ?>