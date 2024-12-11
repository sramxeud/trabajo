<?php
// Iniciar la sesión
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
    
    // Inicializar las variables de error
    $error_nombre = $error_contra = "";
    
    // Obtener los datos del formulario
    $nombre = mysqli_real_escape_string($link, $_POST['nombre']);
    $contra = mysqli_real_escape_string($link, $_POST['contra']);
    
    // Obtener la dirección IP de la máquina
    if (!empty($_SERVER["HTTP_X_FORWARDED_FOR"])) {
        $ip_pc = $_SERVER["HTTP_X_FORWARDED_FOR"]; // IP de la PC detrás de un proxy
    } else {
        $ip_pc = $_SERVER["REMOTE_ADDR"]; // IP directa de la PC
    }

    // Validar que la contraseña solo tenga caracteres alfanuméricos
    if (ctype_alnum($contra)) {
        $error_contra = "La contraseña solo puede contener caracteres alfanuméricos.";
    }
    
    // Consulta a la tabla 'usuario' para verificar el nombre
    if (empty($error_contra)) {
        $query = "SELECT * FROM usuario WHERE nombre='$nombre'";
        $result = mysqli_query($link, $query);
        
        if (mysqli_num_rows($result) == 0) {
            $error_nombre = "Nombre no válido.";
            // Registrar intento fallido
            $query = "INSERT INTO login_attempts (nombre, fecha, ip_pc, exito) VALUES ('$nombre', NOW(), '$ip_pc', 1)";
            if (!mysqli_query($link, $query)) {
                echo "Error al registrar el intento fallido: " . mysqli_error($link);
            }
        } else {
            // Verificar la combinación de nombre y contraseña
            $query = "SELECT * FROM usuario WHERE nombre='$nombre' AND contra='$contra'";
            $result = mysqli_query($link, $query);
            
            if (mysqli_num_rows($result) == 1) {
                // Nombre y contraseña encontrados, iniciar sesión y redirigir a welcome.php
                $_SESSION['nombre'] = $nombre;
                // Registrar intento exitoso
                $query = "INSERT INTO login_attempts (nombre, fecha, ip_pc, exito) VALUES ('$nombre', NOW(), '$ip_pc', 0)";
                if (!mysqli_query($link, $query)) {
                    echo "Error al registrar el intento exitoso: " . mysqli_error($link);
                }
                header("Location: welcome.php");
                exit();
            } else {
                if (empty($error_contra)) {
                    $error_contra = "Contraseña no válida.";
                }
                // Registrar intento fallido
                $query = "INSERT INTO login_attempts (nombre, fecha, ip_pc, exito) VALUES ('$nombre', NOW(), '$ip_pc', 2)";
                if (!mysqli_query($link, $query)) {
                    echo "Error al registrar el intento fallido: " . mysqli_error($link);
                }
            }
        }
    } else {
        // Registrar intento fallido por contraseña no alfanumérica
        $query = "INSERT INTO login_attempts (nombre, fecha, ip_pc, exito) VALUES ('$nombre', NOW(), '$ip_pc', 3)";
        if (!mysqli_query($link, $query)) {
            echo "Error al registrar el intento fallido: " . mysqli_error($link);
        }
    }
    
    // Cerrar la conexión a la base de datos
    mysqli_close($link);
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="path/to/bootstrap.min.css">
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
                    }, 3000);
                  </script>';
        }
        if (!empty($error_contra)) {
            echo '<div id="error-contra" class="alert alert-danger" role="alert">' . $error_contra . '</div>';
            echo '<script>
                    setTimeout(function() {
                        var errorContra = document.getElementById("error-contra");
                        errorContra.style.opacity = "0";
                        setTimeout(function() { errorContra.style.display = "none"; }, 1000);
                    }, 3000);
                  </script>';
        }
        ?>
        <form action="login.php" method="post">
            <div class="form-group">
                <label for="nombre">Nombre</label>
                <input type="text" class="form-control" id="nombre" name="nombre" required>
            </div>
            <div class="form-group">
                <label for="contra">Contraseña</label>
                <input type="password" class="form-control" id="contra" name="contra" required>
            </div>
            <button type="submit" class="btn btn-primary">Ingresar</button>
        </form>
    </div>
    <script src="path/to/jquery.slim.min.js"></script>
    <script src="path/to/popper.min.js"></script>
    <script src="path/to/bootstrap.min.js"></script>
</body>
</html>
