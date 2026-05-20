<?php
// admin/index.php - Vista Admin: Control general
require_once '../includes/config.php';
require_once '../includes/auth.php';
requiereAdmin();

$db = getDB();

$sql = "
    SELECT 
        a.nombre AS atraccion,
        a.tematica,
        v.hora,
        u.nombre AS viajero,
        TIMESTAMPDIFF(YEAR, u.fecha_nacimiento, CURDATE()) AS edad
    FROM viajes v
    JOIN atracciones a ON v.atraccion_id = a.id
    JOIN usuarios u ON v.usuario_id = u.id
    ORDER BY a.nombre, v.hora DESC
";
$result = $db->query($sql);

$totalViajes      = $db->query("SELECT COUNT(*) AS n FROM viajes")->fetch_assoc()['n'];
$totalUsuarios    = $db->query("SELECT COUNT(*) AS n FROM usuarios WHERE rol='cliente'")->fetch_assoc()['n'];
$totalAtracciones = $db->query("SELECT COUNT(*) AS n FROM atracciones")->fetch_assoc()['n'];

$db->close();

require_once '../includes/header.php';
?>
<div class="container">

    <!-- Title row -->
    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:20px;">
        <h1 style="margin-bottom:0;">🎛️ Panel de Administración</h1>
        <span class="pill pill-yellow">Admin</span>
    </div>

    <!-- Stats bento row -->
    <div class="bento-grid" style="margin-bottom:20px;">
        <div class="card card-accent-yellow" style="text-align:center;">
            <div class="stat-num" style="color:#f0a500;"><?= $totalViajes ?></div>
            <div class="stat-label">Viajes totales</div>
        </div>
        <div class="card card-accent-blue" style="text-align:center;">
            <div class="stat-num" style="color:#3d6be4;"><?= $totalUsuarios ?></div>
            <div class="stat-label">Clientes registrados</div>
        </div>
        <div class="card card-accent-green" style="text-align:center;">
            <div class="stat-num" style="color:#2ecc71;"><?= $totalAtracciones ?></div>
            <div class="stat-label">Atracciones</div>
        </div>
    </div>

    <!-- Quick actions bento -->
    <div class="card" style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:14px;margin-bottom:20px;">
        <div>
            <h2 style="margin-bottom:4px;">⚙️ Gestión de atracciones</h2>
            <p style="color:#7a7880;font-size:13px;">Crear, editar y eliminar atracciones del parque</p>
        </div>
        <a href="atracciones.php" class="btn btn-primary">Ver / Gestionar →</a>
    </div>

    <!-- All trips table -->
    <div class="card">
        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:18px;">
            <h2 style="margin-bottom:0;">📋 Control general de viajes</h2>
            <span style="font-size:12px;color:#7a7880;"><?= $result ? $result->num_rows : 0 ?> registros</span>
        </div>

        <?php if ($result && $result->num_rows > 0): ?>
        <div style="overflow-x:auto;">
            <table>
                <thead>
                    <tr>
                        <th>Atracción</th>
                        <th>Temática</th>
                        <th>Viajero</th>
                        <th>Edad</th>
                        <th>Hora del viaje</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($fila = $result->fetch_assoc()): ?>
                    <tr>
                        <td><strong><?= htmlspecialchars($fila['atraccion']) ?></strong></td>
                        <td><span class="pill pill-blue"><?= htmlspecialchars($fila['tematica']) ?></span></td>
                        <td><?= htmlspecialchars($fila['viajero']) ?></td>
                        <td>
                            <span style="font-family:'Syne',sans-serif;font-weight:700;color:#f0a500;">
                                <?= $fila['edad'] ?>
                            </span>
                            <span style="color:#7a7880;font-size:12px;"> años</span>
                        </td>
                        <td style="color:#7a7880;font-size:13px;"><?= date('d/m/Y H:i', strtotime($fila['hora'])) ?></td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
        <?php else: ?>
        <div class="empty">
            <div class="empty-icon">📊</div>
            <p>No hay viajes registrados todavía.</p>
        </div>
        <?php endif; ?>
    </div>
</div>
<?php require_once '../includes/footer.php'; ?>
