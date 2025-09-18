<?php
session_start();
include __DIR__ . '/../Models/Conexion.php';
$conexion = (new \App\Models\Conexion())->getConexion();
if (!isset($_SESSION['usuario_id']) || $_SESSION['rol'] !== 'admin') {
    header("Location: /Alcaldia/app/Views/sign_in.php");
    exit();
}
// Guardar mensajes y cambiar estados
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $id = $_POST['id'];
    $respuesta = trim($_POST['respuesta'] ?? '');
    // Marcar "En curso" y enviar primer mensaje
    if (isset($_POST['accion']) && $_POST['accion'] === 'en_curso' && !empty($respuesta)) {
        $stmt = $conexion->prepare("UPDATE complaints SET respuesta_admin = CONCAT(IFNULL(respuesta_admin, ''), IF(respuesta_admin IS NULL OR respuesta_admin = '', ?, CONCAT('\nadministrador ', ?))), estado = 'En curso' WHERE id = ?");
        $stmt->execute([$respuesta, $respuesta, $id]);
    }
    // Seguir conversando en "En curso"
    if (isset($_POST['accion']) && $_POST['accion'] === 'seguir' && !empty($respuesta)) {
        $stmt = $conexion->prepare("UPDATE complaints SET respuesta_admin = CONCAT(IFNULL(respuesta_admin, ''), '\nAdministrador: ', ?) WHERE id = ?");
        $stmt->execute([$respuesta, $id]);
    }
    // Finalizar conversación
    if (isset($_POST['accion']) && $_POST['accion'] === 'finalizar' && !empty($respuesta)) {
        $stmt = $conexion->prepare("UPDATE complaints SET respuesta_admin = CONCAT(IFNULL(respuesta_admin, ''), '\n---\n', ?), estado = 'Respondida' WHERE id = ?");
        $stmt->execute([$respuesta, $id]);
    }
}
// Consultas separadas para denuncias
$queryEsperando = "SELECT c.*, u.nombre AS nombre_usuario, u.apellido AS apellido_usuario FROM complaints c LEFT JOIN usuarios u ON c.usuario_id = u.id WHERE c.tipo = 'denuncia' AND c.estado = 'Esperando respuesta' ORDER BY c.created_at DESC";
$queryEnCurso = "SELECT c.*, u.nombre AS nombre_usuario, u.apellido AS apellido_usuario FROM complaints c LEFT JOIN usuarios u ON c.usuario_id = u.id WHERE c.tipo = 'denuncia' AND c.estado = 'En curso' ORDER BY c.created_at DESC";
$resultEsperando = $conexion->query($queryEsperando);
$resultEnCurso = $conexion->query($queryEnCurso);
if (!$resultEsperando || !$resultEnCurso) {
    die("Error en la consulta: " . $conexion->errorInfo()[2]);
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Denuncias - Panel Administrativo</title>
    <link rel="stylesheet" href="/Alcaldia/public/CSS/reports_admin.css" />
    <link rel="stylesheet" href="/Alcaldia/public/CSS/header_admin.css" />
</head>
<body>
    <?php include __DIR__ . '/header_admin.php'; ?>
    <main>
        <h1>Denuncias Recibidas</h1>
        <div class="filtro-btns">
            <button type="button" class="filtro-btn active" data-section="esperando">Esperando respuesta</button>
            <button type="button" class="filtro-btn" data-section="en-curso">En curso</button>
        </div>
        <div class="seccion-filtro" id="esperando" style="display:block;">
            <h2>Esperando Respuesta</h2>
            <?php
            if ($resultEsperando->rowCount() > 0):
                $codigosMostrados = [];
                foreach ($resultEsperando as $row):
                    if (in_array($row['codigo_seguimiento'], $codigosMostrados)) continue;
                    $codigosMostrados[] = $row['codigo_seguimiento'];
            ?>
                    <div class="reporte denuncia">
                        <p><strong>Nombre:</strong>
                            <?php
                            if ($row['nombre_usuario']) {
                                echo htmlspecialchars($row['nombre_usuario'] . " " . $row['apellido_usuario']);
                            } else {
                                echo "Anónimo";
                            }
                            ?>
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
                        <p><strong>Detalle de la denuncia:</strong> <?= nl2br(htmlspecialchars($row['complaint'])) ?></p>
                        <?php if (!empty($row['photo'])): ?>
                            <p><strong>Imagen:</strong></p>
                            <img src="<?= htmlspecialchars($row['photo']) ?>" alt="Imagen de la denuncia" style="max-width:300px;border-radius:8px;">
                            <div style="margin-top:5px;">
                                <a href="<?= htmlspecialchars($row['photo']) ?>" target="_blank" style="margin-right:10px;color:#004d66;text-decoration:none;">
                                    Ver en tamaño real
                                </a>
                                <a href="<?= htmlspecialchars($row['photo']) ?>" download style="color:#fff;background:#004d66;padding:5px 10px;border-radius:5px;text-decoration:none;">
                                    Descargar imagen
                                </a>
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
                <?php endforeach; ?>
            <?php else: ?>
                <p>No hay denuncias esperando respuesta.</p>
            <?php endif; ?>
        </div>

        <div class="seccion-filtro" id="en-curso" style="display:none;">
            <h2>En Curso</h2>
            <?php
            if ($resultEnCurso->rowCount() > 0):
                $codigosMostrados = [];
                foreach ($resultEnCurso as $row):
                    if (in_array($row['codigo_seguimiento'], $codigosMostrados)) continue;
                    $codigosMostrados[] = $row['codigo_seguimiento'];
            ?>
                    <div class="reporte denuncia">
                        <p><strong>Nombre:</strong>
                            <?php
                            if ($row['nombre_usuario']) {
                                echo htmlspecialchars($row['nombre_usuario'] . " " . $row['apellido_usuario']);
                            } else {
                                echo "Anónimo";
                            }
                            ?>
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
                        <p><strong>Detalle de la denuncia:</strong> <?= nl2br(htmlspecialchars($row['complaint'])) ?></p>
                        <?php if (!empty($row['photo'])): ?>
                            <p><strong>Imagen:</strong></p>
                            <img src="<?= htmlspecialchars($row['photo']) ?>" alt="Imagen de la denuncia" style="max-width:300px;border-radius:8px;">
                            <div style="margin-top:5px;">
                                <a href="<?= htmlspecialchars($row['photo']) ?>" target="_blank" style="margin-right:10px;color:#004d66;text-decoration:none;">
                                    Ver en tamaño real
                                </a>
                                <a href="<?= htmlspecialchars($row['photo']) ?>" download style="color:#fff;background:#004d66;padding:5px 10px;border-radius:5px;text-decoration:none;">
                                    Descargar imagen
                                </a>
                            </div>
                        <?php endif; ?>
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
                                <?php endif;
                                endforeach; ?>
                            </div>
                        <?php endif; ?>
                        <form class="form-chat-ajax-admin" data-id="<?= $row['id'] ?>">
                            <input type="hidden" name="id" value="<?= $row['id'] ?>">
                            <label>Nuevo mensaje:</label>
                            <textarea name="respuesta" rows="3" required></textarea>
                            <div class="botones-estado">
                                <button type="submit" name="accion" value="seguir" class="btn-en-curso">Seguir Conversando</button>
                                <button type="submit" name="accion" value="finalizar" class="btn-finalizar">Finalizar</button>
                            </div>
                        </form>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>No hay denuncias en curso.</p>
            <?php endif; ?>
        </div>
    <script src="/Alcaldia/public/JS/reports_admin.js"></script>
    <script src="/Alcaldia/public/JS/chat_auto_reload.js"></script>
    <script src="/Alcaldia/public/JS/chat_admin.js"></script>
    </main>
</body>
</html>
