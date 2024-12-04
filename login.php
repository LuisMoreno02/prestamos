<?php
require_once '../config/conexion.php';

session_start();

// Mostrar errores de PHP
error_reporting(E_ALL);
ini_set('display_errors', 1);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM Usuarios WHERE login = :username AND clave = :password";
    $stmt = $conexion->prepare($sql);
    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':password', $password);
    $stmt->execute();

    // Verificar si hubo errores en la ejecución de la consulta
    $stmt_error = $stmt->errorInfo();
    if ($stmt_error[0] !== '00000') {
        var_dump($stmt_error);
        exit(); // Detener la ejecución
    }

    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        $_SESSION['username'] = $user['login'];
        
        if ($user['rol'] == 'Admin') {
            header("Location: inicio.php");
            exit();
        } elseif ($user['rol'] == 'Usuario') {
            header("Location: usuario_cliente.php");
            exit();
        } else {
            $error = "Rol desconocido";
        }
    } else {
        $error = "Usuario o contraseña incorrectos";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: #e9f7f9;
            font-family: 'Arial', sans-serif;
        }
        .card {
            width: 100%;
            max-width: 400px;
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
            background-color: #fff;
            border: none;
            backdrop-filter: blur(10px);
        }
        .card-header {
            text-align: center;
            margin-bottom: 20px;
            font-size: 24px;
            color: #007bff;
        }
        .btn-primary {
            width: 100%;
            background: #007bff;
            border: none;
            transition: background 0.3s;
            font-size: 16px;
            font-weight: bold;
        }
        .btn-primary:hover {
            background: #0056b3;
        }
        .form-control {
            border-radius: 5px;
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #ced4da;
        }
        .input-group-text {
            background: #007bff;
            color: #fff;
            border: none;
            border-radius: 5px 0 0 5px;
        }
        .recover-password {
            text-align: center;
            margin-top: 10px;
        }
        .recover-password a {
            color: #007bff;
            text-decoration: none;
        }
        .recover-password a:hover {
            text-decoration: underline;
        }
        .title h1 {
            font-weight: bold;
            color: #333;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-5">
                <div class="title text-center mb-4">
                    <h1>Sistema de Gestión de Préstamos</h1>
                </div>
                <div class="card">
                    <div class="card-header">
                        <h2 class="mb-0">Iniciar Sesión</h2>
                    </div>
                    <div class="card-body">
                        <?php if(isset($error)): ?>
                            <div class="alert alert-danger" role="alert">
                                <?php echo $error; ?>
                            </div>
                        <?php endif; ?>
                        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
                            <div class="input-group mb-4">
                                <span class="input-group-text"><i class="fas fa-user"></i></span>
                                <input type="text" class="form-control" id="username" name="username" placeholder="Usuario" required>
                            </div>
                            <div class="input-group mb-4">
                                <span class="input-group-text"><i class="fas fa-lock"></i></span>
                                <input type="password" class="form-control" id="password" name="password" placeholder="Contraseña" required>
                            </div>
                            <button type="submit" class="btn btn-primary">Acceder</button>
                        </form>
                        <div class="recover-password">
                            <a href="recuperar_contraseña.php">¿Olvidaste tu contraseña?</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
