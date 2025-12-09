<?php
require_once("../database/conexion.php");
require_once("../models/classempleado.php");
require_once("../models/classdependencia.php");
require_once("../models/classasignaciones.php");
require_once("../models/classmobiliario.php"); 

$conecta = new conexion("172.25.85.224", "gobierno", "postgres", "240416");
$conecta->conectar();

$objEmpleado = new Empleados($conecta);
$objDependencia = new Dependencias($conecta);
$objAsignaciones = new Asignaciones($conecta);
$objMobiliario = new Mobiliario($conecta); 

$txtNombre = "";
$txtPuesto = "";
$txtDependencia = "";
$idEmpleadoForm = "";
$etiqueta = "Guardar";



if (isset($_POST['nombre'], $_POST['puesto'], $_POST['id_dependencia'])) {
    if (empty($_POST['id_empleado'])) {
        $objEmpleado->insertar($_POST['nombre'], $_POST['puesto'], $_POST['id_dependencia']);
    } else {
        $objEmpleado->modificar($_POST['id_empleado'], $_POST['nombre'], $_POST['puesto'], $_POST['id_dependencia']);
    }
    header("Location: empleados.php");
    exit;
}



if (isset($_GET['opcion']) && $_GET['opcion'] === 'eliminar') {
    $id = (int)$_GET['id'];

    $resultado = $objEmpleado->eliminar($id);

    
    if (is_array($resultado) && isset($resultado['error']) && $resultado['error'] === true) {
        header("Location: empleados.php?confirmar=1&id=$id");
        exit;
    }

    header("Location: empleados.php?ok=1");
    exit;
}



if (isset($_GET['opcion']) && $_GET['opcion'] === 'eliminar_todo') {
    $id = (int)$_GET['id'];

    $objMobiliario->eliminarPorEmpleado($id);

    // 2. Eliminar asignaciones
    $objAsignaciones->eliminarPorEmpleado($id);

    // 3. Eliminar el empleado
    $objEmpleado->eliminar($id);

    header("Location: empleados.php?ok_total=1");
    exit;
}



if (isset($_GET['opcion']) && $_GET['opcion'] === 'modificar') {
    $id = (int)$_GET['id'];
    $datos = $objEmpleado->buscar($id);

    if ($datos && $row = pg_fetch_row($datos)) {
        $idEmpleadoForm = $row[0];
        $txtNombre = $row[1];
        $txtPuesto = $row[2];
        $txtDependencia = $row[3];
    }

    $etiqueta = "Modificar";
}



$listaEmpleados = $objEmpleado->listar();
$listaDependencias = $objDependencia->listar();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Gestión de Empleados</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<?php include("navbar.php"); ?>

<div class="container py-4">


    
    <?php if (isset($_GET['ok'])): ?>
        <div class="alert alert-success">Empleado eliminado correctamente.</div>
    <?php endif; ?>

    
    <?php if (isset($_GET['ok_total'])): ?>
        <div class="alert alert-success">Empleado, asignaciones  fueron eliminados correctamente.</div>
    <?php endif; ?>

    
    <?php if (isset($_GET['confirmar']) && isset($_GET['id'])): ?>
        <div class="alert alert-warning">
            <h5>Este empleado tiene asignaciones  asignado</h5>
            <p>¿Deseas eliminar todo?</p>
            <a href="empleados.php?opcion=eliminar_todo&id=<?php echo (int)$_GET['id']; ?>" class="btn btn-danger">Sí, eliminar todo</a>
            <a href="empleados.php" class="btn btn-secondary">Cancelar</a>
        </div>
    <?php endif; ?>


  
    <div class="card mb-4">
        <div class="card-body">
            <h5 class="card-title">Nuevo Empleado</h5>
            <form method="POST" action="empleados.php">
                <input type="hidden" name="id_empleado" value="<?php echo htmlspecialchars($idEmpleadoForm); ?>">

                <div class="row mb-3">
                    <div class="col-md-4">
                        <label class="form-label">Nombre</label>
                        <input type="text" name="nombre" class="form-control" value="<?php echo htmlspecialchars($txtNombre); ?>" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Puesto</label>
                        <input type="text" name="puesto" class="form-control" value="<?php echo htmlspecialchars($txtPuesto); ?>" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Dependencia</label>
                        <select name="id_dependencia" class="form-select" required>
                            <option value="">Seleccionar...</option>
                            <?php
                            if ($listaDependencias) {
                                while ($row = pg_fetch_row($listaDependencias)) {
                                    $sel = ($txtDependencia == $row[0]) ? "selected" : "";
                                    echo "<option value='{$row[0]}' $sel>" . htmlspecialchars($row[1]) . "</option>";
                                }
                            }
                            ?>
                        </select>
                    </div>
                </div>

                <button class="btn btn-primary"><?php echo $etiqueta; ?></button>
            </form>
        </div>
    </div>


    <!-- LISTA DE EMPLEADOS -->
    <div class="card">
        <div class="card-body">
            <h5 class="card-title">Lista de Empleados</h5>
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Puesto</th>
                        <th>Dependencia</th>
                        <th>Opciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($listaEmpleados) {
                        while ($row = pg_fetch_row($listaEmpleados)) {
                            echo "<tr>";
                            echo "<td>{$row[0]}</td>";
                            echo "<td>" . htmlspecialchars($row[1]) . "</td>";
                            echo "<td>" . htmlspecialchars($row[2]) . "</td>";
                            echo "<td>" . htmlspecialchars($row[3]) . "</td>";
                            echo "<td>
                                    <a href='empleados.php?opcion=modificar&id={$row[0]}' class='btn btn-warning btn-sm'>Modificar</a>
                                    <a href='empleados.php?opcion=eliminar&id={$row[0]}' class='btn btn-danger btn-sm'>Eliminar</a>
                                  </td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='5'>No hay empleados registrados.</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>

</div>
</body>
</html>
