<?php
session_start();
include 'conexion.php';

// Verificar rol admin
if (!isset($_SESSION['usuario_id']) || $_SESSION['rol'] !== 'admin') {
    header("Location: sign_in.php");
    exit();
}

// Guardar mensajes y cambiar estados
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $id = $_POST['id'];
    $respuesta = trim($_POST['respuesta'] ?? '');

    // Marcar "En curso" y enviar primer mensaje
    if (isset($_POST['accion']) && $_POST['accion'] === 'en_curso' && !empty($respuesta)) {
        $stmt = $conexion->prepare("
            UPDATE complaints 
            SET respuesta_admin = CONCAT(IFNULL(respuesta_admin, ''), IF(respuesta_admin IS NULL OR respuesta_admin = '', ?, CONCAT('\n---\n', ?))), 
                estado = 'En curso' 
            WHERE id = ?
        ");
        $stmt->bind_param("ssi", $respuesta, $respuesta, $id);
        $stmt->execute();
    }

    // Seguir conversando en "En curso"
    if (isset($_POST['accion']) && $_POST['accion'] === 'seguir' && !empty($respuesta)) {
        $stmt = $conexion->prepare("
            UPDATE complaints 
            SET respuesta_admin = CONCAT(IFNULL(respuesta_admin, ''), '\nAdministrador: ', ?) 
            WHERE id = ?
        ");
        $stmt->bind_param("si", $respuesta, $id);
        $stmt->execute();
    }

    // Finalizar conversación
    if (isset($_POST['accion']) && $_POST['accion'] === 'finalizar' && !empty($respuesta)) {
        $stmt = $conexion->prepare("
            UPDATE complaints 
            SET respuesta_admin = CONCAT(IFNULL(respuesta_admin, ''), '\n---\n', ?), 
                estado = 'Respondida' 
            WHERE id = ?
        ");
        $stmt->bind_param("si", $respuesta, $id);
        $stmt->execute();
    }

    // Redirigir con la sección activa
    $seccion = 'esperando';
    if ($_POST['accion'] === 'seguir' || $_POST['accion'] === 'en_curso') {
        $seccion = 'en-curso';
    }
    header("Location: reports_admin.php?seccion=$seccion");
    exit();
}

// Determinar sección activa
$seccion_activa = $_GET['seccion'] ?? 'esperando';

// Consultas separadas
$queryEsperando = "
    SELECT c.*, u.nombre AS nombre_usuario, u.apellido AS apellido_usuario
    FROM complaints c
    LEFT JOIN usuarios u ON c.usuario_id = u.id
    WHERE c.tipo = 'denuncia' AND c.estado = 'Esperando respuesta'
    ORDER BY c.created_at DESC
";
$queryEnCurso = "
    SELECT c.*, u.nombre AS nombre_usuario, u.apellido AS apellido_usuario
    FROM complaints c
    LEFT JOIN usuarios u ON c.usuario_id = u.id
    WHERE c.tipo = 'denuncia' AND c.estado = 'En curso'
    ORDER BY c.created_at DESC
";

$resultEsperando = $conexion->query($queryEsperando);
$resultEnCurso = $conexion->query($queryEnCurso);

if (!$resultEsperando || !$resultEnCurso) {
    die("Error en la consulta: " . $conexion->error);
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Denuncias - Panel Administrativo</title>
    <link rel="stylesheet" href="../CSS/header.css" />
    <link rel="stylesheet" href="../CSS/reports_admin.css" />
    <link rel="stylesheet" href="../CSS/header_admin.css" />
</head>

<body>
    <?php include 'header_admin.php'; ?>
    <main>
        <h1>Denuncias Recibidas</h1>

        <!-- Botones de filtro -->
        <div class="filtro-btns">
            <button type="button" class="filtro-btn <?= $seccion_activa === 'esperando' ? 'active' : '' ?>" data-section="esperando">Esperando respuesta</button>
            <button type="button" class="filtro-btn <?= $seccion_activa === 'en-curso' ? 'active' : '' ?>" data-section="en-curso">En curso</button>
        </div>

        <!------------------------------------------------------ Sección Esperando Respuesta ----------------------------------------------------->
        <div class="seccion-filtro" id="esperando" style="display:<?= $seccion_activa === 'esperando' ? 'block' : 'none' ?>;">
            <h2>Esperando Respuesta</h2>
            <?php if ($resultEsperando->num_rows > 0): ?>
                <?php while ($row = $resultEsperando->fetch_assoc()): ?>
                    <div class="reporte denuncia" data-id="<?= $row['id'] ?>">
                        <p><strong>Nombre:</strong>
                            <?= $row['usuario_id'] && $row['nombre_usuario']
                                ? htmlspecialchars($row['nombre_usuario'] . " " . $row['apellido_usuario'])
                                : htmlspecialchars($row['nombre_anonimo'] ?: "Anónimo") ?>
                        </p>
                        <p><strong>Código:</strong> <?= htmlspecialchars($row['codigo_seguimiento']) ?></p>
                        <p><strong>Departamento:</strong> <?= htmlspecialchars($row['department']) ?></p>
                        <p><strong>Latitud:</strong> <?= htmlspecialchars($row['lat']) ?></p>
                        <p><strong>Longitud:</strong> <?= htmlspecialchars($row['lng']) ?></p>
                        <p>
                          <strong>Ubicación en Google Maps:</strong>
                          <a href="https://www.google.com/maps?q=<?= urlencode($row['lat']) ?>,<?= urlencode($row['lng']) ?>" target="_blank">
                            Ver ubicación
                          </a>
                        </p>
                        <p><strong>Ubicación exacta:</strong> <?= htmlspecialchars($row['ubication']) ?></p>
                        <p><strong>Detalle de la denuncia:</strong> <?= nl2br(htmlspecialchars($row['complaint'])) ?></p>

                        <?php 
                        $fotos = array_filter([$row['photo1'], $row['photo2'], $row['photo3']]);
                        if (!empty($fotos)): ?>
                            <p><strong>Imágenes:</strong></p>
                            <div style="display:flex; gap:10px; flex-wrap:wrap;">
                                <?php foreach ($fotos as $foto): ?>
                                    <div style="text-align:center;">
                                        <img src="<?= htmlspecialchars($foto) ?>" alt="Imagen de la denuncia" style="max-width:200px; border-radius:8px; margin-bottom:5px;">
                                        <div>
                                            <a href="<?= htmlspecialchars($foto) ?>" target="_blank" style="margin-right:10px;color:#004d66;text-decoration:none;">Ver tamaño real</a>
                                            <a href="<?= htmlspecialchars($foto) ?>" download style="color:#fff;background:#004d66;padding:5px 10px;border-radius:5px;text-decoration:none;">Descargar</a>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>

                        <!-- conversacion -->
                        <?php if (!empty($row['respuesta_admin'])): 
                            $mensajes = preg_split('/\n/', $row['respuesta_admin']); ?>
                            <div class="conversacion-chat" id="chat-<?= $row['id'] ?>">
                                <?php foreach ($mensajes as $msg):
                                    $msg = trim($msg);
                                    if ($msg === '') continue;
                                    if (stripos($msg, 'Usuario:') === 0): ?>
                                        <div class="mensaje usuario"><?= htmlspecialchars($msg) ?></div>
                                    <?php else: ?>
                                        <div class="mensaje admin"><?= htmlspecialchars($msg) ?></div>
                                <?php endif; endforeach; ?>
                            </div>
                        <?php endif; ?>

                        <form method="post">
                            <input type="hidden" name="id" value="<?= $row['id'] ?>">
                            <label>Mensaje del Administrador:</label>
                            <textarea name="respuesta" rows="3" required></textarea>
                            <div class="botones-estado">
                                <button type="submit" name="accion" value="en_curso" class="btn-en-curso">En Curso</button>
                                <button type="submit" name="accion" value="finalizar" class="btn-finalizar">Finalizar</button>
                            </div>
                        </form>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p>No hay denuncias esperando respuesta.</p>
            <?php endif; ?>
        </div>

        <!------------------------------------------------------------- Sección En Curso ---------------------------------------------------------------->
        <div class="seccion-filtro" id="en-curso" style="display:<?= $seccion_activa === 'en-curso' ? 'block' : 'none' ?>;">
            <h2>En Curso</h2>
            <?php if ($resultEnCurso->num_rows > 0): ?>
                <?php while ($row = $resultEnCurso->fetch_assoc()): ?>
                    <div class="reporte denuncia" data-id="<?= $row['id'] ?>">
                        <p><strong>Nombre:</strong>
                            <?= $row['usuario_id'] && $row['nombre_usuario']
                                ? htmlspecialchars($row['nombre_usuario'] . " " . $row['apellido_usuario'])
                                : htmlspecialchars($row['nombre_anonimo'] ?: "Anónimo") ?>
                        </p>
                        <p><strong>Código:</strong> <?= htmlspecialchars($row['codigo_seguimiento']) ?></p>
                        <p><strong>Departamento:</strong> <?= htmlspecialchars($row['department']) ?></p>
                        <p><strong>Latitud:</strong> <?= htmlspecialchars($row['lat']) ?></p>
                        <p><strong>Longitud:</strong> <?= htmlspecialchars($row['lng']) ?></p>
                        <p>
                          <strong>Ubicación en Google Maps:</strong>
                          <a href="https://www.google.com/maps?q=<?= urlencode($row['lat']) ?>,<?= urlencode($row['lng']) ?>" target="_blank">
                            Ver ubicación
                          </a>
                        </p>
                        <p><strong>Ubicación exacta:</strong> <?= htmlspecialchars($row['ubication']) ?></p>
                        <p><strong>Detalle de la denuncia:</strong> <?= nl2br(htmlspecialchars($row['complaint'])) ?></p>

                        <?php 
                        $fotos = array_filter([$row['photo1'], $row['photo2'], $row['photo3']]);
                        if (!empty($fotos)): ?>
                            <p><strong>Imágenes:</strong></p>
                            <div style="display:flex; gap:10px; flex-wrap:wrap;">
                                <?php foreach ($fotos as $foto): ?>
                                    <div style="text-align:center;">
                                        <img src="<?= htmlspecialchars($foto) ?>" alt="Imagen de la denuncia" style="max-width:200px; border-radius:8px; margin-bottom:5px;">
                                        <div>
                                            <a href="<?= htmlspecialchars($foto) ?>" target="_blank" style="margin-right:10px;color:#004d66;text-decoration:none;">Ver tamaño real</a>
                                            <a href="<?= htmlspecialchars($foto) ?>" download style="color:#fff;background:#004d66;padding:5px 10px;border-radius:5px;text-decoration:none;">Descargar</a>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>

                        <!-- conversacion tipo chat -->
                        <?php if (!empty($row['respuesta_admin'])): 
                            $mensajes = preg_split('/\n/', $row['respuesta_admin']); ?>
                            <div class="conversacion-chat" id="chat-<?= $row['id'] ?>">
                                <?php foreach ($mensajes as $msg):
                                    $msg = trim($msg);
                                    if ($msg === '') continue;
                                    if (stripos($msg, 'Usuario:') === 0): ?>
                                        <div class="mensaje usuario"><?= htmlspecialchars($msg) ?></div>
                                    <?php else: ?>
                                        <div class="mensaje admin"><?= htmlspecialchars($msg) ?></div>
                                <?php endif; endforeach; ?>
                            </div>
                        <?php endif; ?>

                        <form method="post">
                            <input type="hidden" name="id" value="<?= $row['id'] ?>">
                            <label>Nuevo mensaje:</label>
                            <textarea name="respuesta" rows="3" required></textarea>
                            <div class="botones-estado">
                                <button type="submit" name="accion" value="seguir" class="btn-en-curso">Seguir Conversando</button>
                                <button type="submit" name="accion" value="finalizar" class="btn-finalizar">Finalizar</button>
                            </div>
                        </form>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p>No hay denuncias en curso.</p>
            <?php endif; ?>
        </div>
    </main>
    <script src="../JS/reports_admin.js"></script><!-- BOTONES DE SECCION -->
    <script src="../JS/chat_auto_reload.js"></script>
</body>
</html>
