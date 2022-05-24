<?php
include_once("cabecera.php");
include_once("control/ControlCurso.php");
include_once("modelo/Curso.php");
include_once("modelo/Cursa.php");
include_once("modelo/Genero.php");
include_once("modelo/Participante.php");
include_once("modelo/conector/BaseDeDatos.php");
$arregloCurso = Curso::listar("legajo='" . $_GET['legajo'] . "'");
$curso = $arregloCurso[0];
$hoy = new DateTime(date("Y-m-d"));
$cantM = 0;
$cantF = 0;
$cantMen = 0;
$cantMay = 0;
?>

<div class="row m-2">
    <div class="col">
        <h5 class="text-center">Curso <?php echo $curso->getNombre(); ?> </h5>
    </div>
</div>
<table class="table">
    <thead>
        <tr>
            <th scope="col">nombre</th>
            <th scope="col">Apellido</th>
            <th scope="col">Genero</th>
            <th scope="col">edad</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($curso->getParticipantes() as $participante) : ?>
            <tr>
                <th scope="row">
                    <?php echo $participante->getNombre(); ?>
                </th>
                <td>
                    <?php echo $participante->getApellido(); ?>
                </td>
                <td>
                    <?php 
                    $genero = ($participante->getGenero()==0)?"Masculino":"Femenino";
                    ($genero=="Masculino") ? $cantM++ : $cantF++;
                    echo $genero;
                    ?>
                </td>
                <td>
                    <?php
                    $nacimiento = new DateTime($participante->getFechaNacimiento());
                    $diferencia = $hoy->diff($nacimiento);
                    $edad = $diferencia->format("%y");
                    ($edad>18)? $cantMay++ : $cantMen; 
                    echo $edad;
                    ?>
                </td>
            </tr>
            <tr>
            <?php endforeach; ?>
            <th class="center" colspan="4" scrope=col>
                Cantidad de:
            </th>
            <tr>
                <td>Mujeres: <?php echo $cantF;?></td>
                <td>Hombres: <?php echo $cantM;?></td>
                <td>Mayores: <?php echo $cantMay;?></td>
                <td>Menores: <?php echo $cantMen;?></td>
            </tr>
    </tbody>
</table>

<?php
include_once("pie.php");
?>