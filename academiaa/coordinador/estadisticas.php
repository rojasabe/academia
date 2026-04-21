<?php
include '../db/sesion.php';
include '../db/conexion.php';
requerir_rol('coordinador');

$prom_grupo = mysqli_query($conexion, "SELECT g.nombre AS grupo, AVG(c.calificacion) AS promedio
  FROM calificaciones c
  INNER JOIN alumno_grupo ag ON c.estudiante_id = ag.alumno_id
  INNER JOIN grupos g ON ag.grupo_id = g.id
  GROUP BY g.id ORDER BY g.nombre ASC");

$prom_materia = mysqli_query($conexion, "SELECT m.nombre AS materia, AVG(c.calificacion) AS promedio
  FROM calificaciones c
  INNER JOIN materias m ON c.materia_id = m.id
  GROUP BY m.id ORDER BY m.nombre ASC");

$sin_subir = mysqli_query($conexion, "SELECT e.id, e.nombre, e.apellido
  FROM estudiantes e
  WHERE e.tipo = 'profesor'
  AND e.id NOT IN (
    SELECT DISTINCT pgm.profesor_id
    FROM profesor_grupo_materia pgm
    INNER JOIN calificaciones c ON c.materia_id = pgm.materia_id
  )
  ORDER BY e.apellido ASC");
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Estadisticas</title>
  <link rel="stylesheet" href="../assets/estilos.css">
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined">
</head>

<body>

<div class="panel">

  <nav class="menuprinci">
    <div class="perfil"><?php echo $_SESSION['usuario']; ?></div>
    <p style="color: #cdaa80; font-size: 12px; margin-bottom: 10px;"><?php echo $_SESSION['tipo']; ?></p>
    <ul>
      <li onclick="window.location.href='../comun/registro.php'">
        <span class="material-symbols-outlined icono">home</span> Inicio
      </li>
      <li onclick="window.location.href='profesores.php'">
        <span class="material-symbols-outlined icono">school</span> Profesores
      </li>
      <li onclick="window.location.href='alumnos.php'">
        <span class="material-symbols-outlined icono">group</span> Alumnos
      </li>
      <li onclick="window.location.href='asignaciones.php'">
        <span class="material-symbols-outlined icono">assignment_ind</span> Asignaciones
      </li>
      <li onclick="window.location.href='estadisticas.php'">
        <span class="material-symbols-outlined icono">insights</span> Estadisticas
      </li>
      <li onclick="window.location.href='../comun/cerrar_sesion.php'">
        <span class="material-symbols-outlined icono">logout</span> Cerrar Sesion
      </li>
    </ul>
  </nav>

  <main class="contenido">
    <div class="formulario" style="width: 800px; max-width: 95%;">
      <h2>Estadisticas Globales</h2>

      <h3 style="margin-top: 10px; color: #0f1e3f;">Promedio por Grupo</h3>
      <table class="tabla-lista" style="margin-top: 10px;">
        <tr>
          <th>Grupo</th>
          <th>Promedio</th>
        </tr>
        <?php while ($r = mysqli_fetch_assoc($prom_grupo)): ?>
        <tr>
          <td><?php echo $r['grupo']; ?></td>
          <td><?php echo number_format($r['promedio'], 2); ?></td>
        </tr>
        <?php endwhile; ?>
      </table>

      <h3 style="margin-top: 30px; color: #0f1e3f;">Promedio por Materia</h3>
      <table class="tabla-lista" style="margin-top: 10px;">
        <tr>
          <th>Materia</th>
          <th>Promedio</th>
        </tr>
        <?php while ($r = mysqli_fetch_assoc($prom_materia)): ?>
        <tr>
          <td><?php echo $r['materia']; ?></td>
          <td><?php echo number_format($r['promedio'], 2); ?></td>
        </tr>
        <?php endwhile; ?>
      </table>

      <h3 style="margin-top: 30px; color: #0f1e3f;">Profesores sin Calificaciones</h3>
      <table class="tabla-lista" style="margin-top: 10px;">
        <tr>
          <th>Nombre</th>
          <th>Apellido</th>
        </tr>
        <?php while ($r = mysqli_fetch_assoc($sin_subir)): ?>
        <tr>
          <td><?php echo $r['nombre']; ?></td>
          <td><?php echo $r['apellido']; ?></td>
        </tr>
        <?php endwhile; ?>
      </table>
    </div>
  </main>
</div>

</body>
</html>
