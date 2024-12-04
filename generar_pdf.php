<?php
require_once '../config/conexion.php';
require_once '../fpdf186/fpdf.php';

class PDF extends FPDF
{
    function Header()
    {
        $this->SetFont('Arial', 'B', 15);
        $this->Cell(0, 10, 'Listado de Clientes', 0, 1, 'C');
        $this->Ln(10);
    }

    function TablaClientes($clientes)
    {
        $this->SetFont('Arial', 'B', 12);
        $this->Cell(20, 10, 'ID', 1);
        $this->Cell(50, 10, 'Nombre', 1);
        $this->Cell(50, 10, 'Direccion', 1);
        $this->Cell(30, 10, 'Telefono', 1);
        $this->Cell(30, 10, 'Estado', 1);
        $this->Ln();

        $this->SetFont('Arial', '', 12);
        foreach ($clientes as $cliente) {
            $this->Cell(20, 10, $cliente['idcliente'], 1);
            $this->Cell(50, 10, $cliente['nombre'], 1);
            $this->Cell(50, 10, $cliente['direccion'], 1);
            $this->Cell(30, 10, $cliente['telefono'], 1);
            $this->Cell(30, 10, $cliente['estado'], 1);
            $this->Ln();
        }
    }
}

$sql = "SELECT * FROM cliente";
$stmt = $conexion->prepare($sql);
$stmt->execute();
$clientes = $stmt->fetchAll(PDO::FETCH_ASSOC);

$pdf = new PDF();
$pdf->AddPage();
$pdf->TablaClientes($clientes);

$pdf->Output('clientes.pdf', 'I');
?>
