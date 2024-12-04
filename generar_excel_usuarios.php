<?php
require_once '../config/conexion.php';

$arquivo = 'usuarios.xls';

$sql = "SELECT idusuario, nombre, direccion, telefono, estado, rol FROM usuarios";
$stmt = $conexion->prepare($sql);
$stmt->execute();
$usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);

header("Expires: Mon, 26 Jul 2227 05:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-cache, must-revalidate");
header("Pragma: no-cache");
header("Content-type: application/x-msexcel");
header("Content-Disposition: attachment; filename=\"{$arquivo}\"");
header("Content-Description: Archivo de Excel de Usuarios");

$html = '<table border="1">';
$html .= '<tr>';
$html .= '<td colspan="6">Listado de Usuarios</td>';
$html .= '</tr>';

$html .= '<tr>';
$html .= '<td><b>ID</b></td>';
$html .= '<td><b>Nombre</b></td>';
$html .= '<td><b>Direccion</b></td>';
$html .= '<td><b>Telefono</b></td>';
$html .= '<td><b>Estado</b></td>';
$html .= '<td><b>Rol</b></td>';
$html .= '</tr>';

foreach ($usuarios as $usuario) {
    $html .= '<tr>';
    $html .= '<td>' . $usuario["idusuario"] . '</td>';
    $html .= '<td>' . $usuario["nombre"] . '</td>';
    $html .= '<td>' . $usuario["direccion"] . '</td>';
    $html .= '<td>' . $usuario["telefono"] . '</td>';
    $html .= '<td>' . $usuario["estado"] . '</td>';
    $html .= '<td>' . $usuario["rol"] . '</td>';
    $html .= '</tr>';
}
$html .= '</table>';

echo $html;
exit;
?>
