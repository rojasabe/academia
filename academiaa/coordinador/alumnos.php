<?php
include '../db/sesion.php';
include '../db/conexion.php';
requerir_rol('coordinador');

$mensaje = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['actualizar'])) {
    $id       = mysqli_real_escape_string($conexion, $_POST['id']);
    $nombre   = mysqli_real_escape_string($conexion, $_POST['nombre']);
    $apellido = mysqli_real_escape_string($conexion, $_POST['apellido']);
    $email    = mysqli_real_escape_string($conexion, $_POST['email']);
    $telefono = mysqli_real_escape_string($conexion, $_POST['telefono']);
    $grupo_id = mysqli_real_escape_string($conexion, $_POST['grupo_id']);

    $sql = "UPDATE estudiantes SET nombre='$nombre', apellido='$apellido', email='$email', telefono='$telefono'
            WHERE id='$id' AND tipo='alumno'";
    //acctualiza los datos del alumno; el filtro AND TIPO ALUMNO evita modificar otros roles por error
    if (mysqli_query($conexion, $sql)) {
        $mensaje = "Alumno actualizado";
    } else {
        $mensaje = "Error al actualizar";
    }

    //ccomprueba si el alumno ya tiene un grupo asignado
    $existe = mysqli_fetch_assoc(mysqli_query($conexion, "SELECT id FROM alumno_grupo WHERE alumno_id = '$id'"));
    if ($grupo_id !== '' && $grupo_id !== '0') {
        if ($existe) {
            //cambia el grupo del alumno al nuevo seleccionado
            mysqli_query($conexion, "UPDATE alumno_grupo SET grupo_id = '$grupo_id' WHERE alumno_id = '$id'");
        } else {
            //asigna el alumno al grupo por primera vez
            mysqli_query($conexion, "INSERT INTO alumno_grupo (alumno_id, grupo_id) VALUES ('$id', '$grupo_id')");
        }
    } else {
        //si se eligió sin grupo, elimina la asignación actual
        mysqli_query($conexion, "DELETE FROM alumno_grupo WHERE alumno_id = '$id'");
    }
}

//obtiene todos los alumnos con su grupo actual (LEFT JOIN para mostrar también los sin grupo)
$alumnos = mysqli_query($conexion, "
    SELECT e.*, ag.grupo_id AS grupo_actual
    FROM estudiantes e
    LEFT JOIN alumno_grupo ag ON e.id = ag.alumno_id
    WHERE e.tipo = 'alumno'
    ORDER BY e.apellido ASC
");

//obbtiene todos los grupos para poblar el selector de cada fila
$grupos = mysqli_query($conexion, "SELECT * FROM grupos ORDER BY nombre ASC");
$lista_grupos = [];
while ($g = mysqli_fetch_assoc($grupos)) {
    $lista_grupos[] = $g;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Alumnos</title>
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
    <div class="formulario" style="width: 900px; max-width: 95%;">
      <h2>Alumnos</h2>

      <?php if ($mensaje != ''): ?>
        <p class="mensaje"><?php echo $mensaje; ?></p>
      <?php endif; ?>

      <table class="tabla-lista" style="margin-top: 10px;">
        <thead>
          <tr>
            <th>Nombre</th>
            <th>Apellido</th>
            <th>Email</th>
            <th>Telefono</th>
            <th>Grupo</th>
            <th>Acciones</th>
          </tr>
        </thead>
        <tbody>
        <?php while ($a = mysqli_fetch_assoc($alumnos)): ?>
        <?php $fid = 'fa' . $a['id']; ?>
        <tr>
          <form id="<?php echo $fid; ?>" method="POST"></form>
          <input form="<?php echo $fid; ?>" type="hidden" name="id" value="<?php echo $a['id']; ?>">
          <td><input form="<?php echo $fid; ?>" type="text" name="nombre" value="<?php echo htmlspecialchars($a['nombre']); ?>" style="width:110px; padding:5px;"></td>
          <td><input form="<?php echo $fid; ?>" type="text" name="apellido" value="<?php echo htmlspecialchars($a['apellido']); ?>" style="width:110px; padding:5px;"></td>
          <td><input form="<?php echo $fid; ?>" type="email" name="email" value="<?php echo htmlspecialchars($a['email']); ?>" style="width:150px; padding:5px;"></td>
          <td><input form="<?php echo $fid; ?>" type="text" name="telefono" value="<?php echo htmlspecialchars($a['telefono']); ?>" style="width:100px; padding:5px;"></td>
          <td>
            <select form="<?php echo $fid; ?>" name="grupo_id" style="padding:5px;">
              <option value="0">Sin grupo</option>
              <?php foreach ($lista_grupos as $g): ?>
                <option value="<?php echo $g['id']; ?>" <?php if ($a['grupo_actual'] == $g['id']) echo 'selected'; ?>>
                  <?php echo htmlspecialchars($g['nombre']); ?>
                </option>
              <?php endforeach; ?>
            </select>
          </td>
          <td>
            <button form="<?php echo $fid; ?>" type="submit" name="actualizar" value="1" class="boton" style="padding:5px 10px; margin:0;">
              <span class="material-symbols-outlined icono">save</span>
            </button>
          </td>
        </tr>
        <?php endwhile; ?>
        </tbody>
      </table>
    </div>
  </main>
</div>

</body>
</html>
