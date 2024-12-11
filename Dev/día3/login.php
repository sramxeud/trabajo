<?php
session_start();

// Verificar si el formulario ha sido enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
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
    
    $nombre = mysqli_real_escape_string($link, $_POST['nombre']);
    $ip_pc = $_SERVER['REMOTE_ADDR'];

    // Verificar si el usuario existe
    $query = "SELECT * FROM usuario WHERE nombre='$nombre'";
    $result = mysqli_query($link, $query);

    if (mysqli_num_rows($result) == 0) {
        echo "<script>alert('Usuario no existe');</script>";
    } else {
        // Verificar intentos fallidos
        $query = "SELECT * FROM loginPassUss WHERE cod_Uss='$nombre' AND estado_Uss=2 ORDER BY fecha_Uss DESC LIMIT 3";
        $result = mysqli_query($link, $query);
        $intentos_fallidos = mysqli_num_rows($result);

        if ($intentos_fallidos >= 3) {
            $ultimo_intento = mysqli_fetch_assoc($result);
            $tiempo_ultimo_intento = strtotime($ultimo_intento['fecha_Uss']);
            $tiempo_actual = time();

            if (($tiempo_actual - $tiempo_ultimo_intento) < 30) {
                echo "<script>alert('Demasiados intentos fallidos. Intente nuevamente en 30 segundos.');</script>";
                // Registrar bloqueo
                $query = "INSERT INTO loginPassUss (cod_Uss, ip_Uss, fecha_Uss, estado_Uss, nroInt_Uss) 
                          VALUES ('$nombre', '$ip_pc', NOW(), 2, $intentos_fallidos)";
                mysqli_query($link, $query);
                exit();
            }
        }

        // Registrar intento de login
        $query = "INSERT INTO loginPassUss (cod_Uss, ip_Uss, fecha_Uss, estado_Uss, nroInt_Uss) 
                  VALUES ('$nombre', '$ip_pc', NOW(), 1, 1)
                  ON DUPLICATE KEY UPDATE nroInt_Uss = nroInt_Uss + 1";
        mysqli_query($link, $query);

        // Redirigir a welcome.php si el login es exitoso
        $_SESSION['nombre'] = $nombre;
        header("Location: welcome.php");
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
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
        }
        ?>
        <form action="login.php" method="post">
            <div class="form-group">
                <label for="nombre">Código de Usuario</label>
                <input type="text" class="form-control" id="nombre" name="nombre" required>
            </div>
            <button type="submit" class="btn btn-primary">Ingresar</button>
        </form>
    </div>
    <script>
        // Ocultar automáticamente el mensaje de error después de 3 segundos
        setTimeout(function() {
            var errorNombre = document.getElementById("error-nombre");
            if (errorNombre) {
                errorNombre.style.opacity = "0";
                setTimeout(function() {
                    errorNombre.style.display = "none";
                }, 1000);
            }
        }, 3000);
    </script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha384-KyZXEAg3QhqLMpG8r+Knujsl5/COU6oaJ6I5fZ6X5Hbe+3Tf5Cm4Pww9zzF4K7nN" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-Fy6S3B9q64WdZWQUiU+q4/2Lc9npb8tCaSX9FK7E8HnRr0Jz8D6OP9DO5Vg3Q9ct" crossorigin="anonymous"></script>
</body>
</html>
