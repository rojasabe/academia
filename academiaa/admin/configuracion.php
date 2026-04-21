<?php
include '../db/sesion.php';
include '../db/conexion.php';
requerir_rol('administrador');

$mensaje = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['nuevo'])) {
    $nombre = mysqli_real_escape_string($conexion, $_POST['nombre']);
    $apellido = mysqli_real_escape_string($conexion, $_POST['apellido']);
    $email = mysqli_real_escape_string($conexion, $_POST['email']);
    $telefono = mysqli_real_escape_string($conexion, $_POST['telefono']);
    $usuario = mysqli_real_escape_string($conexion, $_POST['usuario']);
    $contrasena = mysqli_real_escape_string($conexion, $_POST['contrasena']);
    $tipo = mysqli_real_escape_string($conexion, $_POST['tipo']);

    $sql = "INSERT INTO estudiantes (nombre, apellido, email, telefono, usuario, contrasena, tipo)
            VALUES ('$nombre', '$apellido', '$email', '$telefono', '$usuario', '$contrasena', '$tipo')";
    //inserta el nuevo usuario con todos sus datos y el rol seleccionado
    if (mysqli_query($conexion, $sql)) {
        $mensaje = "Usuario registrado";
    } else {
        $mensaje = "Error al registrar";
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['actualizar_usuario'])) {
    $id = mysqli_real_escape_string($conexion, $_POST['id']);
    $nombre = mysqli_real_escape_string($conexion, $_POST['nombre']);
    $apellido = mysqli_real_escape_string($conexion, $_POST['apellido']);
    $email = mysqli_real_escape_string($conexion, $_POST['email']);
    $telefono = mysqli_real_escape_string($conexion, $_POST['telefono']);
    $usuario = mysqli_real_escape_string($conexion, $_POST['usuario']);
    $tipo = mysqli_real_escape_string($conexion, $_POST['tipo']);
    $contrasena = isset($_POST['contrasena']) ? mysqli_real_escape_string($conexion, $_POST['contrasena']) : '';

    if ($contrasena !== '') {
        $sql = "UPDATE estudiantes SET
                    nombre = '$nombre',
                    apellido = '$apellido',
                    email = '$email',
                    telefono = '$telefono',
                    usuario = '$usuario',
                    contrasena = '$contrasena',
                    tipo = '$tipo'
                WHERE id = '$id'";
    } else {
        $sql = "UPDATE estudiantes SET
                    nombre = '$nombre',
                    apellido = '$apellido',
                    email = '$email',
                    telefono = '$telefono',
                    usuario = '$usuario',
                    tipo = '$tipo'
                WHERE id = '$id'";
    }

    //updetea todos los datos del usuario; si se envió contraseña nueva la incluye, si no la omite
    if (mysqli_query($conexion, $sql)) {
        $mensaje = "Usuario actualizado";
    } else {
        $mensaje = "Error al actualizar";
    }
}

if (isset($_GET['eliminar'])) { //elimina todo lo que tenga el estudiante antes de eliminarlo
    $id = mysqli_real_escape_string($conexion, $_GET['eliminar']);
    mysqli_query($conexion, "DELETE FROM calificaciones WHERE estudiante_id = $id");
    mysqli_query($conexion, "DELETE FROM alumno_grupo WHERE alumno_id = $id");
    mysqli_query($conexion, "DELETE FROM profesor_grupo_materia WHERE profesor_id = $id");
    mysqli_query($conexion, "DELETE FROM estudiantes WHERE id = $id");
    header("Location: configuracion.php");
    exit;
}

