<?php
include_once("cabecera.php");
include_once("control/ControlCurso.php");
include_once("modelo/Curso.php");
include_once("modelo/Cursa.php");
include_once("modelo/Participante.php");
include_once("modelo/Genero.php");
include_once("modelo/conector/BaseDeDatos.php");
$controlCurso = new ControlCurso();
$cursos = $controlCurso->buscarCursos($_GET['m']);
?>

<div class="row m-2">
  <div class="col">
    <h5 class="text-center">Cursos con la modalidad <?php echo $_GET['m'] ?> </h5>
  </div>
</div>
<table class="table">
  <thead>
    <tr>
      <th scope="col">legajo</th>
      <th scope="col">Nombre</th>
      <th scope="col">Descripcion</th>
      <th scope="col">
      <a href="crearCurso.php" role="button" class="btn btn-secondary btn-sm">+</a>
      </th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($cursos as $curso) : ?>
      <tr>
        <th scope="row"><?php echo $curso->getLegajo(); ?></th>
        <td><?php echo $curso->getNombre(); ?></td>
        <td><?php echo $curso->getDescripcion(); ?></td>
        <td>
          <div class="btn-group" role="group">
            <a href="inscripcion.php?legajo=<?php echo$curso->getLegajo()?>" role="button" class="btn btn-secondary btn-sm mr-2">Inscribirse</a>
            <a href="mostrarCurso.php?legajo=<?php echo$curso->getLegajo()?>" role="button" class="btn btn-secondary btn-sm">Listado</a>
          </div>
        </td>
      </tr>
      <tr>
      <?php endforeach; ?>
  </tbody>
</table>

<?php
include_once("pie.php");
?>