<?php
session_start();
include 'conexion.php';

// Control de acceso
if (!isset($_SESSION['usuario_id'])) {
    header("Location: sign_in.php");
    exit();
}

$usuario_id = $_SESSION['usuario_id'];
$denuncias = [];
$quejas = [];
$busqueda = null;

// Procesar respuesta de usuario en denuncias en curso
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id']) && isset($_POST['respuesta_usuario'])) {
    $id = $_POST['id'];
    $mensaje = trim($_POST['respuesta_usuario']);
    if ($mensaje !== '') {
        $stmt = $conexion->prepare("
            UPDATE complaints 
            SET respuesta_admin = CONCAT(IFNULL(respuesta_admin, ''), '\nUsuario: ', ?) 
            WHERE id = ? AND usuario_id = ? AND estado = 'En curso'
        ");
        $stmt->bind_param("sii", $mensaje, $id, $usuario_id);
        $stmt->execute();
        header("Location: my_complaints.php");
        exit();
    }
}



// Búsqueda por código
if (isset($_POST['codigo_seguimiento']) && trim($_POST['codigo_seguimiento']) !== '') {
    $codigo = trim($_POST['codigo_seguimiento']);
    $stmt = $conexion->prepare("
        SELECT id, codigo_seguimiento, tipo, department, complaint, photo1, photo2, photo3, estado, respuesta_admin
        FROM complaints 
        WHERE usuario_id = ? 
        AND codigo_seguimiento = ?
        LIMIT 1
    ");
    $stmt->bind_param("is", $usuario_id, $codigo);
    $stmt->execute();
    $result = $stmt->get_result();
    $busqueda = $result->num_rows > 0 ? $result->fetch_assoc() : null;
} else {
    // Obtener DENUNCIAS
    $stmt = $conexion->prepare("
        SELECT id, codigo_seguimiento, tipo, department, complaint, photo1, photo2, photo3, estado, respuesta_admin
        FROM complaints 
        WHERE usuario_id = ? AND tipo = 'denuncia'
        ORDER BY created_at DESC
    ");
    $stmt->bind_param("i", $usuario_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $denuncias = $result->fetch_all(MYSQLI_ASSOC);

    // Obtener QUEJAS
    $stmt = $conexion->prepare("
        SELECT id, codigo_seguimiento, tipo, department, complaint, photo1, photo2, photo3, estado, respuesta_admin
        FROM complaints 
        WHERE usuario_id = ? AND tipo = 'queja'
        ORDER BY created_at DESC
    ");
    $stmt->bind_param("i", $usuario_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $quejas = $result->fetch_all(MYSQLI_ASSOC);
}

// Función para mostrar denuncia/queja
function mostrarDenuncia($d)
{
    $idUnico = preg_replace('/[^a-zA-Z0-9_-]/', '', $d['codigo_seguimiento']);
    $clases = ($d['tipo'] === 'Queja' ? 'queja' : 'denuncia');
    if ($d['estado'] === 'Respondida') $clases .= ' respondida';
    if ($d['estado'] === 'En curso') $clases .= ' en-curso';
?>
    <div class="<?= $clases ?>" data-id="<?= htmlspecialchars($d['id']) ?>">
        <p><strong>Código:</strong> <?= htmlspecialchars($d['codigo_seguimiento']) ?></p>
        <p><strong>Estado:</strong>
            <?php if ($d['estado'] === 'Esperando respuesta'): ?>
                <em>Esperando respuesta del administrador</em>
            <?php else: ?>
                <em><?= htmlspecialchars($d['estado']) ?></em>
            <?php endif; ?>
        </p>
        <p><strong>Departamento:</strong> <?= htmlspecialchars($d['department']) ?></p>
        <p><strong>Descripción:</strong> <?= nl2br(htmlspecialchars($d['complaint'])) ?></p>
        <?php
for ($i = 1; $i <= 3; $i++) {
    $photoKey = "photo$i";
    if (!empty($d[$photoKey])):
?>
    <div class="imagen-contenedor">
        <a href="javascript:void(0);" onclick="mostrarImagen('<?= $idUnico ?>_<?= $i ?>')" style="margin-right:10px;color:#004d66;text-decoration:none;">
            Ver imagen <?= $i ?>
        </a>
        <div id="imagen-<?= $idUnico ?>_<?= $i ?>" style="display:none; text-align:center; margin-top:10px;">
            <img src="<?= htmlspecialchars($d[$photoKey]) ?>" alt="Imagen <?= $i ?>" style="max-width:300px;border-radius:8px;">
            <div style="margin-top:5px;">
                <a href="<?= htmlspecialchars($d[$photoKey]) ?>" download style="color:#fff;background:#004d66;padding:5px 10px;border-radius:5px;text-decoration:none;">
                    Descargar imagen <?= $i ?>
                </a>
            </div>
        </div>
    </div>
<?php
    endif;
}
?>

        <?php if ($d['estado'] === 'Respondida' || $d['estado'] === 'En curso'): ?>
            <!-- Procesar la conversación -->
            <?php $mensajes = preg_split('/\n/', $d['respuesta_admin']); ?>
            <div class="conversacion-chat" id="chat-<?= $d['id'] ?>">
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
        <?php if ($d['estado'] === 'En curso'): ?>
            <form method="post">
                <input type="hidden" name="id" value="<?= $d['id'] ?>">
                <textarea placeholder="Responder al administrador" name="respuesta_usuario" rows="3" required></textarea>
                <button type="submit" class="btn-enviar-usuario">Enviar respuesta</button>
            </form>
        <?php endif; ?>
        <?php if ($d['estado'] === 'Respondida'): ?>
            <button type="button" class="btn-eliminar">Eliminar denuncia</button>
        <?php endif; ?>
    </div>
<?php
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mis Quejas & Denuncias</title>
    <link rel="stylesheet" href="../CSS/header.css">
    <link rel="stylesheet" href="../CSS/my_complaints.css">
    <script>
        function mostrarImagen(id) {
            const contenedor = document.getElementById('imagen-' + id);
            contenedor.style.display = contenedor.style.display === 'none' ? 'block' : 'none';
        }

        // Filtrado por estado
        document.addEventListener('DOMContentLoaded', function() {
            const botonesFiltro = document.querySelectorAll('.filtro-btn');
            const seccionesFiltro = document.querySelectorAll('.seccion-filtro');

            botonesFiltro.forEach(boton => {
                boton.addEventListener('click', function() {
                    const seccion = this.getAttribute('data-section');

                    // Actualizar clases activas
                    botonesFiltro.forEach(b => b.classList.remove('active'));
                    this.classList.add('active');

                    // Mostrar/ocultar secciones
                    seccionesFiltro.forEach(s => {
                        s.style.display = s.getAttribute('id') === seccion ? 'block' : 'none';
                    });
                });
            });
        });
    </script>
</head>

<body>

    <?php include 'header.php'; ?>

    <main>
        <h1>Mis Quejas y Denuncias</h1>

        <h2 id="search-title">Buscar en mis Quejas y Denuncias</h2>
        <input type="text" id="search-bar" placeholder="Buscar por código, departamento o descripción...">
        <p id="no-results" style="display:none; color:red;">No se encontraron resultados.</p>


        <!-- Botones de tipo -->
        <div class="tipo-btns">
            <button type="button" class="tipo-btn active" data-tipo="denuncias">Denuncias</button>
            <button type="button" class="tipo-btn" data-tipo="quejas">Quejas</button>
        </div>

        <!-- Botones de filtro por estado -->
        <div class="filtro-btns">
            <button type="button" class="filtro-btn active" data-section="esperando">Esperando respuesta</button>
            <button type="button" class="filtro-btn" data-section="en-curso">En curso</button>
            <button type="button" class="filtro-btn" data-section="respondida">Respondida</button>
        </div>

        <!-- Secciones filtrables -->
        <div id="seccion-complaints">
            <!-- DENUNCIAS -->
            <div class="seccion-denuncias seccion-filtro" data-tipo="denuncias" id="esperando" style="display:block;">
                <h2>DENUNCIAS</h2>
                <h3>Esperando respuesta</h3>
                <?php foreach ($denuncias as $d): if ($d['estado'] === 'Esperando respuesta') mostrarDenuncia($d);
                endforeach; ?>
            </div>
            <div class="seccion-denuncias seccion-filtro" data-tipo="denuncias" id="en-curso" style="display:none;">
                <h2>DENUNCIAS</h2>
                <h3>En curso</h3>
                <?php foreach ($denuncias as $d): if ($d['estado'] === 'En curso') mostrarDenuncia($d);
                endforeach; ?>
            </div>
            <div class="seccion-denuncias seccion-filtro" data-tipo="denuncias" id="respondida" style="display:none;">
                <h2>DENUNCIAS</h2>
                <h3>Respondidas</h3>
                <?php foreach ($denuncias as $d): if ($d['estado'] === 'Respondida') mostrarDenuncia($d);
                endforeach; ?>
            </div>
            <!-- QUEJAS -->
            <div class="seccion-quejas seccion-filtro" data-tipo="quejas" id="esperando-queja" style="display:none;">
                <h2>QUEJAS</h2>
                <h3>Esperando respuesta</h3>
                <?php foreach ($quejas as $q): if ($q['estado'] === 'Esperando respuesta') mostrarDenuncia($q);
                endforeach; ?>
            </div>
            <div class="seccion-quejas seccion-filtro" data-tipo="quejas" id="en-curso-queja" style="display:none;">
                <h2>QUEJAS</h2>
                <h3>En curso</h3>
                <?php foreach ($quejas as $q): if ($q['estado'] === 'En curso') mostrarDenuncia($q);
                endforeach; ?>
            </div>
            <div class="seccion-quejas seccion-filtro" data-tipo="quejas" id="respondida-queja" style="display:none;">
                <h2>QUEJAS</h2>
                <h3>Respondidas</h3>
                <?php foreach ($quejas as $q): if ($q['estado'] === 'Respondida') mostrarDenuncia($q);
                endforeach; ?>
            </div>
        </div>

        <?php if ($busqueda): ?>
            <?php mostrarDenuncia($busqueda); ?>
        <?php elseif (isset($_POST['codigo_seguimiento'])): ?>
            <p>No se encontró ninguna denuncia con ese código.</p>


        <?php endif; ?>
    </main>
    <script src="../JS/delete_complaints.js"></script>
    <script src="../JS/my_complaints.js"></script>
    <script src="../JS/chat_auto_reload.js"></script>

</body>

</html>