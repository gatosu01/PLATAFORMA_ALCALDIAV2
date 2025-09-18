<?php
session_start();
include '../php/conexion.php';

if (!isset($_SESSION['usuario_id']) || $_SESSION['rol'] !== 'animal_admin') {
    header("Location: ../VIEWS/sign_in_form.php");
    exit();
}

// Actualizar status directamente a "Respondida"
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $id = intval($_POST['id']);
    $nuevo = "Respondida";

    $stmt = $conexion->prepare("UPDATE animal_complaint SET status=? WHERE id=?");
    $stmt->bind_param("si", $nuevo, $id);
    $stmt->execute();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Panel Bienestar Animal - Alcaldía de Santiago</title>
<link rel="stylesheet" href="../CSS/header_admin.css">
<link rel="stylesheet" href="../CSS/index.css">
<link rel="stylesheet" href="../CSS/mascotas.css">

</head>
<body>
<?php include '../PHP/header_animal.php'; ?>

<main>
    <h1 style="text-align:center; margin-top:1rem;">Panel Bienestar Animal</h1>

    <section class="denuncia-animal-admin">
        <h2>🚨 Denuncias Animales</h2>
        <div class="denuncias-lista">
            <?php
            $res = $conexion->query("SELECT * FROM animal_complaint where status='Esperando' ORDER BY created_at DESC");
            if($res && $res->num_rows > 0){
                while($d = $res->fetch_assoc()): ?>
                    <div class="denuncia-card">
                        <p><strong>Ubicación:</strong> <?= htmlspecialchars($d['pet_location']) ?></p>
                        <p><strong>Color de Casa:</strong> <?= htmlspecialchars($d['house_color']) ?></p>
                        <p><strong>Descripción:</strong> <?= htmlspecialchars($d['pet_description']) ?></p>

        

                        <?php if(!empty($d['photo'])): ?>
                            <img src="../UPLOADS/D_animal/<?= htmlspecialchars($d['photo']) ?>" alt="Foto denuncia">

                            <div class="opciones-imagen">
                                <a href="../UPLOADS/D_animal/<?= htmlspecialchars($d['photo']) ?>" target="_blank" class="btn-ver">👁️ Vista previa</a>
                                <a href="../UPLOADS/D_animal/<?= htmlspecialchars($d['photo']) ?>" download class="btn-descargar">⬇️ Descargar</a>
                            </div>
                        <?php endif; ?>

                         <form method="post" class="status-form">
                            <input type="hidden" name="id" value="<?= $d['id'] ?>">
                            <button type="submit" class="btn-respondida">✅ Respondida</button>
                        </form>
                    </div>
                <?php endwhile;
            } else {
                echo "<p>No hay denuncias registradas aún.</p>";
            }
            ?>
        </div>
    </section>
</main>
</body>
</html>
