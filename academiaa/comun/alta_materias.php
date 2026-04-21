<?php
include '../db/sesion.php';
include '../db/conexion.php';
requerir_rol(array('profesor', 'administrador', 'coordinador'));

$tipo = $_SESSION['tipo'];

$mensaje = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nombre = mysqli_real_escape_string($conexion, $_POST['nombre']);
    $creditos = mysqli_real_escape_string($conexion, $_POST['creditos']);

    $query = "INSERT INTO materias (nombre, creditos) VALUES ('$nombre', '$creditos')";
    //inserta materia con creditos
    if (mysqli_query($conexion, $query)) {
        $mensaje = "Materia registrada";
    } else {
        $mensaje = "Error al registrar";
    }
}

if ($tipo == 'profesor') {
    $profesor_id = (int) $_SESSION['usuario_id'];
    //regresa materias asignadas por profe, evitando duplicaods con join con profesor_grupo_materia
    $materias = mysqli_query($conexion, "
        SELECT DISTINCT m.*
        FROM materias m
        INNER JOIN profesor_grupo_materia pgm ON m.id = pgm.materia_id
        WHERE pgm.profesor_id = $profesor_id
        ORDER BY m.nombre ASC
    ");
} else {
    //regresa todas las materias registradas que pueden ver admin y coordinador
    $materias = mysqli_query($conexion, "SELECT * FROM materias ORDER BY nombre ASC");
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Alta de Materias</title>
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
      <li onclick="window.location.href='alta_materias.php'">
        <span class="material-symbols-outlined icono">menu_book</span> Materias
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

      <li onclick="window.location.href='cerrar_sesion.php'">
        <span class="material-symbols-outlined icono">logout</span> Cerrar Sesion
      </li>
    </ul>
  </nav>

  <main class="contenido">
    <div class="formulario">
      <h2>Alta de Materias</h2>

      <?php if ($mensaje != ''): ?>
        <p class="mensaje"><?php echo $mensaje; ?></p>
      <?php endif; ?>

      <form method="POST">
        <div class="pregunta">
          <label>Nombre de la Materia</label>
          <input type="text" name="nombre" >
        </div>
        <div class="pregunta">
          <label>Creditos</label>
          <input type="number" name="creditos" >
        </div>
        <button type="submit" class="boton">
          <span class="material-symbols-outlined icono">save</span> Guardar
        </button>
      </form>

      <h3 style="margin-top: 30px; color: #0f1e3f;">Materias Registradas</h3>
      <table class="tabla-lista" style="margin-top: 10px;">
        <tr>
          <th>ID</th>
          <th>Materia</th>
          <th>Creditos</th>
        </tr>
        <?php while ($fila = mysqli_fetch_assoc($materias)): ?>
        <tr>
          <td><?php echo $fila['id']; ?></td>
          <td><?php echo $fila['nombre']; ?></td>
          <td><?php echo $fila['creditos']; ?></td>
        </tr>
        <?php endwhile; ?>
      </table>
    </div>
  </main>
</div>

</body>
</html>
