<?php
$host = "localhost";
$dbname = "tiendita";
$user = "root";
$pass = "_R5m_t20C"; 

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die(" Error de conexión: " . $e->getMessage());
}
?>
