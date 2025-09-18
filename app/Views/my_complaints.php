<?php
// Mostrar conteo para depuración visual
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$denuncias = $denuncias ?? [];
$quejas = $quejas ?? [];
$busqueda = $busqueda ?? null;

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
            <?php
            // Si la ruta no inicia con /Alcaldia/public/UPLOADS/, la ajustamos
            $imgPath = $d[$photoKey];
            if (strpos($imgPath, 'UPLOADS/') === 0) {
                $imgPath = '/Alcaldia/UPLOADS/' . $imgPath;
            } elseif (strpos($imgPath, '/Alcaldia/UPLOADS/complaints/') !== 0) {
                $imgPath = '/Alcaldia/UPLOADS/complaints/' . basename($imgPath);
            }
            ?>
            <img src="<?= htmlspecialchars($imgPath) ?>" alt="Imagen <?= $i ?>" style="max-width:300px;border-radius:8px;">
            <div style="margin-top:5px;">
                <a href="<?= htmlspecialchars($imgPath) ?>" download style="color:#fff;background:#004d66;padding:5px 10px;border-radius:5px;text-decoration:none;">
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
            <form class="form-chat-ajax" data-id="<?= $d['id'] ?>">
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
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.form-chat-ajax').forEach(form => {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                const id = form.dataset.id;
                const textarea = form.querySelector('textarea[name="respuesta_usuario"]');
                const mensaje = textarea.value.trim();
                if (!mensaje) return;
                form.querySelector('button').disabled = true;
                fetch('/Alcaldia/public/send_message.php?action=sendMessage', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                    body: `id=${encodeURIComponent(id)}&mensaje=${encodeURIComponent(mensaje)}&tipo=usuario`
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        // Actualizar el chat
                        fetch(`/Alcaldia/public/send_message.php?action=getChat&id=${id}`)
                            .then(res => res.text())
                            .then(html => {
                                const chatDiv = document.getElementById('chat-' + id);
                                if (chatDiv) {
                                    chatDiv.innerHTML = html;
                                    chatDiv.scrollTop = chatDiv.scrollHeight;
                                }
                                textarea.value = '';
                                form.querySelector('button').disabled = false;
                            });
                    } else {
                        alert('Error al enviar mensaje');
                        form.querySelector('button').disabled = false;
                    }
                })
                .catch(() => {
                    alert('Error de red');
                    form.querySelector('button').disabled = false;
                });
            });
        });
    });
    </script>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mis Quejas & Denuncias</title>
    <link rel="stylesheet" href="/Alcaldia/public/CSS/header.css">
    <link rel="stylesheet" href="/Alcaldia/public/CSS/my_complaints.css">
    <script src="/Alcaldia/public/JS/delete_complaints.js"></script>
    <script src="/Alcaldia/public/JS/my_complaints.js"></script>
    <script src="/Alcaldia/public/JS/chat_auto_reload.js"></script>
    <script>
        function mostrarImagen(id) {
            const contenedor = document.getElementById('imagen-' + id);
            if (!contenedor) return;
            if (contenedor.style.display === 'none' || contenedor.style.display === '') {
                contenedor.style.display = 'block';
                contenedor.scrollIntoView({ behavior: 'smooth', block: 'center' });
            } else {
                contenedor.style.display = 'none';
            }
        }
        document.addEventListener('DOMContentLoaded', function() {
            const botonesFiltro = document.querySelectorAll('.filtro-btn');
            const seccionesFiltro = document.querySelectorAll('.seccion-filtro');
            botonesFiltro.forEach(boton => {
                boton.addEventListener('click', function() {
                    const seccion = this.getAttribute('data-section');
                    botonesFiltro.forEach(b => b.classList.remove('active'));
                    this.classList.add('active');
                    seccionesFiltro.forEach(s => {
                        s.style.display = s.getAttribute('id') === seccion ? 'block' : 'none';
                    });
                });
            });
        });
        </script>
        <script>
        document.addEventListener('DOMContentLoaded', function() {
            var chats = document.querySelectorAll('.conversacion-chat');
            chats.forEach(function(chat) {
                chat.scrollTop = chat.scrollHeight;
            });
        });
        </script>
