<?php
session_start();

function alertaSweet($titulo,$mensaje,$icono="error",$redirigir="registro.php"){
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body>

<script>

Swal.fire({
title: '<?php echo $titulo ?>',
html: '<?php echo $mensaje ?>',
icon: '<?php echo $icono ?>',
confirmButtonText: 'Aceptar'
}).then(()=>{
window.location.href='<?php echo $redirigir ?>';
});

</script>

</body>
</html>
<?php
exit;
}

if(!isset($_SESSION['turnos'])){
    $_SESSION['turnos']=array();
}

function fechaBonita($fecha){
    setlocale(LC_TIME, 'es_ES.UTF-8');
    return strftime("%d de %B", strtotime($fecha));
}

function horaBonita($hora){
    return date("g:i a", strtotime($hora));
}

/* SI VIENE CONFIRMACION */
if(isset($_POST['confirmar'])){

    if($_POST['confirmar']=="si"){

        $_SESSION['turnos'][] = $_SESSION['turno_temp'];
        unset($_SESSION['turno_temp']);

        $_SESSION['mensaje']="Cita agendada con éxito";
        header("Location: registro.php");
        exit;

    }else{

        unset($_SESSION['turno_temp']);
        $_SESSION['mensaje']="Cita no agendada";
        header("Location: registro.php");
        exit;
    }
}

/* DATOS FORMULARIO */

$animal = $_POST['animal'];
$especie = $_POST['especie'];
$dueno = $_POST['dueno'];
$telefono = $_POST['telefono'];
$vet = $_POST['vet'];
$fecha = $_POST['fecha'];
$hora = $_POST['hora'];

/* VALIDAR FECHA */
if($fecha < date("Y-m-d")){
    alertaSweet(
        "Fecha inválida",
        "No puedes agendar turnos en fechas anteriores a hoy",
        "error"
    );
}

/* VALIDAR LIMITE 2 TURNOS */
$cont=0;
foreach($_SESSION['turnos'] as $t){
    if($t['dueno']==$dueno && $t['fecha']==$fecha){
        $cont++;
    }
}
if($cont>=2){
    alertaSweet(
    "Limite alcanzado",
    "$dueno ya tiene 2 turnos registrados ese día",
    "warning"
    );
    exit;
}

/* DISPONIBILIDAD REAL DEL VETERINARIO */

$horasVet = [];

foreach($_SESSION['turnos'] as $t){

    if($t['fecha']==$fecha && $t['vet']==$vet){
        $horasVet[] = $t['hora'];
    }
}

/* SI EL VETERINARIO TIENE CITAS ESE DIA */
if(count($horasVet) > 0){

    sort($horasVet);

    $horaSolicitada = $hora;
    $horaDisponible = $horaSolicitada;

    while(in_array($horaDisponible, $horasVet)){
        $horaDisponible = date("H:i", strtotime($horaDisponible . "+30 minutes"));
    }

    if($horaDisponible != $horaSolicitada){

        $_SESSION['turno_temp']=array(
        "animal"=>$animal,
        "especie"=>$especie,
        "dueno"=>$dueno,
        "telefono"=>$telefono,
        "vet"=>$vet,
        "fecha"=>$fecha,
        "hora"=>$horaDisponible
        );
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body>

<script>

Swal.fire({
title: 'Veterinario no disponible',
html: '<?php echo $vet ?> no está disponible para el día <?php echo fechaBonita($fecha) ?> a las <?php echo horaBonita($hora) ?><br><br>Disponible desde <?php echo horaBonita($horaDisponible) ?>',
icon: 'warning',
showCancelButton: true,
confirmButtonText: 'Agendar',
cancelButtonText: 'Cancelar'
}).then((result) => {

    const form = document.createElement('form');
    form.method='POST';
    form.action='guardar_turno.php';

    const input = document.createElement('input');
    input.type='hidden';
    input.name='confirmar';

    if(result.isConfirmed){
        input.value='si';
    }else{
        input.value='no';
    }

    form.appendChild(input);
    document.body.appendChild(form);
    form.submit();

});

</script>

</body>
</html>

<?php
        exit;
    }
}

/* VALIDAR PERRO Y GATO */
foreach($_SESSION['turnos'] as $t){

    if($t['fecha']==$fecha && $t['hora']==$hora){

        if(($t['especie']=="Perro" && $especie=="Gato") ||
           ($t['especie']=="Gato" && $especie=="Perro")){
            alertaSweet(
            "Incompatibilidad de especies",
            "No pueden coincidir perros y gatos en la misma hora",
            "warning"
            );
            exit;
        }
    }
}

/* GUARDAR NORMAL */
$turno=array(
"animal"=>$animal,
"especie"=>$especie,
"dueno"=>$dueno,
"telefono"=>$telefono,
"vet"=>$vet,
"fecha"=>$fecha,
"hora"=>$hora
);

$_SESSION['turnos'][]=$turno;

$_SESSION['mensaje']="Cita agendada con éxito";

header("Location: registro.php");

?>