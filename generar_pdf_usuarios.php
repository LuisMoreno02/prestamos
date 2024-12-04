<?php
require('../fpdf186/fpdf.php');
require_once '../config/conexion.php';

class PDF extends FPDF
{
    function Header()
    {
        $this->SetFont('Arial', 'B', 15);
        $this->Cell(0, 10, 'Listado de Usuarios', 0, 1, 'C');
        $this->Ln(10);
    }

    function TablaMejorada($header, $data)
    {
        $this->SetFillColor(230, 230, 230);
        $this->SetTextColor(0);
        $this->SetDrawColor(0);
        $this->SetLineWidth(.2);
        $this->SetFont('', 'B');

        $w = array(15, 40, 35, 30, 25, 25); 
        for ($i = 0; $i < count($header); $i++) {
            $this->Cell($w[$i], 7, $header[$i], 1, 0, 'C', true);
        }
        $this->Ln();
        $this->SetFillColor(224, 235, 255);
        $this->SetTextColor(0);
        $this->SetFont('');
        
        $fill = false;
        foreach ($data as $row) {
            $this->Cell($w[0], 6, $row['idusuario'], 'LR', 0, 'L', $fill);
            $this->Cell($w[1], 6, $row['nombre'], 'LR', 0, 'L', $fill);
            $this->Cell($w[2], 6, $row['direccion'], 'LR', 0, 'L', $fill);
            $this->Cell($w[3], 6, $row['telefono'], 'LR', 0, 'L', $fill);
            $this->Cell($w[4], 6, $row['estado'], 'LR', 0, 'L', $fill);
            $this->Cell($w[5], 6, $row['rol'], 'LR', 0, 'L', $fill);
            $this->Ln();
            $fill = !$fill;
        }
        $this->Cell(array_sum($w), 0, '', 'T');
    }
}

$sqlUsuarios = "SELECT idusuario, nombre, direccion, telefono, estado, rol FROM usuarios";
$stmtUsuarios = $conexion->prepare($sqlUsuarios);
$stmtUsuarios->execute();
$usuarios = $stmtUsuarios->fetchAll(PDO::FETCH_ASSOC);

$pdf = new PDF();
$header = array('ID', 'Nombre', 'Direccion', 'Telefono', 'Estado', 'Rol');
$pdf->AddPage();
$pdf->SetFont('Arial', '', 12);
$pdf->TablaMejorada($header, $usuarios);
$pdf->Output();
?>
