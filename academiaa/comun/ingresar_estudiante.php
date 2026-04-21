<?php
include '../db/sesion.php';
include '../db/conexion.php';
requerir_rol(array('profesor', 'administrador', 'coordinador'));

$mensaje = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nombre = $_POST['nombre'];
    $apellido = $_POST['apellido'];
    $email = $_POST['email'];
    $telefono = $_POST['telefono'];
    $usuario = $_POST['usuario'];
    $contrasena = $_POST['contrasena'];

    $sql = "INSERT INTO estudiantes (nombre, apellido, email, telefono, usuario, contrasena, tipo)
            VALUES ('$nombre', '$apellido', '$email', '$telefono', '$usuario', '$contrasena', 'alumno')";

    if (mysqli_query($conexion, $sql)) {
        $mensaje = "Estudiante registrado";
    } else {
        $mensaje = "Error al registrar";
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Ingresar Estudiante</title>
  <link rel="stylesheet" href="../assets/estilos.css">
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined">
</head>

<body>

<div class="panel">

  <nav class="menuprinci">
    <div class="perfil"><?php echo $_SESSION['usuario']; ?></div>
    <p style="color: #cdaa80; font-size: 12px; margin-bottom: 10px;"><?php echo $_SESSION['tipo']; ?></p>
    <ul>
      <li onclick="window.location.href='registro.php'">
        <span class="material-symbols-outlined icono">home</span> Inicio
      </li>
      <li onclick="window.location.href='ingresar_estudiante.php'">
        <span class="material-symbols-outlined icono">person_add</span> Ingresar
      </li>
      <li onclick="window.location.href='alta_materias.php'">
        <span class="material-symbols-outlined icono">menu_book</span> Materias
      </li>
      <li onclick="window.location.href='../profesor/subir_calificaciones.php'">
        <span class="material-symbols-outlined icono">upload</span> Calificaciones
      </li>
      <li onclick="window.location.href='cerrar_sesion.php'">
        <span class="material-symbols-outlined icono">logout</span> Cerrar Sesion
      </li>
    </ul>
  </nav>

<main class="contenido">
  <div class="formulario">
    <h2>Ingresar Estudiante</h2>

    <?php if ($mensaje != ''): ?>
      <p class="mensaje"><?php echo $mensaje; ?></p>
    <?php endif; ?>

    <form method="POST">
      <div class="pregunta">
        <label>Nombre</label>
        <input type="text" name="nombre">
      </div>
      <div class="pregunta">
        <label>Apellido</label>
        <input type="text" name="apellido">
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
        <input type="text" name="usuario">
      </div>
      <div class="pregunta">
        <label>Contraseña</label>
        <input type="password" name="contrasena">
      </div>
      <p class="error" id="error"></p>
      <button type="button" class="boton" onclick="validar()">
        <span class="material-symbols-outlined icono">save</span> Guardar
      </button>
    </form>
  </div>
</main>

</div>

<script>
function validar() {
  var nombre = document.querySelector('input[name="nombre"]').value.trim();
  var apellido = document.querySelector('input[name="apellido"]').value.trim();
  var usuario = document.querySelector('input[name="usuario"]').value.trim();
  var contrasena = document.querySelector('input[name="contrasena"]').value.trim();

  if (nombre == '' || apellido == '' || usuario == '' || contrasena == '') {
    document.getElementById('error').innerText = 'Llena los campos obligatorios';
    return;
  }
  document.querySelector('form').submit();
}
</script>

</body>
</html>
