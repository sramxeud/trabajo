<?php
include("config.php"); // REALIZAMOS LA CONEXIÓN
session_start();

$usr = $_POST['usr']; // RECUPERAMOS LOS DATOS INGRESADOS DESDE EL index.php EN $usr Y $pwd
$pwd = $_POST['pwd'];
$pwd1 = $pwd;
$ch = $_POST['remember-me']; // recuperamos el valor del check recuerdame
$pwd = md5($pwd); // la contraseña recuperada la estamos encriptando y guardando en la misma variable

// Verificar si el usuario está bloqueado
if (!empty($_SESSION['bloqueado']) && $_SESSION['bloqueado'] > time()) {
    header("Location: ../Ejm/index.php?error=2");
    exit();
}

// REALIZAMOS UNA CONSULTA QUE BUSQUE EN LA TABLA usuarios LOS DATOS INGRESADOS
$q = "SELECT * FROM usuario WHERE nombre='$usr' AND contra='$pwd'";
$rs = mysqli_query($con, $q); // MANDAMOS LA CONSULTA AL mysql_query

if (mysqli_num_rows($rs) != 0) { // si el n° de filas es distinto de 0 al menos hay una, por lo tanto el usuario existe
    $_SESSION['ingreso'] = "accesopermitido"; // a la variable 'ingreso' le asignamos un valor accesopermitido
    $r = mysqli_fetch_array($rs);
    $_SESSION['cod'] = $r['nombre'];
    $_SESSION['usuario'] = $r['alias']; // identificamos quien entra
    $_SESSION['nivel'] = $r['nivel']; // identificamos su nivel
    $_SESSION['check'] = $ch; // asignamos el valor del check recuerdame a la sesion
    if (!empty($_SESSION['check'])) { // preguntamos si se activó guardar contraseña
        $_SESSION['usr'] = $usr; // asignamos el usuario a la sesión
        $_SESSION['pwd'] = $pwd1; // asignamos la contraseña a la sesión
    }
    $_SESSION['intentos_fallidos'] = 0; // Reiniciar contador de intentos fallidos

    switch ($_SESSION['nivel']) {
        case '4':
            header("Location: mdf_dco.php");
            break;
        case '5':
            header("Location: turnos_ewsd.php");
            break;
        case '6':
            header("Location: op_adm.php");
            break;
        case '8':
            header("Location: admin.php");
            break;
        default:
            // code...
            break;
    }
} else {
    // Incrementar contador de intentos fallidos
    if (!isset($_SESSION['intentos_fallidos'])) {
        $_SESSION['intentos_fallidos'] = 0;
    }
    $_SESSION['intentos_fallidos']++;

    // Verificar si se alcanzó el límite de intentos fallidos
    if ($_SESSION['intentos_fallidos'] >= 3) {
        // Bloquear el acceso durante 10 segundos
        $_SESSION['bloqueado'] = time() + 10;
        $_SESSION['intentos_fallidos'] = 0;
        header("Location: ../Ejm/index.php?error=3");
    } else {
        header("Location: ../Ejm/index.php?error=1");
    }
}
?>
