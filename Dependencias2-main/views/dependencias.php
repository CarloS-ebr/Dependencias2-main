<?php
require_once("../database/conexion.php");
require_once("../models/classdependencia.php");

$conecta = new conexion("172.25.85.224", "gobierno", "postgres", "240416");
$conecta->conectar();

$objDependencia = new Dependencias($conecta);
$txtNombre = "";
$txtResponsable = "";
$id = "";
$etiqueta = "Guardar";

// Guardar o modificar
if (isset($_POST['nombre'], $_POST['responsable'])) {
    if ($_POST['id_dependencias'] == "") {
        $objDependencia->insertar($_POST['nombre'], $_POST['responsable']);
    } else {
        $objDependencia->modificar($_POST['id_dependencias'], $_POST['nombre'], $_POST['responsable']);
    }
}

// Eliminar o preparar para modificar
if (isset($_GET['opcion'])) {
    if ($_GET['opcion'] == 'eliminar') {
        $id = $_GET['id'];
        $objDependencia->eliminar($id);
    }
    if ($_GET['opcion'] == 'modificar') {
        $id = $_GET['id'];
        $datosDependencia = $objDependencia->buscar($id);
        while ($row = pg_fetch_row($datosDependencia)) {
            $txtNombre = $row[1];
            $txtResponsable = $row[2];
        }
        $etiqueta = "Modificar";
    }
}

$datos = $objDependencia->listar();
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Dependencias</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<?php include("navbar.php"); ?>

<div class="container">

  <h2 class="mb-4">Gestión de Dependencias</h2>

  <div class="card shadow p-3 mb-4">
    <h5>Nueva Dependencia</h5>
    <form method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
      <input type="hidden" name="id_dependencias" value="<?php echo $id; ?>">
      <div class="row">
        <div class="col-md-6 mb-3">
          <label class="form-label">Nombre</label>
          <input type="text" class="form-control" name="nombre" value="<?php echo $txtNombre; ?>" required>
        </div>
        <div class="col-md-6 mb-3">
          <label class="form-label">Responsable</label>
          <input type="text" class="form-control" name="responsable" value="<?php echo $txtResponsable; ?>" required>
        </div>
      </div>
      <button class="btn btn-primary"><?php echo $etiqueta; ?></button>
    </form>
  </div>

  <div class="card shadow p-3">
    <h5>Lista de Dependencias</h5>
    <table class="table">
      <thead>
        <tr>
          <th>ID</th>
          <th>Nombre</th>
          <th>Responsable</th>
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
                        <td><a href='?opcion=eliminar&id={$row[0]}' class='btn btn-danger btn-sm'>Eliminar</a></td>
                        <td><a href='?opcion=modificar&id={$row[0]}' class='btn btn-warning btn-sm'>Modificar</a></td>
                    </tr>";
                }
            } else {
                echo "<tr><td colspan='5'>No hay datos o la tabla no existe.</td></tr>";
            }
          ?>
      </tbody>
    </table>
  </div>

</div>

</body>
</html>