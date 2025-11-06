<!DOCTYPE html>
<html>
<head>
    <title>Error de inicio</title>
</head>
<body>
    <h2>❌ Error al iniciar sesión</h2>
    <p>
        <?php 
        if (isset($_GET["motivo"])) {
            echo $_GET["motivo"];
        } else {
            echo "No se proporcionó una descripción del error.";
        }
        ?>
    </p>
    <a href="loginc3.php">Regresar</a>
</body>
</html>