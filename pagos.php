<?php
require_once '../config/conexion.php';

// Variables para almacenar los filtros
$filtro_cliente = isset($_GET['idcliente']) ? $_GET['idcliente'] : null;
$filtro_fecha_inicio = isset($_GET['fecha_inicio']) ? $_GET['fecha_inicio'] : null;
$filtro_fecha_fin = isset($_GET['fecha_fin']) ? $_GET['fecha_fin'] : null;

// Construcción de la consulta SQL base
$sqlPagos = "SELECT p.idpago, c.nombre AS cliente_nombre, p.usuario, p.fecha, p.cuota 
             FROM pagos p 
             INNER JOIN cliente c ON p.idcliente = c.idcliente";

// Añadir condiciones de filtro si están presentes
$condiciones = [];
$params = [];

if (!empty($filtro_cliente)) {
    $condiciones[] = "p.idcliente = :idcliente";
    $params[':idcliente'] = $filtro_cliente;
}

if (!empty($filtro_fecha_inicio)) {
    $condiciones[] = "p.fecha >= :fecha_inicio";
    $params[':fecha_inicio'] = $filtro_fecha_inicio;
}

if (!empty($filtro_fecha_fin)) {
    $condiciones[] = "p.fecha <= :fecha_fin";
    $params[':fecha_fin'] = $filtro_fecha_fin;
}

if (!empty($condiciones)) {
    $sqlPagos .= " WHERE " . implode(" AND ", $condiciones);
}

// Preparar la consulta con PDO
$stmtPagos = $conexion->prepare($sqlPagos);

// Asignar valores a los parámetros del filtro, si están presentes
foreach ($params as $param => $value) {
    $stmtPagos->bindValue($param, $value, PDO::PARAM_STR);
}

// Ejecutar la consulta
$stmtPagos->execute();

// Obtener los resultados
$pagos = $stmtPagos->fetchAll(PDO::FETCH_ASSOC);

