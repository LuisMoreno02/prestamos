<?php
require_once '../config/conexion.php';

// Consulta para obtener las estadísticas
// Total de Pagos
$sqlTotalPagos = "SELECT SUM(cuota) as total_pagos FROM pagos"; 
$stmtTotalPagos = $conexion->prepare($sqlTotalPagos);
$stmtTotalPagos->execute();
$totalPagos = $stmtTotalPagos->fetch(PDO::FETCH_ASSOC)['total_pagos'];

// Morosos o atrasados en los pagos
$sqlMorosos = "SELECT DISTINCT c.nombre, c.telefono, p.saldo 
               FROM prestamos p 
               JOIN cliente c ON p.idcliente = c.idcliente 
               WHERE p.estado = 'activo' AND p.fechapago < CURDATE()";
$stmtMorosos = $conexion->prepare($sqlMorosos);
$stmtMorosos->execute();
$clientesMorosos = $stmtMorosos->fetchAll(PDO::FETCH_ASSOC);
$totalMorosos = count($clientesMorosos);


// Clientes Activos
$sqlClientesActivos = "SELECT COUNT(*) as total_clientes FROM cliente WHERE estado = 'activo'";
$stmtClientesActivos = $conexion->prepare($sqlClientesActivos);
$stmtClientesActivos->execute();
$totalClientesActivos = $stmtClientesActivos->fetch(PDO::FETCH_ASSOC)['total_clientes'];

// Saldo Total de los Préstamos
$sqlTotalSaldo = "SELECT SUM(saldo) as total_saldo FROM prestamos";
$stmtTotalSaldo = $conexion->prepare($sqlTotalSaldo);
$stmtTotalSaldo->execute();
$totalSaldo = $stmtTotalSaldo->fetch(PDO::FETCH_ASSOC)['total_saldo'];

// Préstamos Activos
$sqlPrestamosActivos = "SELECT COUNT(*) as total_prestamos FROM prestamos WHERE estado = 'activo'";
$stmtPrestamosActivos = $conexion->prepare($sqlPrestamosActivos);
$stmtPrestamosActivos->execute();
$totalPrestamosActivos = $stmtPrestamosActivos->fetch(PDO::FETCH_ASSOC)['total_prestamos'];

// Total de Usuarios
$sqlTotalUsuarios = "SELECT COUNT(*) as total_usuarios FROM usuarios";
$stmtTotalUsuarios = $conexion->prepare($sqlTotalUsuarios);
$stmtTotalUsuarios->execute();
$totalUsuarios = $stmtTotalUsuarios->fetch(PDO::FETCH_ASSOC)['total_usuarios'];

