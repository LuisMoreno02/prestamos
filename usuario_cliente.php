<?php
require_once '../config/conexion.php';

$sql = "SELECT * FROM cliente";
$stmt = $conexion->prepare($sql);
$stmt->execute();
$num_clientes = $stmt->rowCount();

if ($num_clientes > 0) {
    $clientes = $stmt->fetchAll(PDO::FETCH_ASSOC);
} else {
    echo "No se encontraron clientes.";
    exit();
}

?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Clientes</title>
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
  <a href="usuario_cliente.php"><i class="bi bi-people"></i> Clientes</a>
  <a href="usuario_pagos.php"><i class="bi bi-credit-card"></i> Pagos</a>
  <a href="cerrar_sesion.php"><i class="bi bi-door-closed"></i> Cerrar Sesión</a>
</div>
</div>


<div class="content">
  <h2 class="my-4">Clientes</h2>
  
  <button type="button" class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#agregarClienteModal"> Nuevo Cliente</button>
  <table class="table">
    <thead>
      <tr>
        <th>ID</th>
        <th>Nombre</th>
        <th>Dirección</th>
        <th>Teléfono</th>
        <th>Estado</th>
        <th>Acciones</th>
      </tr>
    </thead>
    <tbody>
      <?php
      foreach ($clientes as $cliente) {
          echo "<tr>";
          echo "<td>{$cliente['idcliente']}</td>";
          echo "<td>{$cliente['nombre']}</td>";
          echo "<td>{$cliente['direccion']}</td>";
          echo "<td>{$cliente['telefono']}</td>";
          echo "<td>{$cliente['estado']}</td>";
          echo "<td>";
          echo "<a href='../ajax/eliminar_cliente.php?id={$cliente['idcliente']}' class='btn btn-danger'><i class='bi bi-trash'></i></a>";
          echo "<a href='#' class='btn btn-primary' onclick='abrirModalActualizar({$cliente['idcliente']})'><i class='bi bi-pencil'></i></a>";
          echo "</td>";
          echo "</tr>";
      }
      ?>
    </tbody>
  </table>
<form action="" method="post">
    <button type="submit" class="btn btn-primary" name="generar_pdf">PDF</button>
</form>
</div>

<!-- Modal para agregar cliente -->
<div class="modal fade" id="agregarClienteModal" tabindex="-1" aria-labelledby="agregarClienteModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="agregarClienteModalLabel">Agregar cliente</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <!-- Formulario para agregar un nuevo cliente -->
        <form action="../ajax/agregar_cliente.php" method="POST">
          <div class="mb-3">
            <label for="nombre" class="form-label">Nombre</label>
            <input type="text" class="form-control" id="nombre" name="nombre" required>
          </div>
          <div class="mb-3">
            <label for="direccion" class="form-label">Dirección</label>
            <input type="text" class="form-control" id="direccion" name="direccion" required>
          </div>
          <div class="mb-3">
            <label for="telefono" class="form-label">Teléfono</label>
            <input type="text" class="form-control" id="telefono" name="telefono" required>
          </div>
          <div class="mb-3">
            <label for="estado" class="form-label">Estado</label>
            <select class="form-select" id="estado" name="estado" required>
              <option value="Activo">Activo</option>
              <option value="Inactivo">Inactivo</option>
            </select>
          </div>
          <button type="submit" class="btn btn-primary">Guardar</button>
        </form>
      </div>
    </div>
  </div>
</div>

<!-- Modal para actualizar cliente -->
<div class="modal fade" id="actualizarClienteModal" tabindex="-1" aria-labelledby="actualizarClienteModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="actualizarClienteModalLabel">Actualizar Cliente</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <!-- Formulario para actualizar un cliente -->
        <form id="formActualizarCliente" action="../ajax/actualizar_cliente.php" method="POST">
          <input type="hidden" id="idClienteActualizar" name="idClienteActualizar">
          <div class="mb-3">
            <label for="nombre" class="form-label">Nombre</label>
            <input type="text" class="form-control" id="nombreActualizar" name="nombreActualizar" required>
          </div>
          <div class="mb-3">
            <label for="direccion" class="form-label">Dirección</label>
            <input type="text" class="form-control" id="direccionActualizar" name="direccionActualizar" required>
          </div>
          <div class="mb-3">
            <label for="telefono" class="form-label">Teléfono</label>
            <input type="text" class="form-control" id="telefonoActualizar" name="telefonoActualizar" required>
          </div>
          <div class="mb-3">
            <label for="estadoActualizar" class="form-label">Estado</label>
            <select class="form-select" id="estadoActualizar" name="estadoActualizar" required>
              <option value="Activo">Activo</option>
              <option value="Inactivo">Inactivo</option>
            </select>
          </div>
          <button type="submit" class="btn btn-primary">Actualizar</button>
        </form>
      </div>
    </div>
  </div>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>


<script>
  function abrirModalActualizar(idCliente) {
    $.ajax({
      url: '../ajax/cargar_clientes.php',
      method: 'POST',
      data: { id: idCliente },
      dataType: 'json',
      success: function(data) {
        $('#idClienteActualizar').val(data.idcliente);
        $('#nombreActualizar').val(data.nombre);
        $('#direccionActualizar').val(data.direccion);
        $('#telefonoActualizar').val(data.telefono);
        $('#estadoActualizar').val(data.estado);
        $('#actualizarClienteModal').modal('show');
      },
      error: function(xhr, status, error) {
        console.error(xhr.responseText);
      }
    });
  }
</script>
</body>
</html>
