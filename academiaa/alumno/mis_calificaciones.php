<?php
include '../db/sesion.php';
include '../db/conexion.php';
requerir_rol('alumno');

$id = $_SESSION['usuario_id'];
$calificaciones = mysqli_query($conexion, "SELECT m.nombre AS materia, c.calificacion
  FROM calificaciones c
  INNER JOIN materias m ON c.materia_id = m.id
  WHERE c.estudiante_id = $id");
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Mis Calificaciones</title>
  <link rel="stylesheet" href="../assets/estilos.css">
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined">
</head>

<body>

<div class="panel">

  <nav class="menuprinci">
    <div class="perfil"><?php echo $_SESSION['usuario']; ?></div>
    <p style="color: #cdaa80; font-size: 10px; margin-bottom: 10px;"><?php echo $_SESSION['tipo']; ?></p>
    <ul>
      <li onclick="window.location.href='../comun/registro.php'">
        <span class="material-symbols-outlined icono">home</span> Inicio
      </li>
      <li onclick="window.location.href='lista_estudiantes.php'">
        <span class="material-symbols-outlined icono">group</span> Compañeros
      </li>
      <li onclick="window.location.href='mis_calificaciones.php'">
        <span class="material-symbols-outlined icono">school</span> Calificaciones
      </li>
      <li onclick="window.location.href='../comun/cerrar_sesion.php'">
        <span class="material-symbols-outlined icono">logout</span> Cerrar Sesion
      </li>
    </ul>
  </nav>

  <main class="contenido">
    <div class="formulario" style="width: 500px;">
      <h2>Mis Calificaciones</h2>

      <table class="tabla-lista">
        <tr>
          <th>Materia</th>
          <th>Calificacion</th>
        </tr>
        <?php while ($fila = mysqli_fetch_assoc($calificaciones)): ?>
        <tr>
          <td><?php echo $fila['materia']; ?></td>
          <td><?php echo $fila['calificacion']; ?></td>
        </tr>
        <?php endwhile; ?>
      </table>
    </div>
  </main>

</div>

</body>
</html>
