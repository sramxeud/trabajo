<?php
session_start();
$cod = $_SESSION['cod'];
require("../conecta.php");
$conectar = conectar("controlOperaciones");

if (!$conectar) {
    die("Conexión fallida: " . mysqli_connect_error());
}

if (isset($_POST['ACT'])) {
    $telf = $_POST['telf'];
    $tipo_trab = $_POST['tipo_trab'];
    $fecha_reg = $_POST['fechaReg'];

    // Buscar datos en las tablas dt_Primario y dt_Secundario
    $consulta = "SELECT * FROM dt_Primario a, dt_Secundario b WHERE a.telf_p = b.telf_s AND a.telf_p = $telf AND estado_p = 'OCUPADO'";
    $sql = mysqli_query($conectar, $consulta);
    if (mysqli_num_rows($sql) > 0) {
        while ($reg = mysqli_fetch_array($sql)) {
            $area_p = $reg['area_p'];
            $distrito_p = $reg['distrito_p'];
            $liston_p = $reg['liston_p'];
            $borne_p = $reg['borne_p'];
            $estado_p = $reg['estado_p'];
            $listonProtector = $reg['listonProtector'];
            $borneProtector = $reg['borneProtector'];

            $area_s = $reg['area_s'];
            $distrito_s = $reg['distrito_s'];
            $caja_s = $reg['caja_s'];
            $par_s = $reg['par_s'];
            $estado_s = $reg['estado_s'];
        }
    } else {
        // Si no hay resultados en ambas tablas, buscar individualmente
        $consulta_primario = "SELECT * FROM dt_Primario WHERE telf_p = $telf AND estado_p = 'OCUPADO'";
        $sql_primario = mysqli_query($conectar, $consulta_primario);
        if (mysqli_num_rows($sql_primario) > 0) {
            while ($reg = mysqli_fetch_array($sql_primario)) {
                $area_p = $reg['area_p'];
                $distrito_p = $reg['distrito_p'];
                $liston_p = $reg['liston_p'];
                $borne_p = $reg['borne_p'];
                $estado_p = $reg['estado_p'];
                $listonProtector = '';
                $borneProtector = '';

                $area_s = '';
                $distrito_s = '';
                $caja_s = '';
                $par_s = '';
                $estado_s = '';
            }
        } else {
            echo "No se encontraron datos para el número de teléfono: " . htmlspecialchars($telf);
            exit;
        }
    }
}

if (isset($_POST['actualizar'])) {
    $telf = $_POST['telf'];
    $area_p = $_POST['area_p'];
    $distrito_p = $_POST['distrito_p'];
    $liston_p = $_POST['liston_p'];
    $borne_p = $_POST['borne_p'];
    $listonProtector = $_POST['listonProtector'];
    $borneProtector = $_POST['borneProtector'];

    $area_s = $_POST['area_s'];
    $distrito_s = $_POST['distrito_s'];
    $caja_s = $_POST['caja_s'];
    $par_s = $_POST['par_s'];
    $tipo_trab = $_POST['tipo_trab'];
    $acomodacion = $_POST['acomodacion'];
    $fecha_reg = $_POST['fecha_reg'];

    // Actualización en dt_Primario
    $updateQueryPrimario = "UPDATE dt_Primario 
                            SET area_p = ?, distrito_p = ?, liston_p = ?, borne_p = ?, listonProtector = ?, borneProtector = ?
                            WHERE telf_p = ? AND estado_p = 'OCUPADO'";

    $stmtPrimario = mysqli_prepare($conectar, $updateQueryPrimario);
    if ($stmtPrimario === false) {
        die("Error en la preparación de la consulta: " . mysqli_error($conectar));
    }

    mysqli_stmt_bind_param($stmtPrimario, "sssssss", $area_p, $distrito_p, $liston_p, $borne_p, $listonProtector, $borneProtector, $telf);
    $executeSuccessPrimario = mysqli_stmt_execute($stmtPrimario);
    
    if ($executeSuccessPrimario && mysqli_stmt_affected_rows($stmtPrimario) > 0) {
        echo "Datos de dt_Primario actualizados correctamente.";
    } else {
        echo "Error al actualizar datos de dt_Primario: " . mysqli_stmt_error($stmtPrimario);
    }

    mysqli_stmt_close($stmtPrimario);

    // Actualización en dt_Secundario (si es necesario)
    $updateQuerySecundario = "UPDATE dt_Secundario 
                              SET area_s = ?, distrito_s = ?, caja_s = ?, par_s = ?
                              WHERE telf_s = ?";

    $stmtSecundario = mysqli_prepare($conectar, $updateQuerySecundario);
    if ($stmtSecundario === false) {
        die("Error en la preparación de la consulta: " . mysqli_error($conectar));
    }

    mysqli_stmt_bind_param($stmtSecundario, "sssss", $area_s, $distrito_s, $caja_s, $par_s, $telf);
    $executeSuccessSecundario = mysqli_stmt_execute($stmtSecundario);
    
    if ($executeSuccessSecundario && mysqli_stmt_affected_rows($stmtSecundario) > 0) {
        echo "Datos de dt_Secundario actualizados correctamente.";
    } else {
        echo "Error al actualizar datos de dt_Secundario: " . mysqli_stmt_error($stmtSecundario);
    }

    mysqli_stmt_close($stmtSecundario);
}


