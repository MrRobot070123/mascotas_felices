<?php
session_start();

if(isset($_SESSION['mensaje'])){
    echo "
    <script>
    document.addEventListener('DOMContentLoaded', function(){
        Swal.fire({
            icon:'success',
            title:'Proceso realizado',
            text:'".$_SESSION['mensaje']."'
        });
    });
    </script>
    ";
    unset($_SESSION['mensaje']);
}
?>
<!DOCTYPE html>
<html>
<head>
<title>Registrar Turno</title>
<link rel="stylesheet" href="style.css">
</head>

<body>

<header>
<h1>Registrar Turno</h1>

<nav>
<a href="index.php">Inicio</a>
<a href="servicios.php">Servicios</a>
<a href="registro.php">Registrar Turno</a>
<a href="ver_turnos.php">Ver Turnos</a>
</nav>
</header>

<div class="container">

<form action="guardar_turno.php" method="POST">

Nombre Animal:
<input type="text" name="animal" required>

Especie:
<select name="especie" required>
<option value="">Seleccione</option>
<option>Perro</option>
<option>Gato</option>
<option>Ave</option>
<option>Otro</option>
</select>

Nombre Dueño:
<input type="text" name="dueno" required>

Teléfono:
<input type="text" name="telefono" required pattern="[0-9]{10}">

Veterinario:
<select name="vet" required>
<option>Dra. López</option>
<option>Dr. Ramírez</option>
</select>

Fecha:
<input type="date" name="fecha" required>

Hora:
<input type="time" name="hora" required>

<button type="submit">Guardar Turno</button>

</form>

</div>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</body>
</html>