<?php
// admin/atracciones.php - CRUD completo de atracciones
require_once '../includes/config.php';
require_once '../includes/auth.php';
requiereAdmin();

$db      = getDB();
$accion  = $_GET['accion'] ?? 'listar';
$id      = intval($_GET['id'] ?? 0);
$mensaje = '';
$error   = '';

// ── BORRAR ────────────────────────────────────────────────
if ($accion === 'borrar' && $id) {
    $stmt = $db->prepare("DELETE FROM atracciones WHERE id = ?");
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $mensaje = 'Atracción eliminada correctamente.';
    $accion = 'listar';
}

// ── INSERTAR ──────────────────────────────────────────────
if ($accion === 'insertar' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre      = trim($_POST['nombre'] ?? '');
    $tematica    = trim($_POST['tematica'] ?? '');
    $descripcion = trim($_POST['descripcion'] ?? '');

    if ($nombre && $tematica) {
        $stmt = $db->prepare("INSERT INTO atracciones (nombre, tematica, descripcion) VALUES (?,?,?)");
        $stmt->bind_param('sss', $nombre, $tematica, $descripcion);
        if ($stmt->execute()) {
            $mensaje = 'Atracción añadida correctamente.';
            $accion  = 'listar';
        } else {
            $error = 'Error al insertar.';
        }
    } else {
        $error = 'Nombre y temática son obligatorios.';
    }
}

// ── MODIFICAR (guardar) ───────────────────────────────────
if ($accion === 'modificar' && $_SERVER['REQUEST_METHOD'] === 'POST' && $id) {
    $nombre      = trim($_POST['nombre'] ?? '');
    $tematica    = trim($_POST['tematica'] ?? '');
    $descripcion = trim($_POST['descripcion'] ?? '');

    if ($nombre && $tematica) {
        $stmt = $db->prepare("UPDATE atracciones SET nombre=?, tematica=?, descripcion=? WHERE id=?");
        $stmt->bind_param('sssi', $nombre, $tematica, $descripcion, $id);
        if ($stmt->execute()) {
            $mensaje = 'Atracción actualizada correctamente.';
            $accion  = 'listar';
        } else {
            $error = 'Error al actualizar.';
        }
    } else {
        $error = 'Nombre y temática son obligatorios.';
    }
}

// ── Cargar datos para edición ─────────────────────────────
$atraccionEditar = null;
if ($accion === 'modificar' && $id && $_SERVER['REQUEST_METHOD'] !== 'POST') {
    $stmt = $db->prepare("SELECT * FROM atracciones WHERE id = ?");
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $atraccionEditar = $stmt->get_result()->fetch_assoc();
    if (!$atraccionEditar) { $accion = 'listar'; }
}