// Pagos del Día
$sqlPagosDia = "SELECT * FROM pagos WHERE DATE(fecha) = CURDATE()";
$stmtPagosDia = $conexion->prepare($sqlPagosDia);
$stmtPagosDia->execute();
$pagosDia = $stmtPagosDia->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Página de Inicio</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <style>
    body {
      font-family: Arial, sans-serif;
      background-color: #f8f9fa;
    }
    .sidebar {
      height: 100%;
      width: 250px;
      position: fixed;
      top: 0;
      left: 0;
      background-color: #343a40;
      padding-top: 20px;
    }
    .sidebar a {
      padding: 10px 15px;
      text-decoration: none;
      font-size: 18px;
      color: #fff;
      display: block;
    }
    .sidebar a:hover {
      background-color: #495057;
    }
    .content {
      margin-left: 250px;
      padding: 20px;
    }
    .card {
      border-radius: 10px;
      box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
      color: white;
    }
    .bg-gradient-primary {
      background: linear-gradient(45deg, #1e3c72, #2a5298);
    }
    .bg-gradient-success {
      background: linear-gradient(45deg, #28a745, #218838);
    }
    .bg-gradient-warning {
      background: linear-gradient(45deg, #ffc107, #e0a800);
    }
    .bg-gradient-info {
      background: linear-gradient(45deg, #17a2b8, #138496);
    }
    .table thead {
      background-color: #343a40;
      color: #fff;
    }
    #myChart {
      width: 300px;
      height: 200px;
      float: left;
    }
  </style>
</head>
<body>

<div class="sidebar">
  <div class="text-center text-white mb-4">
    <h4>Menú</h4>
  </div>
  <a href="inicio.php"><i class="bi bi-house"></i> Dashboard</a>
  <a href="clientes.php"><i class="bi bi-people"></i> Clientes</a>
  <a href="prestamos.php"><i class="bi bi-cash"></i> Préstamos</a>
  <a href="pagos.php"><i class="bi bi-credit-card"></i> Pagos</a>
  <a href="usuarios.php"><i class="bi bi-person"></i> Usuarios</a>
  <a href="cerrar_sesion.php"><i class="bi bi-door-closed"></i> Cerrar Sesión</a>
</div>

<div class="content">
  <h2 class="my-4">Estadísticas</h2>
  <div class="row">
    <div class="col-md-3">
      <div class="card bg-gradient-primary mb-3">
        <div class="card-body">
          <h5 class="card-title">Total de Pagos</h5>
          <p class="card-text"><i class="bi bi-credit-card"></i> $<?php echo number_format($totalPagos); ?></p>
        </div>
      </div>
    </div>
    <div class="col-md-3">
      <div class="card bg-gradient-success mb-3">
        <div class="card-body">
          <h5 class="card-title">Clientes Activos</h5>
          <p class="card-text"><i class="bi bi-people"></i> <?php echo $totalClientesActivos; ?></p>
        </div>
      </div>
    </div>
    <div class="col-md-3">
      <div class="card bg-gradient-warning mb-3">
        <div class="card-body">
          <h5 class="card-title">Préstamos Activos</h5>
          <p class="card-text"><i class="bi bi-cash"></i> <?php echo $totalPrestamosActivos; ?></p>
        </div>
      </div>
    </div>
    <div class="col-md-3">
      <div class="card bg-gradient-info mb-3">
        <div class="card-body">
          <h5 class="card-title">Total de Usuarios</h5>
          <p class="card-text"><i class="bi bi-person"></i> <?php echo $totalUsuarios; ?></p>
        </div>
      </div>
    </div>
  </div>
  <div class="row">
    <div class="col-md-6">
      <div class="card mb-4">
        <div class="card-header">
          <h5 class="card-title">Resumen de Actividades</h5>
        </div>
        <div class="card-body">
          <canvas id="myChart"></canvas>
        </div>
      </div>
    </div>


    <div class="col-md-3">
  <div class="card bg-gradient-warning mb-3">
    <div class="card-body">
      <h5 class="card-title">Saldo Total de Préstamos</h5>
      <p class="card-text"><i class="bi bi-cash"></i> $<?php echo number_format($totalSaldo); ?></p>
    </div>
  </div>
</div>


<div class="col-md-3">
  <div class="card bg-gradient-warning mb-3">
    <div class="card-body">
      <h5 class="card-title">Clientes Morosos</h5>
      <p class="card-text">
        <i class="bi bi-exclamation-triangle"></i> <?php echo $totalMorosos; ?> cliente(s)
      </p>
      <button class="btn btn-light btn-sm" type="button" data-bs-toggle="collapse" data-bs-target="#morososList" aria-expanded="false" aria-controls="morososList">
        Mostrar
      </button>
    </div>
  </div>
</div>

<div class="col-md-12">
  <div class="collapse" id="morososList">
    <div class="card card-body">
      <h5 class="card-title text-dark">Lista de Clientes Morosos</h5>
      <ul class="list-group">
        <?php if (empty($clientesMorosos)): ?>
          <li class="list-group-item text-dark">No hay clientes morosos actualmente.</li>
        <?php else: ?>
          <?php foreach ($clientesMorosos as $moroso): ?>
            <li class="list-group-item text-dark">
              <?php echo $moroso['nombre'] . " - Tel: " . $moroso['telefono'] . " - Saldo: $" . number_format($moroso['saldo']); ?>
            </li>
          <?php endforeach; ?>
        <?php endif; ?>
      </ul>
    </div>
  </div>
</div>




    <div class="col-md-6">
      <div class="card mb-4">
        <div class="card-header">
          <h5 class="card-title">Pagos del Día</h5>
        </div>
        <div class="card-body">
          <table class="table table-hover">
            <thead>
              <tr>
                <th>Cliente</th>
                <th>Cuota</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($pagosDia as $pago): ?>
                <tr>
                  <td><?php echo $pago['idcliente']; ?></td>
                  <td>$<?php echo number_format($pago['cuota'], 2); ?></td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
<script>
  const ctx = document.getElementById('myChart').getContext('2d');
  const myChart = new Chart(ctx, {
    type: 'bar',
    data: {
      labels: ['Pagos', 'Clientes Activos', 'Préstamos Activos', 'Usuarios'],
      datasets: [{
        label: 'Estadísticas',
        data: [<?php echo $totalPagos; ?>, <?php echo $totalClientesActivos; ?>, <?php echo $totalPrestamosActivos; ?>, <?php echo $totalUsuarios; ?>],
        backgroundColor: [
          'rgba(54, 162, 235, 0.2)',
          'rgba(75, 192, 192, 0.2)',
          'rgba(255, 206, 86, 0.2)',
          'rgba(153, 102, 255, 0.2)'
        ],
        borderColor: [
          'rgba(54, 162, 235, 1)',
          'rgba(75, 192, 192, 1)',
          'rgba(255, 206, 86, 1)',
          'rgba(153, 102, 255, 1)'
        ],
        borderWidth: 1
      }]
    },
    options: {
      scales: {
        y: {
          beginAtZero: true
        }
      }
    }
  });
</script>
</body>
</html>
