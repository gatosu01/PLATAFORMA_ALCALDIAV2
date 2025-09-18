<?php
// Adaptación para MVC: los datos deben ser pasados por el controlador
// Aquí se asume que las variables $slider_images, $notificaciones, $ultimasFaqs están disponibles
// Los includes se adaptan a la nueva estructura
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Alcaldía de Santiago</title>
    <link rel="stylesheet" href="/Alcaldia/public/CSS/index.css" />
    <link rel="stylesheet" href="/Alcaldia/public/CSS/header.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/alertifyjs@1.13.1/build/css/alertify.min.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/alertifyjs@1.13.1/build/css/themes/default.min.css" />
</head>
<body>
    <?php include __DIR__ . '/header.php'; ?>
    <main>
        <section class="title" id="INDEX">
            <h1>Bienvenido a la Alcaldía de Santiago</h1>
            <p>Conoce nuestros servicios y trámites disponibles para ti.</p>
        </section>
        <section class="botones-navegacion">
            <button class="scroll-button" data-section-id="notificaciones">🔔 Notificaciones</button>
            <button class="scroll-button" data-section-id="banco-info">💳 Pagos</button>
        </section>
        <section class="slider">
            
            <div class="slides">
                <?php if (!empty($slider_images)): ?>
                    <?php foreach ($slider_images as $index => $image): ?>
                        <input type="radio" name="radio-btn" id="radio<?= $index + 1 ?>" <?= $index === 0 ? 'checked' : '' ?> />
                    <?php endforeach; ?>
                    <?php foreach ($slider_images as $index => $image): ?>
                        <div class="slide <?= $index === 0 ? 'first' : '' ?>">
                            <img src="/Alcaldia/IMG/<?= htmlspecialchars($image['image_path']) ?>" alt="<?= htmlspecialchars($image['alt_text']) ?>">
                        </div>
                    <?php endforeach; ?>
                    <div class="navigation-manual">
                        <?php for ($i = 1; $i <= count($slider_images); $i++): ?>
                            <label for="radio<?= $i ?>" class="manual-btn"></label>
                        <?php endfor; ?>
                    </div>
                <?php endif; ?>
            </div>
        </section>
        <section class="notificaciones-slider" id="notificaciones">
            <h2>Notificaciones</h2>
            <div class="notificaciones-container">
                <?php if (!empty($notificaciones)): ?>
                    <?php foreach ($notificaciones as $n): ?>
                        <div class="notificacion">
                            <h3><?= htmlspecialchars($n['titulo']) ?></h3>
                            <p><?= nl2br(htmlspecialchars($n['mensaje'])) ?></p>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p>No hay notificaciones disponibles.</p>
                <?php endif; ?>
            </div>
        </section>
        <section class="faq">
            <h2>Preguntas Frecuentes</h2>
            <?php if (!empty($ultimasFaqs)): ?>
                <?php foreach ($ultimasFaqs as $faq): ?>
                    <div class="pregunta">
                        <h3><?= htmlspecialchars($faq['pregunta']) ?></h3>
                        <p><?= nl2br(htmlspecialchars($faq['respuesta'])) ?></p>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>No hay preguntas frecuentes disponibles.</p>
            <?php endif; ?>
        </section>
        <section class="BancoNacional" id="banco-info">
            <div class="cuadroblanco">
                <p>RECUERDE QUE PUEDE REALIZAR TU PAGO DESDE LA COMODIDAD DE TU CASA U OFICINA, A TRAVÉS DE LA BANCA EN LÍNEA A LA SIGUIENTE CUENTA:</p>
                <div class="banco">
                    <p>BANCO NACIONAL DE PANAMÁ</p>
                    <p>CUENTA#: 01-00000-30368</p>
                    <p>TESORERÍA MUNICIPAL DE SANTIAGO</p>
                    <p>CUENTA CORRIENTE</p>
                </div>
                <p><strong>NOTA:</strong> Una vez realizado el pago, envíe su comprobante de pago al correo electrónico: pagos@alcaldiadesantiago.com</p>
            </div>
        </section>
        <section class="postulate" id="postulate">
            <div class="cuadroblanco">
                <h2>Postúlate</h2>
                <p>Si deseas formar parte de nuestro equipo, envíanos tu currículum en formato PDF.</p>
                <button class="nav-button" data-href="/Alcaldia/public/postulate">Enviar CV</button>
            </div>
        </section>
        <section class="mascotas" id="mascotas">
            <div class="cuadroblanco">
                <h2>🐾 Reporta tu Mascota</h2>
                <p>Si tu mascota está perdida, repórtala aquí.</p>
                <button class="nav-button" data-href="/Alcaldia/public/reportar-mascotas">Reportar Mascota</button>
            </div>
            <div class="cuadroblanco">
                <h2>🐶 Mascotas Extraviadas</h2>
                <p>Mira todas las mascotas reportadas como extraviadas.</p>
                <button class="nav-button" data-href="/Alcaldia/public/mascotas-extraviadas">Ver Mascotas</button>
            </div>
            <div class="cuadroblanco">
                <h2>🚨 Denuncia Animal</h2>
                <p>Reporta casos de maltrato o abandono animal.</p>
                <button class="nav-button" data-href="/Alcaldia/public/denuncia-animal">Hacer Denuncia</button>
            </div>
        </section>
        <div class="mensaje-centro">
            <p>Si has encontrado a tu mascota, por favor llama al <strong>[número de contacto]</strong> de Bienestar Animal para informar sobre su ubicación. ¡Tu ayuda es muy importante! 🐾</p>
        </div>
    </main>
    <?php include __DIR__ . '/footer.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/alertifyjs@1.13.1/build/alertify.min.js"></script>
    <script src="/Alcaldia/public/JS/slider_auto.js"></script>
</body>
</html>
