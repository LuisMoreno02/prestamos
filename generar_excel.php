<?php
require_once '../config/conexion.php';

$arquivo = 'clientes.xls';
$html = '';
$html .= '<table border="1">';
$html .= '<tr>';
$html .= '<td colspan="5">Listado de Clientes</td>';
$html .= '</tr>';

$html .= '<tr>';
$html .= '<td><b>ID</b></td>';
$html .= '<td><b>Nombre</b></td>';
$html .= '<td><b>Direccion</b></td>';
$html .= '<td><b>Telefono</b></td>';
$html .= '<td><b>Estado</b></td>';
$html .= '</tr>';

$sql = "SELECT * FROM cliente";
$stmt = $conexion->prepare($sql);
$stmt->execute();
$clientes = $stmt->fetchAll(PDO::FETCH_ASSOC);

foreach ($clientes as $cliente) {
    $html .= '<tr>';
    $html .= '<td>' . $cliente["idcliente"] . '</td>';
    $html .= '<td>' . $cliente["nombre"] . '</td>';
    $html .= '<td>' . $cliente["direccion"] . '</td>';
    $html .= '<td>' . $cliente["telefono"] . '</td>';
    $html .= '<td>' . $cliente["estado"] . '</td>';
    $html .= '</tr>';
}

header("Expires: Mon, 26 Jul 2227 05:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-cache, must-revalidate");
header("Pragma: no-cache");
header("Content-type: application/x-msexcel");
header("Content-Disposition: attachment; filename=\"{$arquivo}\"");
header("Content-Description: PHP Generado por Prestamos");

echo $html;
exit;
?>
