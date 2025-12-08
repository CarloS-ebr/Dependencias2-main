<?php
require_once("../database/conexion.php");
require_once("../models/classempleado.php");
require_once("../models/classdependencia.php");

$conecta = new conexion("172.25.85.224", "gobierno", "postgres", "240416");
$conecta->conectar();

$objEmpleado = new Empleados($conecta);
$objDependencia = new Dependencias($conecta);

$txtNombre = "";
$txtPuesto = "";
$txtDependencia = "";
$id = "";
$etiqueta = "Guardar";

// Guardar o modificar
if (isset($_POST['nombre'], $_POST['puesto'], $_POST['id_dependencia'])) {
    if ($_POST['id_empleado'] == "") {
        $objEmpleado->insertar($_POST['nombre'], $_POST['puesto'], $_POST['id_dependencia']);
    } else {
        $objEmpleado->modificar($_POST['id_empleado'], $_POST['nombre'], $_POST['puesto'], $_POST['id_dependencia']);
    }
}

// Eliminar o preparar para modificar
if (isset($_GET['opcion'])) {
    if ($_GET['opcion'] == 'eliminar') {
        $id = $_GET['id'];
        $objEmpleado->eliminar($id);
    }
    if ($_GET['opcion'] == 'modificar') {
        $id = $_GET['id'];
        $datosEmpleado = $objEmpleado->buscar($id);
        while ($row = pg_fetch_row($datosEmpleado)) {
            $txtNombre = $row[1];
            $txtPuesto = $row[2];
            $txtDependencia = $row[3];
        }
        $etiqueta = "Modificar";
    }
}

$datos = $objEmpleado->listar();
$dependencias = $objDependencia->listar();
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Empleados</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<?php include("navbar.php"); ?>

<div class="container">

  <h2 class="mb-4">Gestión de Empleados</h2>

  <div class="card shadow p-3 mb-4">
    <h5>Nuevo Empleado</h5>
    <form method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
      <input type="hidden" name="id_empleado" value="<?php echo $id; ?>">
      <div class="row">
        <div class="col-md-4 mb-3">
          <label class="form-label">Nombre</label>
          <input type="text" class="form-control" name="nombre" value="<?php echo $txtNombre; ?>" required>
        </div>
        <div class="col-md-4 mb-3">
          <label class="form-label">Puesto</label>
          <input type="text" class="form-control" name="puesto" value="<?php echo $txtPuesto; ?>" required>
        </div>
        <div class="col-md-4 mb-3">
          <label class="form-label">Dependencia</label>
          <select class="form-select" name="id_dependencia" required>
            <option value="">Seleccionar...</option>
            <?php
            while ($row = pg_fetch_row($dependencias)) {
                $selected = ($txtDependencia == $row[0]) ? "selected" : "";
                echo "<option value='{$row[0]}' $selected>{$row[1]}</option>";
            }
            ?>
          </select>
        </div>
      </div>
      <button class="btn btn-primary"><?php echo $etiqueta; ?></button>
    </form>
  </div>

  <div class="card shadow p-3">
    <h5>Lista de Empleados</h5>
    <table class="table">
      <thead>
        <tr>
          <th>ID</th>
          <th>Nombre</th>
          <th>Puesto</th>
          <th>Dependencia</th>
          <th colspan="2">Opciones</th>
        </tr>
      </thead>
      <tbody>
        <?php
        if ($datos) {
            while ($row = pg_fetch_row($datos)) {
                echo "<tr>
                    <td>{$row[0]}</td>
                    <td>{$row[1]}</td>
                    <td>{$row[2]}</td>
                    <td>{$row[3]}</td>
                    <td><a href='?opcion=eliminar&id={$row[0]}' class='btn btn-danger btn-sm'>Eliminar</a></td>
                    <td><a href='?opcion=modificar&id={$row[0]}' class='btn btn-warning btn-sm'>Modificar</a></td>
                </tr>";
            }
        } else {
            echo "<tr><td colspan='6'>No hay datos o la tabla no existe.</td></tr>";
        }
        ?>
      </tbody>
    </table>
  </div>

</div>

</body>
</html>