if (isset($_POST['limpiar'])) {
  $telf = "";
  $area_p = $_POST['area_p'];
  $distrito_p = $_POST['distrito_p'];
  $liston_p = $_POST['liston_p'];
  $borne_p = $_POST['borne_p'];

  $area_s = $_POST['area_s'];
  $distrito_s = $_POST['distrito_s'];
  $caja_s = $_POST['caja_s'];
  $par_s = $_POST['par_s'];

  // Cambiar el estado a "LIBRE"
  $estado_p = "LIBRE";
  $estado_s = "LIBRE";

  // Limpiar los demás campos
  $listonProtector = "";
  $borneProtector = "";
  $tipo_trab = $_POST['tipo_trab'];
  $acomodacion = "";
  $fecha_reg = $_POST['fecha_reg'];
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF - 8">
    <title>Actualizar Datos DT-MDF</title>
    <link rel="stylesheet" href="../bootstrap-4.1.3-dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container">
        <h2>Actualizar Datos DT-MDF</h2>
        <form action="userupdate_MDF.php" method="post" class="form">
            <div class="row">
                <div class="col-md-6">
                    <div class="card mb-3">
                        <div class="card-header">Datos Primarios</div>
                        <div class="card-body">
                            <!-- Campos Primarios -->
                            <div class="form-group">
                                <label for="telf">Teléfono:</label>
                                <input type="text" class="form-control" name="telf" id="telf" value="<?= htmlspecialchars($telf) ?>">
                            </div>
                            <div class="form-group">
                                <label for="area_p">Área:</label>
                                <input type="text" class="form-control" name="area_p" id="area_p" value="<?= htmlspecialchars($area_p) ?>" readonly>
                            </div>
                            <div class="form-group">
                                <label for="distrito_p">Distrito:</label>
                                <input type="text" class="form-control" name="distrito_p" id="distrito_p" value="<?= htmlspecialchars($distrito_p) ?>" readonly>
                            </div>
                            <div class="form-group">
                                <label for="liston_p">Listón:</label>
                                <input type="text" class="form-control" name="liston_p" id="liston_p" value="<?= htmlspecialchars($liston_p) ?>" readonly>
                            </div>
                            <div class="form-group">
                                <label for="borne_p">Borne:</label>
                                <input type="text" class="form-control" name="borne_p" id="borne_p" value="<?= htmlspecialchars($borne_p) ?>" readonly>
                            </div>
                            <div class="form-group">
                                <label for="estado_p">Estado:</label>
                                <input type="text" class="form-control" name="estado_p" id="estado_p" value="<?= htmlspecialchars($estado_p) ?>">
                            </div>
                            <div class="form-group">
                                <label for="listonProtector">Protector Listón:</label>
                                <input type="text" class="form-control" name="listonProtector" id="listonProtector" value="<?= htmlspecialchars($listonProtector) ?>">
                            </div>
                            <div class="form-group">
                                <label for="borneProtector">Protector Borne:</label>
                                <input type="text" class="form-control" name="borneProtector" id="borneProtector" value="<?= htmlspecialchars($borneProtector) ?>">
                            </div>
                            <div class="form-group">
                                <label for="entreHilos_1erP">Entre Hilos 1er P:</label>
                                <input type="text" class="form-control" name="entreHilos_1erP" id="entreHilos_1erP" value="<?= htmlspecialchars($entreHilos_1erP) ?>">
                            </div>
                            <div class="form-group">
                                <label for="entreHilos_2doP">Entre Hilos 2do P:</label>
                                <input type="text" class="form-control" name="entreHilos_2doP" id="entreHilos_2doP" value="<?= htmlspecialchars($entreHilos_2doP) ?>">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="card mb-3">
                        <div class="card-header">Datos Secundarios</div>
                        <div class="card-body">
                            <!-- Campos Secundarios -->
                            <div class="form-group">
                                <label for="area_s">Área:</label>
                                <input type="text" class="form-control" name="area_s" id="area_s" value="<?= htmlspecialchars($area_s) ?>" readonly>
                            </div>
                            <div class="form-group">
                                <label for="distrito_s">Distrito:</label>
                                <input type="text" class="form-control" name="distrito_s" id="distrito_s" value="<?= htmlspecialchars($distrito_s) ?>" readonly>
                            </div>
                            <div class="form-group">
                                <label for="caja_s">Caja:</label>
                                <input type="text" class="form-control" name="caja_s" id="caja_s" value="<?= htmlspecialchars($caja_s) ?>" readonly>
                            </div>
                            <div class="form-group">
                                <label for="par_s">Par:</label>
                                <input type="text" class="form-control" name="par_s" id="par_s" value="<?= htmlspecialchars($par_s) ?>" readonly>
                            </div>
                            <div class="form-group">
                                <label for="estado_s">Estado:</label>
                                <input type="text" class="form-control" name="estado_s" id="estado_s" value="<?= htmlspecialchars($estado_s) ?>">
                            </div>
                            <div class="form-group">
                                <label for="entreHilos_1erS">Entre Hilos 1er S:</label>
                                <input type="text" class="form-control" name="entreHilos_1erS" id="entreHilos_1erS" value="<?= htmlspecialchars($entreHilos_1erS) ?>">
                            </div>
                            <div class="form-group">
                                <label for="entreHilos_2doS">Entre Hilos 2do S:</label>
                                <input type="text" class="form-control" name="entreHilos_2doS" id="entreHilos_2doS" value="<?= htmlspecialchars($entreHilos_2doS) ?>">
                            </div>
                        </div>
                    </div><br>
                    <div class="card mb-3">
                    <div class="card-body">
                        <div class="form-group">
                            <label for="tipo_trab">Tipo de Trabajo:</label>
                            <input type="text" class="form-control" name="tipo_trab" id="tipo_trab" value="<?= htmlspecialchars($tipo_trab) ?>">
                        </div>
                        <div class="form-group">
                            <label for="fecha_reg">Fecha de Registro:</label>
                            <input type="text" class="form-control" name="fecha_reg" id="fecha_reg" value="<?= htmlspecialchars($fecha_reg) ?>">
                        </div>
                    </div>
                    </div>
                </div>
            </div>

            <!-- Otros campos generales -->
            <center>
            <button type="submit" name="actualizar" class="btn btn-primary">Actualizar</button>
            <button type="submit" name="limpiar" class="btn btn-secondary">Limpiar</button>
            </center>
        </form>
    </div>
    <br><br>
</body>
</html>
