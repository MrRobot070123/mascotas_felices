<?php
session_start();

if(!isset($_SESSION['turnos'])){
    $_SESSION['turnos']=array();
}

$animal = $_POST['animal'];
$especie = $_POST['especie'];
$dueno = $_POST['dueno'];
$telefono = $_POST['telefono'];
$vet = $_POST['vet'];
$fecha = $_POST['fecha'];
$hora = $_POST['hora'];

/* VALIDACION FECHA */
if($fecha < date("Y-m-d")){
    echo "Fecha inválida";
    exit;
}

/* LIMITE 2 TURNOS POR DIA */
$contador=0;
foreach($_SESSION['turnos'] as $t){
    if($t['dueno']==$dueno && $t['fecha']==$fecha){
        $contador++;
    }
}

if($contador>=2){
    echo "Máximo 2 turnos por día";
    exit;
}

/* CRUCE MISMO VETERINARIO */
foreach($_SESSION['turnos'] as $t){
    if($t['fecha']==$fecha && $t['hora']==$hora && $t['vet']==$vet){

        $hora = date("H:i", strtotime($hora."+30 minutes"));
        echo "Turno ocupado. Se sugiere nueva hora: ".$hora;
    }
}

/* PERROS Y GATOS NO PUEDEN COINCIDIR */
foreach($_SESSION['turnos'] as $t){
    if($t['fecha']==$fecha && $t['hora']==$hora){

        if(($t['especie']=="Perro" && $especie=="Gato") ||
           ($t['especie']=="Gato" && $especie=="Perro")){
            echo "Perros y gatos no pueden coincidir";
            exit;
        }
    }
}

/* GUARDAR */
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

header("Location: ver_turnos.php");
?>