// ── Listar todas ──────────────────────────────────────────
$atracciones = $db->query("
    SELECT a.*, COUNT(v.id) AS total_viajes
    FROM atracciones a
    LEFT JOIN viajes v ON a.id = v.atraccion_id
    GROUP BY a.id
    ORDER BY a.nombre
");

$db->close();

require_once '../includes/header.php';
?>
<div class="container">

    <!-- Header -->
    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:20px;flex-wrap:wrap;gap:12px;">
        <h1 style="margin-bottom:0;">🎡 Gestión de Atracciones</h1>
        <div style="display:flex;gap:8px;">
            <a href="index.php" class="btn btn-secondary">← Panel Admin</a>
            <a href="?accion=insertar" class="btn btn-primary">+ Nueva atracción</a>
        </div>
    </div>

    <?php if ($mensaje): ?>
        <div class="alert alert-success"><?= htmlspecialchars($mensaje) ?></div>
    <?php endif; ?>
    <?php if ($error): ?>
        <div class="alert alert-error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <!-- FORM: INSERTAR -->
    <?php if ($accion === 'insertar'): ?>
    <div class="card card-accent-green" style="margin-bottom:20px;">
        <h2 style="margin-bottom:20px;">➕ Nueva atracción</h2>
        <form method="POST" action="?accion=insertar">
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px;">
                <div class="form-group">
                    <label>Nombre de la atracción *</label>
                    <input type="text" name="nombre" required placeholder="Ej: La Montaña Rusa del Gredos">
                </div>
                <div class="form-group">
                    <label>Temática *</label>
                    <input type="text" name="tematica" required placeholder="Ej: Aventura, Historia...">
                </div>
            </div>
            <div class="form-group">
                <label>Descripción</label>
                <textarea name="descripcion" rows="3" placeholder="Descripción de la atracción..."></textarea>
            </div>
            <div style="display:flex;gap:10px;margin-top:6px;">
                <button type="submit" class="btn btn-primary">Guardar atracción</button>
                <a href="atracciones.php" class="btn btn-secondary">Cancelar</a>
            </div>
        </form>
    </div>

    <!-- FORM: EDITAR -->
    <?php elseif ($accion === 'modificar' && $atraccionEditar): ?>
    <div class="card card-accent-blue" style="margin-bottom:20px;">
        <h2 style="margin-bottom:20px;">✏️ Editar atracción</h2>
        <form method="POST" action="?accion=modificar&id=<?= $atraccionEditar['id'] ?>">
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px;">
                <div class="form-group">
                    <label>Nombre de la atracción *</label>
                    <input type="text" name="nombre" required value="<?= htmlspecialchars($atraccionEditar['nombre']) ?>">
                </div>
                <div class="form-group">
                    <label>Temática *</label>
                    <input type="text" name="tematica" required value="<?= htmlspecialchars($atraccionEditar['tematica']) ?>">
                </div>
            </div>
            <div class="form-group">
                <label>Descripción</label>
                <textarea name="descripcion" rows="3"><?= htmlspecialchars($atraccionEditar['descripcion'] ?? '') ?></textarea>
            </div>
            <div style="display:flex;gap:10px;margin-top:6px;">
                <button type="submit" class="btn btn-primary">Actualizar</button>
                <a href="atracciones.php" class="btn btn-secondary">Cancelar</a>
            </div>
        </form>
    </div>
    <?php endif; ?>

    <!-- TABLA: LISTAR -->
    <div class="card">
        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:18px;">
            <h2 style="margin-bottom:0;">📋 Todas las atracciones</h2>
            <?php if ($atracciones): ?>
            <span style="font-size:12px;color:#7a7880;"><?= $atracciones->num_rows ?> registros</span>
            <?php endif; ?>
        </div>

        <?php if ($atracciones && $atracciones->num_rows > 0): ?>
        <div style="overflow-x:auto;">
            <table>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Nombre</th>
                        <th>Temática</th>
                        <th>Descripción</th>
                        <th>Viajes</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($a = $atracciones->fetch_assoc()): ?>
                    <tr>
                        <td style="color:#3a3a3e;font-size:12px;"><?= $a['id'] ?></td>
                        <td><strong><?= htmlspecialchars($a['nombre']) ?></strong></td>
                        <td><span class="pill pill-blue"><?= htmlspecialchars($a['tematica']) ?></span></td>
                        <td style="font-size:12px;color:#7a7880;max-width:200px;">
                            <?= htmlspecialchars(substr($a['descripcion'] ?? '', 0, 80)) ?>
                            <?= strlen($a['descripcion'] ?? '') > 80 ? '…' : '' ?>
                        </td>
                        <td>
                            <span class="pill pill-yellow"><?= $a['total_viajes'] ?></span>
                        </td>
                        <td>
                            <div style="display:flex;gap:6px;">
                                <a href="?accion=modificar&id=<?= $a['id'] ?>" class="btn btn-secondary btn-sm">✏️ Editar</a>
                                <a href="?accion=borrar&id=<?= $a['id'] ?>"
                                   class="btn btn-danger btn-sm"
                                   onclick="return confirm('¿Eliminar la atracción <?= addslashes($a['nombre']) ?>? Se borrarán también sus viajes.')">
                                   🗑️ Borrar
                                </a>
                            </div>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
        <?php else: ?>
        <div class="empty">
            <div class="empty-icon">🎡</div>
            <p>No hay atracciones. ¡Añade la primera!</p>
        </div>
        <?php endif; ?>
    </div>
</div>
<?php require_once '../includes/footer.php'; ?>
