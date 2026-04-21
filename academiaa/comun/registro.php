<?php
include '../db/sesion.php';
requerir_login();
$tipo = $_SESSION['tipo'];
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Panel</title>
  <link rel="stylesheet" href="../assets/estilos.css">
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined">
</head>

<body>

<div class="panel">

  <nav class="menuprinci">
    <div class="perfil"><?php echo $_SESSION['usuario']; ?></div>
    <p style="color: #cdaa80; font-size: 12px; margin-bottom: 10px;"><?php echo $tipo; ?></p>

    <ul>
      <li onclick="window.location.href='registro.php'">
        <span class="material-symbols-outlined icono">home</span> Inicio
      </li>

      <?php if ($tipo == 'administrador'): ?>
      <li onclick="window.location.href='../admin/configuracion.php'">
        <span class="material-symbols-outlined icono">manage_accounts</span> Usuarios
      </li>
      <li onclick="window.location.href='../admin/grupos.php'">
        <span class="material-symbols-outlined icono">groups</span> Grupos
      </li>
      <li onclick="window.location.href='alta_materias.php'">
        <span class="material-symbols-outlined icono">menu_book</span> Materias
      </li>
      <li onclick="window.location.href='../admin/estadisticas.php'">
        <span class="material-symbols-outlined icono">insights</span> Estadisticas
      </li>
      <?php endif; ?>

      <?php if ($tipo == 'coordinador'): ?>
      <li onclick="window.location.href='../coordinador/profesores.php'">
        <span class="material-symbols-outlined icono">school</span> Profesores
      </li>
      <li onclick="window.location.href='../coordinador/alumnos.php'">
        <span class="material-symbols-outlined icono">group</span> Alumnos
      </li>
      <li onclick="window.location.href='../coordinador/asignaciones.php'">
        <span class="material-symbols-outlined icono">assignment_ind</span> Asignaciones
      </li>
      <li onclick="window.location.href='../coordinador/estadisticas.php'">
        <span class="material-symbols-outlined icono">insights</span> Estadisticas
      </li>
      <?php endif; ?>

      <?php if ($tipo == 'profesor'): ?>
      <li onclick="window.location.href='ingresar_estudiante.php'">
        <span class="material-symbols-outlined icono">person_add</span> Ingresar
      </li>
      <li onclick="window.location.href='alta_materias.php'">
        <span class="material-symbols-outlined icono">menu_book</span> Materias
      </li>
      <li onclick="window.location.href='../profesor/subir_calificaciones.php'">
        <span class="material-symbols-outlined icono">upload</span> Calificaciones
      </li>
      <li onclick="window.location.href='../profesor/editar_independiente.php'">
        <span class="material-symbols-outlined icono">edit_note</span> Editar
      </li>
      <?php endif; ?>

      <?php if ($tipo == 'alumno'): ?>
      <li onclick="window.location.href='../alumno/lista_estudiantes.php'">
        <span class="material-symbols-outlined icono">group</span> Compañeros
      </li>
      <li onclick="window.location.href='../alumno/mis_calificaciones.php'">
        <span class="material-symbols-outlined icono">school</span> Calificaciones
      </li>
      <?php endif; ?>

      <li onclick="window.location.href='cerrar_sesion.php'">
        <span class="material-symbols-outlined icono">logout</span> Cerrar Sesion
      </li>
    </ul>
  </nav>

  <main class="contenido">
    <div class="vista">
      <h1 style="color: #0f1e3f;">ACADEMIA</h1>
      <p style="color: #213a56; margin-top: 10px;">Panel de Control</p>
    </div>
  </main>

</div>

</body>
</html>
