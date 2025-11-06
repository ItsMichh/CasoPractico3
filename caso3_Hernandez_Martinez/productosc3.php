<?php
session_start();
include("conexionc3.php");

// Protección
if (!isset($_SESSION["usuario"])) {
    header("Location: loginc3.php");
    exit();
}

// AGREGAR
if (isset($_POST["accion"]) && $_POST["accion"] == "agregar") {
    $stmt = $pdo->prepare("INSERT INTO producto (producto_nombre, producto_precio, producto_stock, producto_categoria)
                           VALUES (?, ?, ?, ?)");
    $stmt->execute([
        $_POST["nombre"],
        $_POST["precio"],
        $_POST["stock"],
        $_POST["categoria"]
    ]);
    header("Location: productosc3.php");
    exit();
}

// EDITAR
if (isset($_POST["accion"]) && $_POST["accion"] == "editar_guardar") {
    $stmt = $pdo->prepare("UPDATE producto SET producto_nombre=?, producto_precio=?, producto_stock=?, producto_categoria=? WHERE producto_id=?");
    $stmt->execute([
        $_POST["nombre"],
        $_POST["precio"],
        $_POST["stock"],
        $_POST["categoria"],
        $_POST["id"]
    ]);
    header("Location: productosc3.php");
    exit();
}

// ELIMINAR
if (isset($_GET["eliminar"])) {
    $stmt = $pdo->prepare("DELETE FROM producto WHERE producto_id=?");
    $stmt->execute([$_GET["eliminar"]]);
    header("Location: productosc3.php");
    exit();
}

// --- BUSCAR PRODUCTOS ---
$busqueda = "";
if (isset($_GET["buscar"]) && $_GET["buscar"] != "") {
    $busqueda = $_GET["buscar"];
    $stmt = $pdo->prepare("SELECT * FROM producto 
        WHERE producto_nombre LIKE ? OR producto_categoria LIKE ?
        ORDER BY producto_id DESC");
    $stmt->execute(["%$busqueda%", "%$busqueda%"]);
} else {
    $stmt = $pdo->query("SELECT * FROM producto ORDER BY producto_id DESC");
}
$productos = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Productos Tiendita</title>
<link rel="stylesheet" href="estilos.css">
</head>
<body class="fondo">
    <div class="productos-container">
        <h2>🛒 Productos de la Tiendita</h2>
        <div class="encabezado">
            <a href="cerrar_sesionc3.php" class="cerrar">Cerrar sesión</a>

            <!-- BUSCADOR -->
            <form method="get" class="buscar-form">
                <input type="text" name="buscar" placeholder="Buscar producto" value="<?= htmlspecialchars($busqueda) ?>">
                <button type="submit">🔍 Buscar</button>
                <a href="productosc3.php" class="limpiar">Limpiar</a>
            </form>
        </div>

        <!-- AGREGAR PRODUCTO -->
        <div class="form-card">
            <h3>Agregar producto</h3>
            <form method="post">
                <input type="hidden" name="accion" value="agregar">
                <input type="text" name="nombre" placeholder="Nombre" required>
                <input type="number" name="precio" step="0.01" placeholder="Precio" required>
                <input type="number" name="stock" placeholder="Stock" required>
                <input type="text" name="categoria" placeholder="Categoría">
                <button type="submit">Guardar</button>
            </form>
        </div>

        <!-- TABLA DE PRODUCTOS -->
        <table class="productos-tabla">
            <thead>
                <tr>
                    <th>ID</th><th>Nombre</th><th>Precio</th><th>Stock</th><th>Categoría</th><th>Acciones</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($productos as $p): ?>
                <?php if (isset($_POST["editar"]) && $_POST["editar"] == $p["producto_id"]): ?>
                    <!-- MODO EDICIÓN -->
                    <tr>
                        <form method="post">
                            <td><?= $p["producto_id"] ?></td>
                            <td><input type="text" name="nombre" value="<?= htmlspecialchars($p["producto_nombre"]) ?>"></td>
                            <td><input type="number" step="0.01" name="precio" value="<?= $p["producto_precio"] ?>"></td>
                            <td><input type="number" name="stock" value="<?= $p["producto_stock"] ?>"></td>
                            <td><input type="text" name="categoria" value="<?= htmlspecialchars($p["producto_categoria"]) ?>"></td>
                            <td>
                                <input type="hidden" name="id" value="<?= $p["producto_id"] ?>">
                                <input type="hidden" name="accion" value="editar_guardar">
                                <button type="submit">💾 Guardar</button>
                                <a href="productosc3.php" class="cancelar">❌ Cancelar</a>
                            </td>
                        </form>
                    </tr>
                <?php else: ?>
                    <!-- MODO SOLO LECTURA -->
                    <tr>
                        <form method="post">
                            <td><?= $p["producto_id"] ?></td>
                            <td><?= htmlspecialchars($p["producto_nombre"]) ?></td>
                            <td>$<?= number_format($p["producto_precio"], 2) ?></td>
                            <td><?= $p["producto_stock"] ?></td>
                            <td><?= htmlspecialchars($p["producto_categoria"]) ?></td>
                            <td>
                                <form method="post">
                                    <input type="hidden" name="editar" value="<?= $p["producto_id"] ?>">
                                    <button type="submit">✏️ Editar</button>
                                </form>
                                <a href="productosc3.php?eliminar=<?= $p["producto_id"] ?>" onclick="return confirm('¿Eliminar este producto?')" class="eliminar">🗑️</a>
                            </td>
                        </form>
                    </tr>
                <?php endif; ?>
            <?php endforeach; ?>

            <?php if (empty($productos)): ?>
                <tr><td colspan="6">No se encontraron productos</td></tr>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
