<?php
// cliente/perfil.php - Perfil del cliente: nombre, edad y sus viajes
require_once '../includes/config.php';
require_once '../includes/auth.php';
requiereLogin();

if (esAdmin()) {
    header('Location: ../admin/index.php');
    exit;
}

$db  = getDB();
$uid = $_SESSION['usuario_id'];

$stmt = $db->prepare("SELECT nombre, fecha_nacimiento, email FROM usuarios WHERE id = ?");
$stmt->bind_param('i', $uid);
$stmt->execute();
$usuario = $stmt->get_result()->fetch_assoc();
$edad    = calcularEdad($usuario['fecha_nacimiento']);

$stmt2 = $db->prepare("
    SELECT a.nombre AS atraccion, a.tematica, v.hora
    FROM viajes v
    JOIN atracciones a ON v.atraccion_id = a.id
    WHERE v.usuario_id = ?
    ORDER BY v.hora DESC
");
$stmt2->bind_param('i', $uid);
$stmt2->execute();
$viajes = $stmt2->get_result();
$totalViajes = $viajes->num_rows;

$db->close();

require_once '../includes/header.php';
?>
<div class="container">

    <!-- Bento top row -->
    <div class="bento-grid" style="grid-template-columns:2fr 1fr 1fr;">

        <!-- Profile card -->
        <div class="card card-accent-yellow" style="display:flex;align-items:center;gap:20px;">
            <div style="width:68px;height:68px;background:#0e0e0f;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:30px;flex-shrink:0;border:2px solid #2a2a2e;">
                🎆
            </div>
            <div>
                <h1 style="margin-bottom:2px;font-size:22px;"><?= htmlspecialchars($usuario['nombre']) ?></h1>
                <p style="color:#7a7880;font-size:13px;margin-bottom:6px;"><?= htmlspecialchars($usuario['email']) ?></p>
                <span class="pill pill-yellow">Cliente</span>
            </div>
        </div>

        <!-- Age card -->
        <div class="card" style="text-align:center;display:flex;flex-direction:column;align-items:center;justify-content:center;">
            <div class="stat-num" style="color:#f0a500;"><?= $edad ?></div>
            <div class="stat-label">Años</div>
            <div style="font-size:11px;color:#3a3a3e;margin-top:6px;">
                <?= date('d/m/Y', strtotime($usuario['fecha_nacimiento'])) ?>
            </div>
        </div>

        <!-- Trips card -->
        <div class="card card-accent-blue" style="text-align:center;display:flex;flex-direction:column;align-items:center;justify-content:center;">
            <div class="stat-num" style="color:#3d6be4;"><?= $totalViajes ?></div>
            <div class="stat-label">Viajes</div>
            <a href="viaje.php" class="btn btn-primary btn-sm" style="margin-top:12px;">+ Nuevo</a>
        </div>

    </div>

    <!-- Viajes table -->
    <div class="card">
        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:18px;">
            <h2 style="margin-bottom:0;">🎆 Mis viajes en NavaPark2</h2>
            <a href="viaje.php" class="btn btn-primary btn-sm">+ Registrar viaje</a>
        </div>

        <?php if ($totalViajes > 0): ?>
            <div style="overflow-x:auto;">
                <table>
                    <thead>
                        <tr>
                            <th>Atracción</th>
                            <th>Temática</th>
                            <th>Fecha y hora</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($v = $viajes->fetch_assoc()): ?>
                            <tr>
                                <td><strong><?= htmlspecialchars($v['atraccion']) ?></strong></td>
                                <td><span class="pill pill-blue"><?= htmlspecialchars($v['tematica']) ?></span></td>
                                <td style="color:#7a7880;font-size:13px;"><?= date('d/m/Y \a \l\a\s H:i', strtotime($v['hora'])) ?></td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <div class="empty">
                <div class="empty-icon">🎟️</div>
                <p style="margin-bottom:16px;">¡Todavía no has montado en ninguna atracción!</p>
                <a href="viaje.php" class="btn btn-primary">Registrar mi primer viaje</a>
            </div>
        <?php endif; ?>
    </div>
</div>
<?php require_once '../includes/footer.php'; ?>