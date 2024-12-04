<?php
require_once '../config/conexion.php';

$arquivo = 'pagos.xls';

$sql = "SELECT p.idpago, c.nombre AS cliente_nombre, p.usuario, p.fecha, p.cuota FROM pagos p INNER JOIN cliente c ON p.idcliente = c.idcliente";
$stmt = $conexion->prepare($sql);
$stmt->execute();
$pagos = $stmt->fetchAll(PDO::FETCH_ASSOC);

header("Expires: Mon, 26 Jul 2227 05:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-cache, must-revalidate");
header("Pragma: no-cache");
header("Content-type: application/x-msexcel");
header("Content-Disposition: attachment; filename=\"{$arquivo}\"");
header("Content-Description: Archivo de Excel de Pagos");

$html = '<table border="1">';
$html .= '<tr>';
$html .= '<td colspan="5">Listado de Pagos</td>';
$html .= '</tr>';

$html .= '<tr>';
$html .= '<td><b>ID</b></td>';
$html .= '<td><b>Cliente</b></td>';
$html .= '<td><b>Prestamista</b></td>';
$html .= '<td><b>Fecha de Pago</b></td>';
$html .= '<td><b>Cuota</b></td>';
$html .= '</tr>';

foreach ($pagos as $pago) {
    $html .= '<tr>';
    $html .= '<td>' . $pago["idpago"] . '</td>';
    $html .= '<td>' . $pago["cliente_nombre"] . '</td>';
    $html .= '<td>' . $pago["usuario"] . '</td>';
    $html .= '<td>' . $pago["fecha"] . '</td>';
    $html .= '<td>' . number_format($pago["cuota"], 2) . '</td>';
    $html .= '</tr>';
}
$html .= '</table>';
echo $html;
exit;
?>
