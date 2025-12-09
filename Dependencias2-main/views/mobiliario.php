<?php

require_once("../database/conexion.php");
require_once("../models/classmobiliario.php");
require_once("../models/classdependencia.php");

$conecta = new Conexion("172.25.85.224", "gobierno", "postgres", "240416");
$conecta->conectar();

$objMobiliario = new Mobiliario($conecta);
$objDependencia = new Dependencias($conecta);

$txtNombre = "";
$txtInventario = "";
$selDependencia = "";
$id = "";
$etiqueta = "Guardar";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id_mobiliario'] ?? "";
    $nombre = $_POST['nombre'] ?? "";
    $inventario = $_POST['numero_inventario'] ?? "";
    $id_dep = $_POST['id_dependencia'] ?? "";

    if ($id === "") {
        $objMobiliario->insertar($nombre, $inventario, $id_dep);
    } else {
        $objMobiliario->modificar($id, $nombre, $inventario, $id_dep);
    }
    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}

if (isset($_GET['opcion'])) {
    $op = $_GET['opcion'];
    $id = $_GET['id'] ?? "";
   if ($op === 'eliminar' && $id !== "") {

    $resultado = $objMobiliario->eliminar($id);

    if ($resultado === "FK_ERROR") {
        header("Location: mobiliario.php?error=1");
        exit;
    }

    if ($resultado === false) {
        header("Location: mobiliario.php?error2=1");
        exit;
    }

    header("Location: mobiliario.php?eliminado=1");
    exit;
}
    if ($op === 'modificar' && $id !== "") {
        $datos = $objMobiliario->buscar($id);
        if ($datos && $row = pg_fetch_assoc($datos)) {
            $txtNombre = $row['nombre'];
            $txtInventario = $row['numero_inventario'];
            $selDependencia = $row['id_dependencia'];
            $etiqueta = "Modificar";
        }
    }
}

$dependencias = $objDependencia->listar();
$datos = $objMobiliario->listar();
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Mobiliario</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<?php include("navbar.php"); ?>

<div class="container">
  

<?php if (isset($_GET['error2'])): ?>
    <div class="alert alert-danger">
        No se puede eliminar este mobiliario porque está asignado a un asignacion
    </div>
<?php endif; ?>

<?php if (isset($_GET['eliminado'])): ?>
    <div class="alert alert-success">
        Mobiliario eliminado correctamente.
    </div>
<?php endif; ?>

  <h2 class="mb-4">Gestión de Mobiliario</h2>

  <div class="card shadow p-3 mb-4">
    <h5>Nuevo Mobiliario</h5>
    <form method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
      <input type="hidden" name="id_mobiliario" value="<?php echo htmlspecialchars($id); ?>">
      <div class="row">
        <div class="col-md-4 mb-3">
          <label class="form-label">Nombre</label>
          <input type="text" name="nombre" class="form-control" value="<?php echo htmlspecialchars($txtNombre); ?>" required>
        </div>

        <div class="col-md-4 mb-3">
          <label class="form-label">Número Inventario</label>
          <input type="text" name="numero_inventario" class="form-control" value="<?php echo htmlspecialchars($txtInventario); ?>" required>
        </div>

        <div class="col-md-4 mb-3">
          <label class="form-label">Dependencia</label>
          <select name="id_dependencia" class="form-select" required>
            <option value="">Seleccionar...</option>
            <?php
              if ($dependencias) {
                  while ($row = pg_fetch_assoc($dependencias)) {
                      $sel = ($row['id_dependencia'] == $selDependencia) ? "selected" : "";
                      echo "<option value='{$row['id_dependencia']}' $sel>".htmlspecialchars($row['nombre'])."</option>";
                  }
              }
            ?>
          </select>
        </div>
      </div>
      <button class="btn btn-primary"><?php echo $etiqueta; ?></button>
    </form>
  </div>

  <div class="card shadow p-3">
    <h5>Lista de Mobiliario</h5>
    <table class="table">
      <thead><tr><th>ID</th><th>Nombre</th><th>Inventario</th><th>Dependencia</th><th colspan="2">Opciones</th></tr></thead>
      <tbody>
        <?php
          if ($datos) {
              while ($row = pg_fetch_assoc($datos)) {
                  echo "<tr>
                          <td>{$row['id_mobiliario']}</td>
                          <td>".htmlspecialchars($row['nombre'])."</td>
                          <td>".htmlspecialchars($row['numero_inventario'])."</td>
                          <td>".htmlspecialchars($row['dependencia'])."</td>
                          <td><a href='?opcion=eliminar&id={$row['id_mobiliario']}' class='btn btn-danger btn-sm'>Eliminar</a></td>
                          <td><a href='?opcion=modificar&id={$row['id_mobiliario']}' class='btn btn-warning btn-sm'>Modificar</a></td>
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
