<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
?>
<?php
session_start();
include 'db/conexion.php';

$error = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $usuario = $_POST['usuario'];
    $password = $_POST['password'];

    $resultado = mysqli_query($conexion, "SELECT * FROM estudiantes WHERE usuario = '$usuario' AND contrasena = '$password'");

    if (mysqli_num_rows($resultado) > 0) {
        $datos = mysqli_fetch_assoc($resultado);
        $_SESSION['usuario'] = $datos['nombre'];
        $_SESSION['tipo'] = $datos['tipo'];
        $_SESSION['usuario_id'] = $datos['id'];
        header("Location: comun/registro.php");
        exit;
    } else {
        $error = "Usuario o contraseña incorrectos";
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Login</title>
  <link rel="stylesheet" href="assets/estilos.css">
</head>

<body class="pagina">

<div class="tarjeta">

  <section class="login">

    <div class="centro">
      <h1 class="tituloInicio">INICIAR SESION</h1>

      <p class="error" id="error">
        <?php if ($error != '') echo $error; ?>
      </p>

      <form method="POST">

        <div class="pregunta">
          <label>USUARIO</label>
          <input type="text" name="usuario">
        </div>

        <div class="pregunta">
          <label>CONTRASEÑA</label>
          <input type="password" name="password">
        </div>

        <button type="button" class="boton" onclick="validar()">
          ENTRAR
        </button>

      </form>
    </div>

    <div class="año">2026</div>

  </section>

<section class="acostado">
  <div class="franja"></div>
  </section>

</div>

<script>
function validar() {
  var usuario = document.querySelector('input[name="usuario"]').value.trim();
  var password = document.querySelector('input[name="password"]').value.trim();

  if (usuario == '' || password == '') {
    document.getElementById('error').innerText = 'Ingresa usuario y contraseña';
    return;
  }
  document.querySelector('form').submit();
}
</script>

</body>
</html>