</head>
<body>
    <?php include __DIR__ . '/header.php'; ?>
    <main>
        <h1>Mis Quejas y Denuncias</h1>
        <h2 id="search-title">Buscar en mis Quejas y Denuncias</h2>
        <input type="text" id="search-bar" placeholder="Buscar por código, departamento o descripción...">
        <p id="no-results" style="display:none; color:red;">No se encontraron resultados.</p>
        <div class="tipo-btns">
            <button type="button" class="tipo-btn active" data-tipo="denuncias">Denuncias</button>
            <button type="button" class="tipo-btn" data-tipo="quejas">Quejas</button>
        </div>
        <div class="filtro-btns">
            <button type="button" class="filtro-btn active" data-section="esperando">Esperando respuesta</button>
            <button type="button" class="filtro-btn" data-section="en-curso">En curso</button>
            <button type="button" class="filtro-btn" data-section="respondida">Respondida</button>
        </div>
  
    <div id="seccion-complaints">
            <div class="seccion-denuncias seccion-filtro" data-tipo="denuncias" id="esperando" style="display:block;">
                <h2>DENUNCIAS</h2>
                <h3>Esperando respuesta</h3>
                <?php
                $hay = false;
                foreach ($denuncias as $d):
                    if (trim(strtolower($d['estado'])) === 'esperando respuesta' && trim(strtolower($d['tipo'])) === 'denuncia') {
                        mostrarDenuncia($d);
                        $hay = true;
                    }
                endforeach;
                if (!$hay) echo '<div class="no-items" style="min-height:120px;display:flex;align-items:center;justify-content:center;"><p>No hay denuncias en este estado.</p></div>';
                ?>
            </div>
            <div class="seccion-denuncias seccion-filtro" data-tipo="denuncias" id="en-curso" style="display:none;">
                <h2>DENUNCIAS</h2>
                <h3>En curso</h3>
                <?php
                $hay = false;
                foreach ($denuncias as $d):
                    if (trim(strtolower($d['estado'])) === 'en curso' && trim(strtolower($d['tipo'])) === 'denuncia') {
                        mostrarDenuncia($d);
                        $hay = true;
                    }
                endforeach;
                if (!$hay) echo '<p class="no-items">No hay denuncias en este estado.</p>';
                ?>
            </div>
            <div class="seccion-denuncias seccion-filtro" data-tipo="denuncias" id="respondida" style="display:none;">
                <h2>DENUNCIAS</h2>
                <h3>Respondidas</h3>
                <?php
                $hay = false;
                foreach ($denuncias as $d):
                    if (trim(strtolower($d['estado'])) === 'respondida' && trim(strtolower($d['tipo'])) === 'denuncia') {
                        mostrarDenuncia($d);
                        $hay = true;
                    }
                endforeach;
                if (!$hay) echo '<p class="no-items">No hay denuncias en este estado.</p>';
                ?>
            </div>
            <div class="seccion-quejas seccion-filtro" data-tipo="quejas" id="esperando-queja" style="display:none;">
                <h2>QUEJAS</h2>
                <h3>Esperando respuesta</h3>
                <?php
                $hay = false;
                foreach ($quejas as $q):
                    if (trim(strtolower($q['estado'])) === 'esperando respuesta' && trim(strtolower($q['tipo'])) === 'queja') {
                        mostrarDenuncia($q);
                        $hay = true;
                    }
                endforeach;
                if (!$hay) echo '<div class="no-items" style="min-height:120px;display:flex;align-items:center;justify-content:center;"><p>No hay quejas en este estado.</p></div>';
                ?>
            </div>
            <div class="seccion-quejas seccion-filtro" data-tipo="quejas" id="en-curso-queja" style="display:none;">
                <h2>QUEJAS</h2>
                <h3>En curso</h3>
                <?php
                $hay = false;
                foreach ($quejas as $q):
                    if (trim(strtolower($q['estado'])) === 'en curso' && trim(strtolower($q['tipo'])) === 'queja') {
                        mostrarDenuncia($q);
                        $hay = true;
                    }
                endforeach;
                if (!$hay) echo '<p class="no-items">No hay quejas en este estado.</p>';
                ?>
            </div>
            <div class="seccion-quejas seccion-filtro" data-tipo="quejas" id="respondida-queja" style="display:none;">
                <h2>QUEJAS</h2>
                <h3>Respondidas</h3>
                <?php
                $hay = false;
                foreach ($quejas as $q):
                    if (trim(strtolower($q['estado'])) === 'respondida' && trim(strtolower($q['tipo'])) === 'queja') {
                        mostrarDenuncia($q);
                        $hay = true;
                    }
                endforeach;
                if (!$hay) echo '<p class="no-items">No hay quejas en este estado.</p>';
                ?>
            </div>
        </div>
        <?php if ($busqueda): ?>
            <?php mostrarDenuncia($busqueda); ?>
        <?php elseif (isset($_POST['codigo_seguimiento'])): ?>
            <p>No se encontró ninguna denuncia con ese código.</p>
        <?php endif; ?>
    </main>
    <?php include __DIR__ . '/footer.php'; ?>
</body>
</html>
