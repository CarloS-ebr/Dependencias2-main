<?php
require_once("../database/conexion.php");
require_once("../models/classdependencia.php");
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Menu</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<?php include("navbar.php"); ?>

<div class="container">

  <h2 class="mb-4">Menu</h2>

  <div class="row">

    <div class="col-md-3">
      <div class="card shadow text-center p-3">
        <h5>Dependencias</h5>
        <p class="display-6">12</p>
      </div>
    </div>

    <div class="col-md-3">
      <div class="card shadow text-center p-3">
        <h5>Empleados</h5>
        <p class="display-6">80</p>
      </div>
    </div>

    <div class="col-md-3">
      <div class="card shadow text-center p-3">
        <h5>Mobiliario</h5>
        <p class="display-6">430</p>
      </div>
    </div>

    <div class="col-md-3">
      <div class="card shadow text-center p-3">
        <h5>Asignaciones</h5>
        <p class="display-6">380</p>
      </div>
    </div>

  </div>

</div>

</body>
</html>
