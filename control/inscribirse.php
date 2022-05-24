<?php
include_once("./ControlParticipante.php");
include_once("../modelo/Participante.php");
include_once("../modelo/Curso.php");
include_once("../modelo/Cursa.php");
include_once("../modelo/Genero.php");
include_once("../modelo/conector/BaseDeDatos.php");
$control = new ControlParticipante();
$datos = [];
$datos['dni'] = $_POST['formdni'];
$datos['legajo'] = $_POST['formlegajo'];
if($_POST['form1vez']=='1'){
    $datos['nombre'] = $_POST['formnombre'];
    $datos['apellido'] = $_POST['formapellido'];
    $datos['fechanacimiento'] = $_POST['formfechanacimiento'];
    $datos['genero']=$_POST['formgenero'];
    $control->alta($datos);
}else{
    $control->registrar($datos);
}
header("Location:../mostrarCurso.php?legajo=".$datos['legajo']);
?>