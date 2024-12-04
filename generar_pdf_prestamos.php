<?php
require('../fpdf186/fpdf.php');
require_once '../config/conexion.php';

class PDF extends FPDF
{
    function Header()
    {
        $this->SetFont('Arial', 'B', 15);
        $this->Cell(0, 10, 'Listado de Prestamos', 0, 1, 'C');
        $this->Ln(10);
    }

    function TablaMejorada($header, $data)
    {
        $this->SetFillColor(230, 230, 230);
        $this->SetTextColor(0);
        $this->SetDrawColor(0);
        $this->SetLineWidth(.2);
        $this->SetFont('', 'B');

        $w = array(30, 45, 45, 40); 
        for ($i = 0; $i < count($header); $i++) {
            $this->Cell($w[$i], 7, $header[$i], 1, 0, 'C', true);
        }
        $this->Ln();
        $this->SetFillColor(224, 235, 255);
        $this->SetTextColor(0);
        $this->SetFont('');
        $fill = false;
        foreach ($data as $row) {
            $this->Cell($w[0], 6, $row['idprestamo'], 'LR', 0, 'L', $fill);
            $this->Cell($w[1], 6, $row['cliente_nombre'], 'LR', 0, 'L', $fill);
            $this->Cell($w[2], 6, number_format($row['monto'], 2), 'LR', 0, 'R', $fill);
            $this->Cell($w[3], 6, $row['fechapago'], 'LR', 0, 'R', $fill);
            $this->Ln();
            $fill = !$fill;
        }
        $this->Cell(array_sum($w), 0, '', 'T');
    }
}

$sqlPrestamos = "SELECT p.idprestamo, c.nombre AS cliente_nombre, p.monto, p.fechapago FROM prestamos p INNER JOIN cliente c ON p.idcliente = c.idcliente";
$stmtPrestamos = $conexion->prepare($sqlPrestamos);
$stmtPrestamos->execute();
$prestamos = $stmtPrestamos->fetchAll(PDO::FETCH_ASSOC);

$pdf = new PDF();
$header = array('ID Prestamo', 'Cliente', 'Monto', 'Fecha de Pago');
$pdf->AddPage();
$pdf->SetFont('Arial', '', 12);
$pdf->TablaMejorada($header, $prestamos);
$pdf->Output();
?>
