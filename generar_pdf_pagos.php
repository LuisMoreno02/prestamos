<?php
require('../fpdf186/fpdf.php');
require_once '../config/conexion.php';

class PDF extends FPDF
{
    function Header()
    {
        $this->SetFont('Arial', 'B', 15);
        $this->Cell(0, 10, 'Listado de Pagos', 0, 1, 'C');
        $this->Ln(10);
    }

    function TablaMejorada($header, $data)
    {
        $this->SetFillColor(230, 230, 230);
        $this->SetTextColor(0);
        $this->SetDrawColor(0);
        $this->SetLineWidth(.2);
        $this->SetFont('', 'B');
        $w = array(20, 50, 50, 35, 30); 
        for ($i = 0; $i < count($header); $i++) {
            $this->Cell($w[$i], 7, $header[$i], 1, 0, 'C', true);
        }
        $this->Ln();

        $this->SetFillColor(224, 235, 255);
        $this->SetTextColor(0);
        $this->SetFont('');
        
        $fill = false;
        foreach ($data as $row) {
            $this->Cell($w[0], 6, $row['idpago'], 'LR', 0, 'L', $fill);
            $this->Cell($w[1], 6, $row['cliente_nombre'], 'LR', 0, 'L', $fill);
            $this->Cell($w[2], 6, $row['usuario'], 'LR', 0, 'L', $fill);
            $this->Cell($w[3], 6, $row['fecha'], 'LR', 0, 'L', $fill);
            $this->Cell($w[4], 6, number_format($row['cuota'], 2), 'LR', 0, 'R', $fill);
            $this->Ln();
            $fill = !$fill;
        }
        $this->Cell(array_sum($w), 0, '', 'T');
    }
}

$sqlPagos = "SELECT p.idpago, c.nombre AS cliente_nombre, p.usuario, p.fecha, p.cuota FROM pagos p INNER JOIN cliente c ON p.idcliente = c.idcliente";
$stmtPagos = $conexion->prepare($sqlPagos);
$stmtPagos->execute();
$pagos = $stmtPagos->fetchAll(PDO::FETCH_ASSOC);

$pdf = new PDF();
$header = array('ID', 'Cliente', 'Prestamista', 'Fecha de Pago', 'Cuota');
$pdf->AddPage();
$pdf->SetFont('Arial', '', 12);
$pdf->TablaMejorada($header, $pagos);
$pdf->Output();
?>
