<?php
include_once("cabecera.php");
?>
<div class="row">
    <form class="col" action="#" method="POST" autocomplete="off">
        <div class="form-group">
            <label for="dni">DNI</label>
            <input type="number" class="form-control" id="dni" name="dni">
            <small id="info" class="form-text text-muted">Si ya esta registrado, se autocompletara el formulario</small>
        </div>
    </form>
</div>
<div class="row d-none" id="formularioinscripcion">
    <form class="col" action="./control/inscribirse.php" method="POST" autocomplete="off">
        <input type="hidden" class="form-control" id="form1vez" name="form1vez">
        <input type="hidden" class="form-control" id="formdni" name="formdni">
        <input type="hidden" class="form-control" id="formlegajo" name="formlegajo" value="<?php echo $_GET['legajo'] ?>">
        <div class="form-group">
            <label for="formnombre">nombre</label>
            <input type="text" class="form-control" id="formnombre" name="formnombre">
        </div>
        <div class="form-group">
            <label for="formapellido">Apellido</label>
            <input type="text" class="form-control" id="formapellido" name="formapellido">
        </div>
        <div class="form-group">
            <label for="formfechanacimiento">Fecha de nacimiento</label>
            <input type="date" class="form-control" id="formfechanacimiento" name="formfechanacimiento">
        </div>
        <div class="form-group">
            <label for="formgenero" class="form-label">Genero</label>
            <select id="formgenero" name="formgenero" class="form-select">
                <option value="0">Hombre</option>
                <option value="1">Mujer</option>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Inscribirse</button>
    </form>
</div>
<?php
include_once("pie.php");
?>