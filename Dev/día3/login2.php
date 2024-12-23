<?php
session_start();

// Datos de conexión
$host = "localhost";
$user = "norah";
$password = "Norah123";
$database = "norah";

// Conectar a la base de datos
$link = mysqli_connect($host, $user, $password, $database);

// Verificar la conexión
if (!$link) {
    die("Error de conexión: " . mysqli_connect_error());
}

// Establecer el conjunto de caracteres a UTF-8
mysqli_set_charset($link, "utf8");

$bloqueado = false;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = mysqli_real_escape_string($link, $_POST['nombre']);
    $ip_pc = $_SERVER['REMOTE_ADDR'];

    // Verificar si el usuario existe
    $query = "SELECT * FROM usuario WHERE nombre='$nombre'";
    $result = mysqli_query($link, $query);

    if (mysqli_num_rows($result) == 0) {
        $error_nombre = "Usuario no existe";
        
        // Incrementar contador de intentos fallidos
        if (!isset($_SESSION['intentos_fallidos'])) {
            $_SESSION['intentos_fallidos'] = 0;
        }
        $_SESSION['intentos_fallidos']++;

        // Verificar si se alcanzó el límite de intentos fallidos
        if ($_SESSION['intentos_fallidos'] >= 3) {
            // Registrar el bloqueo en la base de datos
            $query = "INSERT INTO loginPassUss (cod_Uss, ip_Uss, fecha_Uss, estado_Uss, nroInt_Uss) 
                      VALUES ('$nombre', '$ip_pc', NOW(), 2, 3)";
            mysqli_query($link, $query);

            // Bloquear el acceso durante 30 segundos
            $_SESSION['bloqueado'] = time();
            $bloqueado = true;
            $error_nombre = "Demasiados intentos fallidos. Intente nuevamente en 30 segundos.";
            $_SESSION['intentos_fallidos'] = 0;
        }

    } else {
        // Verificar si el usuario está bloqueado
        if (isset($_SESSION['bloqueado']) && (time() - $_SESSION['bloqueado']) < 30) {
            $bloqueado = true;
            $error_nombre = "Demasiados intentos fallidos. Intente nuevamente en 30 segundos.";
        } else {
            // Reiniciar contador de intentos fallidos
            $_SESSION['intentos_fallidos'] = 0;

            // Registrar intento exitoso en la base de datos
            $query = "INSERT INTO loginPassUss (cod_Uss, ip_Uss, fecha_Uss, estado_Uss, nroInt_Uss) 
                      VALUES ('$nombre', '$ip_pc', NOW(), 1, 1)";
            mysqli_query($link, $query);

            // Redirigir a welcome.php si el login es exitoso
            $_SESSION['nombre'] = $nombre;
            header("Location: welcome.php");
            exit();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF - 8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">
    <title>Iniciar Sesión</title>
    <style>
        .alert {
            transition: opacity 1s ease-out;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2 class="mt-5">Iniciar Sesión</h2>
        <?php
        if (!empty($error_nombre)) {
            echo '<div id="error-nombre" class="alert alert-danger" role="alert">' . $error_nombre . '</div>';
            echo '<script>
                    setTimeout(function() {
                        var errorNombre = document.getElementById("error-nombre");
                        errorNombre.style.opacity = "0";
                        setTimeout(function() { errorNombre.style.display = "none"; }, 1000);
                    }, 1500);
                  </script>';
        }
        ?>
        <form action="login.php" method="post">
            <div class="form-group">
                <label for="nombre">Código de Usuario</label>
                <input type="text" class="form-control" id="nombre" name="nombre" required <?php echo $bloqueado ? 'disabled' : ''; ?>>
            </div>
            <button type="submit" class="btn btn-primary" <?php echo $bloqueado ? 'disabled' : ''; ?>>Ingresar</button>
        </form>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha384-KyZXEAg3QhqLMpG8r+Knujsl5/COU6oaJ6I5fZ6X5Hbe+3Tf5Cm4Pww9zzF4K7nN" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-Fy6S3B9q64WdZWQUiU+q4/2Lc9npb8tCaSX9FK7E8HnRr0Jz8D6OP9dO5Vg3Q9ct" crossorigin="anonymous"></script>
</body>
</html>
