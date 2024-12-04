<?php
require_once '../config/conexion.php';

$sqlUsuarios = "SELECT idusuario, nombre FROM usuarios";
$stmtUsuarios = $conexion->prepare($sqlUsuarios);
$stmtUsuarios->execute();
$usuarios = $stmtUsuarios->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Usuarios</title>
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
  <h2 class="my-4">Usuarios</h2>
  <button type="button" class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#agregarUsuarioModal">Nuevo Usuario</button>
  <table class="table">
    <thead>
      <tr>
        <th>ID</th>
        <th>Nombre</th>
        <th>Dirección</th>
        <th>Teléfono</th>
        <th>Login</th>
        <th>Clave</th>
        <th>Estado</th>
        <th>Rol</th>
        <th>Acciones</th>
      </tr>
    </thead>
    <tbody>
      <?php
      $sqlUsuarios = "SELECT * FROM usuarios";
      $stmtUsuarios = $conexion->prepare($sqlUsuarios);
      $stmtUsuarios->execute();
      $usuarios = $stmtUsuarios->fetchAll(PDO::FETCH_ASSOC);
      
      foreach ($usuarios as $usuario) {
          echo "<tr>";
          echo "<td>{$usuario['idusuario']}</td>";
          echo "<td>{$usuario['nombre']}</td>";
          echo "<td>{$usuario['direccion']}</td>";
          echo "<td>{$usuario['telefono']}</td>";
          echo "<td>{$usuario['login']}</td>";
          echo "<td>{$usuario['clave']}</td>";
          echo "<td>{$usuario['estado']}</td>";
          echo "<td>{$usuario['rol']}</td>";
          echo "<td>";
          echo "<a href='../ajax/eliminar_usuario.php?id={$usuario['idusuario']}' class='btn btn-danger'><i class='bi bi-trash'></i></a>";
          echo "<a href='#' class='btn btn-primary' onclick='abrirModalActualizar({$usuario['idusuario']})'><i class='bi bi-pencil'></i></a>";
          echo "</td>";
          echo "</tr>";
      }
      ?>
    </tbody>
  </table>
</div>

<!-- Modal para agregar usuario -->
<div class="modal fade" id="agregarUsuarioModal" tabindex="-1" aria-labelledby="agregarUsuarioModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="agregarUsuarioModalLabel">Agregar Usuario</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <!-- Formulario para agregar un nuevo usuario -->
        <form action="../ajax/agregar_usuario.php" method="POST">
          <div class="mb-3">
            <label for="nombre" class="form-label">Nombre:</label>
            <input type="text" class="form-control" id="nombre" name="nombre" required>
          </div>
          <div class="mb-3">
            <label for="direccion" class="form-label">Dirección:</label>
            <input type="text" class="form-control" id="direccion" name="direccion" required>
          </div>
          <div class="mb-3">
            <label for="telefono" class="form-label">Teléfono:</label>
            <input type="text" class="form-control" id="telefono" name="telefono" required>
          </div>
          <div class="mb-3">
            <label for="login" class="form-label">Login:</label>
            <input type="text" class="form-control" id="login" name="login" required>
          </div>
          <div class="mb-3">
            <label for="clave" class="form-label">Clave:</label>
            <input type="password" class="form-control" id="clave" name="clave" required>
          </div>
          <div class="mb-3">
            <label for="estado" class="form-label">Estado</label>
            <select class="form-select" id="estado" name="estado" required>
              <option value="Activo">Activo</option>
              <option value="Inactivo">Inactivo</option>
            </select>
          </div>
          <div class="mb-3">
            <label for="rol" class="form-label">Rol</label> <!-- Campo para el rol -->
            <select class="form-select" id="rol" name="rol" required>
              <option value="Admin">Admin</option>
              <option value="Usuario">Usuario</option>
            </select>
          </div>
          <button type="submit" class="btn btn-primary">Guardar</button>
        </form>
      </div>
    </div>
  </div>
</div>

<!-- Modal para actualizar usuario -->
<div class="modal fade" id="actualizarUsuarioModal" tabindex="-1" aria-labelledby="actualizarUsuarioModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="actualizarUsuarioModalLabel">Actualizar Usuario</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <!-- Formulario para actualizar un usuario -->
        <form id="formActualizarUsuario" action="../ajax/actualizar_usuario.php" method="POST">
          <input type="hidden" id="idUsuarioActualizar" name="idUsuarioActualizar">
          <div class="mb-3">
            <label for="nombreActualizar" class="form-label">Nombre:</label>
            <input type="text" class="form-control" id="nombreActualizar" name="nombre" required>
          </div>
          <div class="mb-3">
            <label for="direccionActualizar" class="form-label">Dirección:</label>
            <input type="text" class="form-control" id="direccionActualizar" name="direccion" required>
          </div>
          <div class="mb-3">
            <label for="telefonoActualizar" class="form-label">Teléfono:</label>
            <input type="text" class="form-control" id="telefonoActualizar" name="telefono" required>
          </div>
          <div class="mb-3">
            <label for="loginActualizar" class="form-label">Login:</label>
            <input type="text" class="form-control" id="loginActualizar" name="login" required>
          </div>
          <div class="mb-3">
            <label for="claveActualizar" class="form-label">Clave:</label>
            <input type="password" class="form-control" id="claveActualizar" name="clave" required>
          </div>
          <div class="mb-3">
            <label for="estadoActualizar" class="form-label">Estado</label>
            <select class="form-select" id="estadoActualizar" name="estadoActualizar" required>
              <option value="Activo">Activo</option>
              <option value="Inactivo">Inactivo</option>
            </select>
          </div>
          <div class="mb-3">
            <label for="rolActualizar" class="form-label">Rol</label> <!-- Campo para el rol -->
            <select class="form-select" id="rolActualizar" name="rolActualizar" required>
              <option value="Admin">Admin</option>
              <option value="Usuario">Usuario</option>
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
  function abrirModalActualizar(idUsuario) {
    $.ajax({
      url: '../ajax/cargar_usuario.php',
      method: 'POST',
      data: { id: idUsuario },
      dataType: 'json',
      success: function(data) {
        $('#idUsuarioActualizar').val(data.idusuario);
        $('#nombreActualizar').val(data.nombre);
        $('#direccionActualizar').val(data.direccion);
        $('#telefonoActualizar').val(data.telefono);
        $('#loginActualizar').val(data.login);
        $('#claveActualizar').val(data.clave);
        $('#estadoActualizar').val(data.estado);
        $('#rolActualizar').val(data.rol); // Llenar el campo de rol
        $('#actualizarUsuarioModal').modal('show');
      },
      error: function(xhr, status, error) {
        console.error(xhr.responseText);
      }
    });
  }
</script>

</body>
</html>
