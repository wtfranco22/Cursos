<?php 
include_once("../modelo/Participante.php");
include_once("../modelo/Genero.php");
include_once("../modelo/conector/BaseDeDatos.php");
$dni = $_POST['dni'];
$participante = new Participante();
$participante->setDni($dni);
$res = [];
if($participante->cargarParticipanteConDni()){
    $res[]=[
        "nombre"=>$participante->getNombre(),
        "apellido"=>$participante->getApellido(),
        "genero"=>$participante->getGenero(),
        "fechanacimiento"=>$participante->getFechaNacimiento()
    ];
}else{
    $res = null;
};
print_r(json_encode($res));
?>