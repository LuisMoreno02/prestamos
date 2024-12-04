<?php
require_once '../config/conexion.php';

$arquivo = 'prestamos.xls';

$sql = "SELECT p.idprestamo, c.nombre AS cliente_nombre, p.usuario, p.monto, p.interes, (p.monto * (1 + (p.interes / 100))) AS saldo, p.formapago, p.plazo, p.fechapago, p.fechaprestamo, p.estado FROM prestamos p INNER JOIN cliente c ON p.idcliente = c.idcliente";
$stmt = $conexion->prepare($sql);
$stmt->execute();
$prestamos = $stmt->fetchAll(PDO::FETCH_ASSOC);

header("Expires: Mon, 26 Jul 2227 05:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-cache, must-revalidate");
header("Pragma: no-cache");
header("Content-type: application/x-msexcel");
header("Content-Disposition: attachment; filename=\"{$arquivo}\"");
header("Content-Description: Archivo de Excel de Préstamos");

$html = '<table border="1">';
$html .= '<tr>';
$html .= '<td colspan="11">Listado de Prestamos</td>';
$html .= '</tr>';

$html .= '<tr>';
$html .= '<td><b>ID</b></td>';
$html .= '<td><b>Cliente</b></td>';
$html .= '<td><b>Prestamista</b></td>';
$html .= '<td><b>Monto</b></td>';
$html .= '<td><b>Interes</b></td>';
$html .= '<td><b>Saldo</b></td>';
$html .= '<td><b>Forma de Pago</b></td>';
$html .= '<td><b>Plazo</b></td>';
$html .= '<td><b>Fecha de Pago</b></td>';
$html .= '<td><b>Fecha del Prestamo</b></td>';
$html .= '<td><b>Estado</b></td>';
$html .= '</tr>';

foreach ($prestamos as $prestamo) {
    $html .= '<tr>';
    $html .= '<td>' . $prestamo["idprestamo"] . '</td>';
    $html .= '<td>' . $prestamo["cliente_nombre"] . '</td>';
    $html .= '<td>' . $prestamo["usuario"] . '</td>';
    $html .= '<td>' . $prestamo["monto"] . '</td>';
    $html .= '<td>' . $prestamo["interes"] . '</td>';
    $html .= '<td>' . $prestamo["saldo"] . '</td>';
    $html .= '<td>' . $prestamo["formapago"] . '</td>';
    $html .= '<td>' . $prestamo["plazo"] . '</td>';
    $html .= '<td>' . $prestamo["fechapago"] . '</td>';
    $html .= '<td>' . $prestamo["fechaprestamo"] . '</td>';
    $html .= '<td>' . $prestamo["estado"] . '</td>';
    $html .= '</tr>';
}
$html .= '</table>';

echo $html;
exit;
?>