// Obtener clientes para el formulario de filtro y agregar/actualizar pago
$sqlClientes = "SELECT idcliente, nombre FROM cliente";
$stmtClientes = $conexion->prepare($sqlClientes);
$stmtClientes->execute();
$clientes = $stmtClientes->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Pagos</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
  <style>
    body {
      font-family: Arial, sans-serif;
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
  <h2 class="my-4">Pagos</h2>
  
  <!-- Formulario para filtrar por cliente y fecha -->
  <div class="my-3">
    <form method="GET" action="pagos.php" class="row g-3">
      <div class="col-md-3">
        <label for="idcliente" class="form-label">Cliente:</label>
        <select id="idcliente" name="idcliente" class="form-select">
          <option value="">Todos los clientes</option>
          <?php foreach ($clientes as $cliente): ?>
            <option value="<?php echo htmlspecialchars($cliente['idcliente']); ?>" <?php echo $filtro_cliente == $cliente['idcliente'] ? 'selected' : ''; ?>>
              <?php echo htmlspecialchars($cliente['nombre']); ?>
            </option>
          <?php endforeach; ?>
        </select>
      </div>
      <div class="col-md-3">
        <label for="fecha_inicio" class="form-label">Fecha de inicio:</label>
        <input type="date" id="fecha_inicio" name="fecha_inicio" class="form-control" value="<?php echo htmlspecialchars($filtro_fecha_inicio); ?>">
      </div>
      <div class="col-md-3">
        <label for="fecha_fin" class="form-label">Fecha de fin:</label>
        <input type="date" id="fecha_fin" name="fecha_fin" class="form-control" value="<?php echo htmlspecialchars($filtro_fecha_fin); ?>">
      </div>
      <div class="col-md-3 align-self-end">
        <button type="submit" class="btn btn-primary">Filtrar</button>
        <a href="pagos.php" class="btn btn-secondary">Limpiar</a>
      </div>
    </form>
  </div>

  <button type="button" class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#agregarPagoModal">Nuevo Pago</button>
  
  <table class="table">
    <thead>
      <tr>
        <th>ID</th>
        <th>Cliente</th>
        <th>Prestamista</th>
        <th>Fecha de Pago</th>
        <th>Cuota</th>
        <th>Acciones</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($pagos as $pago): ?>
        <tr>
          <td><?php echo htmlspecialchars($pago['idpago']); ?></td>
          <td><?php echo htmlspecialchars($pago['cliente_nombre']); ?></td>
          <td><?php echo htmlspecialchars($pago['usuario']); ?></td>
          <td><?php echo htmlspecialchars($pago['fecha']); ?></td>
          <td><?php echo htmlspecialchars($pago['cuota']); ?></td>
          <td>
            <a href="../ajax/eliminar_pago.php?id=<?php echo htmlspecialchars($pago['idpago']); ?>" class="btn btn-danger"><i class="bi bi-trash"></i></a>
            <a href="#" class="btn btn-primary" onclick="abrirModalActualizar(<?php echo htmlspecialchars($pago['idpago']); ?>)"><i class="bi bi-pencil"></i></a>
          </td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>

<!-- Modal para agregar pago -->
<div class="modal fade" id="agregarPagoModal" tabindex="-1" aria-labelledby="agregarPagoModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="agregarPagoModalLabel">Agregar Pago</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <!-- Formulario para agregar un nuevo pago -->
        <form action="../ajax/agregar_pago.php" method="POST">
          <div class="mb-3">
            <label for="idcliente" class="form-label">Cliente</label>
            <select class="form-select" id="idcliente" name="idcliente" required>
              <?php foreach ($clientes as $cliente): ?>
                <option value="<?php echo htmlspecialchars($cliente['idcliente']); ?>"><?php echo htmlspecialchars($cliente['nombre']); ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="mb-3">
            <label for="usuario" class="form-label">Usuario</label>
            <input type="text" class="form-control" id="usuario" name="usuario" required>
          </div>
          <div class="mb-3">
            <label for="fecha" class="form-label">Fecha de Pago</label>
            <input type="date" class="form-control" id="fecha" name="fecha" required>
          </div>
          <div class="mb-3">
            <label for="cuota" class="form-label">Cuota</label>
            <input type="text" class="form-control" id="cuota" name="cuota" required>
          </div>
          <button type="submit" class="btn btn-primary">Agregar</button>
        </form>
      </div>
    </div>
  </div>
</div>

<!-- Modal para actualizar pago -->
<div class="modal fade" id="actualizarPagoModal" tabindex="-1" aria-labelledby="actualizarPagoModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="actualizarPagoModalLabel">Actualizar Pago</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <!-- Formulario para actualizar un pago existente -->
        <form action="../ajax/actualizar_pago.php" method="POST" id="formActualizarPago">
          <input type="hidden" name="idpago" id="actualizarIdPago">
          <div class="mb-3">
            <label for="actualizarIdCliente" class="form-label">Cliente</label>
            <select class="form-select" id="actualizarIdCliente" name="idcliente" required>
              <?php foreach ($clientes as $cliente): ?>
                <option value="<?php echo htmlspecialchars($cliente['idcliente']); ?>"><?php echo htmlspecialchars($cliente['nombre']); ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="mb-3">
            <label for="actualizarUsuario" class="form-label">Usuario</label>
            <input type="text" class="form-control" id="actualizarUsuario" name="usuario" required>
          </div>
          <div class="mb-3">
            <label for="actualizarFecha" class="form-label">Fecha de Pago</label>
            <input type="date" class="form-control" id="actualizarFecha" name="fecha" required>
          </div>
          <div class="mb-3">
            <label for="actualizarCuota" class="form-label">Cuota</label>
            <input type="text" class="form-control" id="actualizarCuota" name="cuota" required>
          </div>
          <button type="submit" class="btn btn-primary">Actualizar</button>
        </form>
      </div>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>
<script>
  function abrirModalActualizar(idPago) {
    // Cargar datos del pago para actualizar
    fetch('../ajax/get_pago.php?id=' + idPago)
      .then(response => response.json())
      .then(data => {
        document.getElementById('actualizarIdPago').value = data.idpago;
        document.getElementById('actualizarIdCliente').value = data.idcliente;
        document.getElementById('actualizarUsuario').value = data.usuario;
        document.getElementById('actualizarFecha').value = data.fecha;
        document.getElementById('actualizarCuota').value = data.cuota;
        // Abrir el modal de actualización
        var actualizarModal = new bootstrap.Modal(document.getElementById('actualizarPagoModal'));
        actualizarModal.show();
      })
      .catch(error => console.error('Error al cargar los datos del pago:', error));
  }
</script>
</body>
</html>
