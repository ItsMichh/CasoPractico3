<?php
session_start();
include("conexionc3.php");

if (isset($_SESSION["usuario"])) {
    header("Location: productosc3.php");
    exit();
}

if (isset($_COOKIE["usuario"])) {
    $_SESSION["usuario"] = $_COOKIE["usuario"];
    header("Location: productosc3.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $usuario = $_POST["usuario"];
    $clave = $_POST["contrasena"];
    $recordar = isset($_POST["recordar"]);

    $stmt = $pdo->prepare("SELECT * FROM usuario WHERE usuario_nombre = ?");
    $stmt->execute([$usuario]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($row && md5($clave) === $row['usuario_clave']) {
        $_SESSION["usuario"] = $usuario;

        if ($recordar) {
            setcookie("usuario", $usuario, time() + 15, "/");
        }

        header("Location: productosc3.php");
        exit();
    } else {
        header("Location: errorc3.php?motivo=Usuario%20o%20contraseña%20incorrectos");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title> Login </title>
<link rel="stylesheet" href="estilos.css">
</head>
<body class="fondo">
<div class="login-card">
    <h2> ¡Bienvenida a la Tiendita! </h2>
    <form method="post">
        <input type="text" name="usuario" placeholder="Usuario" required>
        <input type="password" name="contrasena" placeholder="Contraseña" required>

        <label class="recordar">
            <input type="checkbox" name="recordar"> Recuérdame 🌸
        </label>

        <button type="submit">Ingresar</button>
    </form>

    <?php if(isset($error)): ?>
        <p class="error"><?= $error ?></p>
    <?php endif; ?>
</div>
</body>
</html>

