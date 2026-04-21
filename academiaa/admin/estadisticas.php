<?php
include '../db/sesion.php';
include '../db/conexion.php';
requerir_rol('administrador');

$total_alumnos = mysqli_fetch_assoc(mysqli_query($conexion, "SELECT COUNT(*) AS t FROM estudiantes WHERE tipo='alumno'"))['t'];
$total_profesores = mysqli_fetch_assoc(mysqli_query($conexion, "SELECT COUNT(*) AS t FROM estudiantes WHERE tipo='profesor'"))['t'];
$total_materias = mysqli_fetch_assoc(mysqli_query($conexion, "SELECT COUNT(*) AS t FROM materias"))['t'];
$total_grupos = mysqli_fetch_assoc(mysqli_query($conexion, "SELECT COUNT(*) AS t FROM grupos"))['t'];
$total_calif = mysqli_fetch_assoc(mysqli_query($conexion, "SELECT COUNT(*) AS t FROM calificaciones"))['t'];
$promedio = mysqli_fetch_assoc(mysqli_query($conexion, "SELECT AVG(calificacion) AS p FROM calificaciones"))['p'];
//todas son consultas para saber totales para las estadísticas, con select count o avg para totales o promedios

//calcula promedios por materia 
$prom_materia = mysqli_query($conexion, "SELECT m.nombre, AVG(c.calificacion) AS promedio
  FROM calificaciones c INNER JOIN materias m ON c.materia_id = m.id
  GROUP BY m.id ORDER BY m.nombre ASC");
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Estadisticas Globales</title>
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
      <li onclick="window.location.href='configuracion.php'">
        <span class="material-symbols-outlined icono">manage_accounts</span> Usuarios
      </li>
      <li onclick="window.location.href='grupos.php'">
        <span class="material-symbols-outlined icono">groups</span> Grupos
      </li>
      <li onclick="window.location.href='../comun/alta_materias.php'">
        <span class="material-symbols-outlined icono">menu_book</span> Materias
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

      <table class="tabla-lista" style="margin-top: 10px;">
        <tr><th>Indicador</th><th>Valor</th></tr>
        <tr><td>Total de alumnos</td><td><?php echo $total_alumnos; ?></td></tr>
        <tr><td>Total de profesores</td><td><?php echo $total_profesores; ?></td></tr>
        <tr><td>Total de materias</td><td><?php echo $total_materias; ?></td></tr>
        <tr><td>Total de grupos</td><td><?php echo $total_grupos; ?></td></tr>
        <tr><td>Total de calificaciones registradas</td><td><?php echo $total_calif; ?></td></tr>
        <tr><td>Promedio general</td><td><?php echo $promedio !== null ? number_format($promedio, 2) : '-'; ?></td></tr>
      </table>

      <h3 style="margin-top: 30px; color: #0f1e3f;">Promedio por Materia</h3>
      <table class="tabla-lista" style="margin-top: 10px;">
        <tr>
          <th>Materia</th>
          <th>Promedio</th>
        </tr>
        <?php while ($r = mysqli_fetch_assoc($prom_materia)): ?>
        <tr>
          <td><?php echo $r['nombre']; ?></td>
          <td><?php echo number_format($r['promedio'], 2); ?></td>
        </tr>
        <?php endwhile; ?>
      </table>
    </div>
  </main>
</div>

</body>
</html>
