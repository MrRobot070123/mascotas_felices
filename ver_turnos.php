<?php
session_start();
?>

<!DOCTYPE html>
<html>
<head>
<title>Turnos</title>
<link rel="stylesheet" href="style.css">
</head>

<body>

<header>
<h1>Turnos Registrados</h1>

<nav>
<a href="index.php">Inicio</a>
<a href="servicios.php">Servicios</a>
<a href="registro.php">Registrar Turno</a>
<a href="ver_turnos.php">Ver Turnos</a>
</nav>
</header>

<div class="container">
<table>

<tr>
<th>Animal</th>
<th>Especie</th>
<th>Dueño</th>
<th>Telefono</th>
<th>Veterinario</th>
<th>Fecha</th>
<th>Hora</th>
</tr>

<?php
if(isset($_SESSION['turnos'])){
foreach($_SESSION['turnos'] as $t){
echo "<tr>
<td>{$t['animal']}</td>
<td>{$t['especie']}</td>
<td>{$t['dueno']}</td>
<td>{$t['telefono']}</td>
<td>{$t['vet']}</td>
<td>{$t['fecha']}</td>
<td>{$t['hora']}</td>
</tr>";
}
}
?>

</table>

</div>

</body>
</html>