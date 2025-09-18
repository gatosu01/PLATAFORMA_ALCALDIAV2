<?php
session_start();
include 'conexion.php';

// Verificar rol admin
if (!isset($_SESSION['usuario_id']) || $_SESSION['rol'] !== 'admin') {
    header("Location: sign_in.php");
    exit();
}

$result = $conexion->query("
    SELECT p.id, p.nombre, p.correo, p.telefono, p.archivo_pdf, p.fecha_postulacion,
           d.nombre AS departamento_nombre,
           s.nombre AS subdepartamento_nombre
    FROM postulaciones p
    LEFT JOIN departament d ON p.departamento = d.id
    LEFT JOIN subdepartamentos s ON p.subdepartamento = s.id
    ORDER BY p.fecha_postulacion DESC
");

?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Postulaciones - Admin</title>
    <link rel="stylesheet" href="../CSS/admin_postulations.css">
    <link rel="stylesheet" href="../CSS/header_admin.css">
</head>

<body>
    <?php include '../PHP/header_admin.php'; ?>

    <main>
        <section class="admin-postulations">
            <h2>Postulaciones Recibidas</h2>
            <table>
    <thead>
        <tr>
            <th>ID</th>
            <th>Nombre</th>
            <th>Correo</th>
            <th>Teléfono</th>
            <th>Departamento</th>
            <th>Área</th>
            <th>Currículum</th>
            <th>Fecha</th>
            <th>Acciones</th>
        </tr>
    </thead>
    <tbody>
        <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?= $row['id'] ?></td>
                <td><?= htmlspecialchars($row['nombre']) ?></td>
                <td><?= htmlspecialchars($row['correo']) ?></td>
                <td><?= htmlspecialchars($row['telefono']) ?></td>
                <td><?= htmlspecialchars($row['departamento_nombre']) ?></td>
                <td><?= htmlspecialchars($row['subdepartamento_nombre']) ?></td>
                <td><a href="../UPLOADS/cv/<?= $row['archivo_pdf'] ?>" target="_blank">Ver PDF</a></td>
                <td><?= $row['fecha_postulacion'] ?></td>
                <td>
                    <form action="../PHP/delete_cv.php" method="POST" onsubmit="return confirm('¿Estás seguro que deseas eliminar este CV?');">
                        <input type="hidden" name="id" value="<?= $row['id'] ?>">
                        <button type="submit" class="btn-delete">Eliminar</button>
                    </form>
                </td>
            </tr>
        <?php endwhile; ?>
    </tbody>
</table>

        </section>
    </main>

</body>

</html>