//regresa todos los usuarios ordenados primero por rol y luego por apellido
$usuarios = mysqli_query($conexion, "SELECT * FROM estudiantes ORDER BY tipo, apellido ASC");
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Gestion de Usuarios</title>
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
    <div class="formulario" style="width: 1200px; max-width: 98%;">
      <style>
        .tabla-usuarios { width: 100%; table-layout: fixed; border-collapse: collapse; }
        .tabla-usuarios th, .tabla-usuarios td { padding: 6px; overflow: hidden; }
        .tabla-usuarios input, .tabla-usuarios select {
          width: 100%; box-sizing: border-box; padding: 4px 6px; margin: 0;
          min-width: 0;
        }
        .tabla-usuarios .col-acciones { width: 90px; }
        .tabla-usuarios .celda-acciones { display: flex; gap: 5px; align-items: center; }
        .scroll-tabla { width: 100%; overflow-x: auto; }
      </style>
      <h2>Gestion de Usuarios</h2>

      <?php if ($mensaje != ''): ?>
        <p class="mensaje"><?php echo $mensaje; ?></p>
      <?php endif; ?>

      <form method="POST">
        <input type="hidden" name="nuevo" value="1">
        <div class="pregunta">
          <label>Nombre</label>
          <input type="text" name="nombre" required>
        </div>
        <div class="pregunta">
          <label>Apellido</label>
          <input type="text" name="apellido" required>
        </div>
        <div class="pregunta">
          <label>Email</label>
          <input type="email" name="email">
        </div>
        <div class="pregunta">
          <label>Telefono</label>
          <input type="text" name="telefono">
        </div>
        <div class="pregunta">
          <label>Usuario</label>
          <input type="text" name="usuario" required>
        </div>
        <div class="pregunta">
          <label>Contraseña</label>
          <input type="password" name="contrasena" required>
        </div>
        <div class="pregunta">
          <label>Rol</label>
          <select name="tipo" required>
            <option value="alumno">Alumno</option>
            <option value="profesor">Profesor</option>
            <option value="coordinador">Coordinador</option>
            <option value="administrador">Administrador</option>
          </select>
        </div>
        <button type="submit" class="boton">
          <span class="material-symbols-outlined icono">save</span> Guardar
        </button>
      </form>

      <h3 style="margin-top: 30px; color: #0f1e3f;">Usuarios Registrados</h3>
      <div class="scroll-tabla">
      <table class="tabla-lista tabla-usuarios" style="margin-top: 10px; min-width: 1000px;">
        <colgroup>
          <col style="width: 12%;">
          <col style="width: 12%;">
          <col style="width: 16%;">
          <col style="width: 10%;">
          <col style="width: 11%;">
          <col style="width: 12%;">
          <col style="width: 12%;">
          <col class="col-acciones">
        </colgroup>
        <thead>
          <tr>
            <th>Nombre</th>
            <th>Apellido</th>
            <th>Email</th>
            <th>Telefono</th>
            <th>Usuario</th>
            <th>Contraseña</th>
            <th>Rol</th>
            <th>Acciones</th>
          </tr>
        </thead>
        <tbody>
          <?php while ($u = mysqli_fetch_assoc($usuarios)): ?>
          <?php $fid = 'fu' . $u['id']; ?>
          <tr>
            <td>
              <form id="<?php echo $fid; ?>" method="POST"></form>
              <input form="<?php echo $fid; ?>" type="hidden" name="id" value="<?php echo $u['id']; ?>">
              <input form="<?php echo $fid; ?>" type="text" name="nombre" value="<?php echo htmlspecialchars($u['nombre']); ?>" required>
            </td>
            <td><input form="<?php echo $fid; ?>" type="text" name="apellido" value="<?php echo htmlspecialchars($u['apellido']); ?>" required></td>
            <td><input form="<?php echo $fid; ?>" type="email" name="email" value="<?php echo htmlspecialchars($u['email']); ?>"></td>
            <td><input form="<?php echo $fid; ?>" type="text" name="telefono" value="<?php echo htmlspecialchars($u['telefono']); ?>"></td>
            <td><input form="<?php echo $fid; ?>" type="text" name="usuario" value="<?php echo htmlspecialchars($u['usuario']); ?>" required></td>
            <td><input form="<?php echo $fid; ?>" type="password" name="contrasena" placeholder="(sin cambios)"></td>
            <td>
              <select form="<?php echo $fid; ?>" name="tipo">
                <option value="alumno" <?php if($u['tipo']=='alumno') echo 'selected'; ?>>alumno</option>
                <option value="profesor" <?php if($u['tipo']=='profesor') echo 'selected'; ?>>profesor</option>
                <option value="coordinador" <?php if($u['tipo']=='coordinador') echo 'selected'; ?>>coordinador</option>
                <option value="administrador" <?php if($u['tipo']=='administrador') echo 'selected'; ?>>administrador</option>
              </select>
            </td>
            <td>
              <div class="celda-acciones">
                <button form="<?php echo $fid; ?>" type="submit" name="actualizar_usuario" value="1" class="boton" style="padding: 5px 10px; margin:0;" title="Guardar">
                  <span class="material-symbols-outlined icono">save</span>
                </button>
                <a href="configuracion.php?eliminar=<?php echo $u['id']; ?>"
                   onclick="return confirm('¿Eliminar usuario?')"
                   class="btn-eliminar" title="Eliminar">
                  <span class="material-symbols-outlined icono">delete</span>
                </a>
              </div>
            </td>
          </tr>
          <?php endwhile; ?>
        </tbody>
      </table>
      </div>
    </div>
  </main>
</div>

</body>
</html>
