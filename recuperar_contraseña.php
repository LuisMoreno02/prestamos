<?php
require_once __DIR__ . '/../config/conexion.php'; // Ruta relativa al archivo de configuración

// Manejo de solicitud de recuperación de contraseña
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];

    // Verificar si el correo electrónico está registrado
    $sql = "SELECT * FROM usuarios WHERE email = :email";
    $stmt = $conexion->prepare($sql);
    $stmt->bindParam(':email', $email);
    $stmt->execute();

    if ($stmt->rowCount() == 1) {
        // Generar un token único para la recuperación de contraseña
        $token = bin2hex(random_bytes(50));
        
        // Obtener la fecha y hora actuales
        $expDate = date("Y-m-d H:i:s", strtotime('+1 hour'));

        // Guardar el token en la base de datos
        $sql = "UPDATE usuarios SET reset_token = :token, reset_expiration = :expDate WHERE email = :email";
        $stmt = $conexion->prepare($sql);
        $stmt->bindParam(':token', $token);
        $stmt->bindParam(':expDate', $expDate);
        $stmt->bindParam(':email', $email);
        $stmt->execute();

        // URL para la recuperación
        $resetLink = "http://localhost/prestamos/vistas/reset_password.php?token=" . $token;

        // Enviar el correo electrónico con el enlace de recuperación
        $subject = "Recuperación de Contraseña";
        $message = "Haz clic en el siguiente enlace para restablecer tu contraseña: " . $resetLink;
        $headers = "From: no-reply@prestamos.com";

        if (mail($email, $subject, $message, $headers)) {
            $success = "Se ha enviado un enlace de recuperación a tu correo electrónico.";
        } else {
            $error = "Error al enviar el correo electrónico de recuperación.";
        }
    } else {
        $error = "El correo electrónico no está registrado.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recuperar Contraseña</title>
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
                        <h2 class="mb-0">Recuperar Contraseña</h2>
                    </div>
                    <div class="card-body">
                        <?php if(isset($success)): ?>
                            <div class="alert alert-success" role="alert">
                                <?php echo $success; ?>
                            </div>
                        <?php elseif(isset($error)): ?>
                            <div class="alert alert-danger" role="alert">
                                <?php echo $error; ?>
                            </div>
                        <?php endif; ?>
                        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
                            <div class="input-group mb-4">
                                <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                <input type="email" class="form-control" id="email" name="email" placeholder="Correo Electrónico" required>
                            </div>
                            <button type="submit" class="btn btn-primary">Enviar Enlace</button>
                        </form>
                        <div class="recover-password">
                            <a href="login.php">Regresar al inicio de sesión</a>
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
