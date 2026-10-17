<?php
session_start();
require_once 'config.php';
$id = intval($_GET['id'] ?? 0);// intval kima integer traja3 entier
$stmt = $pdo->prepare("SELECT photo FROM ouvrier WHERE id_ouvrier=?");
$stmt->execute([$id]);
$row = $stmt->fetch();
if ($row && $row['photo']) {
    header('Content-Type: image/jpeg');
    echo $row['photo'];
} else {
    header('HTTP/1.0 404 Not Found');
}
?>
