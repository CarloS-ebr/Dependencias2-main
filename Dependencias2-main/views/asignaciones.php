<?php
require_once("../database/conexion.php");
require_once("../models/classasignaciones.php");
require_once("../models/classmobiliario.php");

$conecta = new conexion("172.25.85.224", "gobierno", "postgres", "240416");
$conecta->conectar();

$objAsign = new Asignaciones($conecta);
$objMobiliario = new Mobiliario($conecta);

$empleados = $conecta->ejecutar("SELECT id_empleado, nombre FROM empleado ORDER BY id_empleado");
$mobiliarios = $objMobiliario->listar();

$id = "";
$selEmpleado = "";
$selMobiliario = "";
$fecha = date('Y-m-d');
$etiqueta = "Guardar";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id_asignacion'] ?? "";
    $idem = $_POST['id_empleado'] ?? "";
    $idm = $_POST['id_mobiliario'] ?? "";
    $fecha = $_POST['fecha'] ?? date('Y-m-d');

    if ($id === "") {
        $objAsign->insertar($idem, $idm, $fecha);
    } else {
        $objAsign->modificar($id, $idem, $idm, $fecha);
    }
    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}

if (isset($_GET['opcion'])) {
    $op = $_GET['opcion'];
    $id = $_GET['id'] ?? "";
    if ($op === 'eliminar' && $id !== "") {
        $objAsign->eliminar($id);
        header("Location: " . $_SERVER['PHP_SELF']);
        exit;
    }
    if ($op === 'modificar' && $id !== "") {
        $datos = $objAsign->buscar($id);
        if ($datos && $row = pg_fetch_assoc($datos)) {
            $selEmpleado = $row['id_empleado'];
            $selMobiliario = $row['id_mobiliario'];
            $fecha = $row['fecha'];
            $etiqueta = "Modificar";
        }
    }
}
 
$datos = $objAsign->listar();
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Asignaciones</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<?php include("navbar.php"); ?>

<div class="container">
  <h2 class="mb-4">Gestión de Asignaciones</h2>

  <div class="card shadow p-3 mb-4">
    <h5>Nueva Asignación</h5>
    <form method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
      <input type="hidden" name="id_asignacion" value="<?php echo htmlspecialchars($id); ?>">
      <div class="row">
        <div class="col-md-4 mb-3">
          <label class="form-label">Empleado</label>
          <select name="id_empleado" class="form-select" required>
            <option value="">Seleccionar...</option>
            <?php
              if ($empleados) {
                  while ($r = pg_fetch_assoc($empleados)) {
                      $sel = ($r['id_empleado'] == $selEmpleado) ? "selected" : "";
                      echo "<option value='{$r['id_empleado']}' $sel>".htmlspecialchars($r['nombre'])."</option>";
                  }
              }
            ?>
          </select>
        </div>

        <div class="col-md-4 mb-3">
          <label class="form-label">Mobiliario</label>
          <select name="id_mobiliario" class="form-select" required>
            <option value="">Seleccionar...</option>
            <?php
              if ($mobiliarios) {
                  while ($r = pg_fetch_assoc($mobiliarios)) {
                      $sel = ($r['id_mobiliario'] == $selMobiliario) ? "selected" : "";
                      $label = htmlspecialchars($r['nombre'] . " (" . $r['numero_inventario'] . ")");
                      echo "<option value='{$r['id_mobiliario']}' $sel>$label</option>";
                  }
              }
            ?>
          </select>
        </div>

        <div class="col-md-4 mb-3">
          <label class="form-label">Fecha</label>
          <input type="date" name="fecha" class="form-control" value="<?php echo htmlspecialchars($fecha); ?>" required>
        </div>
      </div>
      <button class="btn btn-primary"><?php echo $etiqueta; ?></button>
    </form>
  </div>

  <div class="card shadow p-3">
    <h5>Lista de Asignaciones</h5>
    <table class="table">
      <thead>
        <tr><th>ID</th><th>Fecha</th><th>Empleado</th><th>Mobiliario</th><th>Inv</th><th colspan="2">Opciones</th></tr>
      </thead>
      <tbody>
        <?php
          if ($datos) {
              while ($row = pg_fetch_assoc($datos)) {
                  echo "<tr>
                          <td>{$row['id_asignacion']}</td>
                          <td>{$row['fecha']}</td>
                          <td>".htmlspecialchars($row['empleado'])."</td>
                          <td>".htmlspecialchars($row['mobiliario'])."</td>
                          <td>".htmlspecialchars($row['numero_inventario'])."</td>
                          <td><a href='?opcion=eliminar&id={$row['id_asignacion']}' class='btn btn-danger btn-sm'>Eliminar</a></td>
                          <td><a href='?opcion=modificar&id={$row['id_asignacion']}' class='btn btn-warning btn-sm'>Modificar</a></td>
                        </tr>";
              }
          } else {
              echo "<tr><td colspan='7'>No hay datos o la tabla no existe.</td></tr>";
          }
        ?>
      </tbody>
    </table>
  </div>
</div>

</body>
</html>
