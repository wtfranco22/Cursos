</div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-fQybjgWLrvvRgtW6bFlB7jaZrFsaBXjsOMm/tB9LTS58ONXgqbR9W8oWht/amnpF" crossorigin="anonymous"></script>
<script>
    $("#dni").on("change", function() {
        $("#formdni").attr("value",this.value);
        //al detectar cambio de valor realizamos llamado a la funcion de ajax con metodo post
        $.post("./control/buscarParticipante.php", {
            dni:this.value
        }, function(data) {
            console.log(data);
            $("#formularioinscripcion").removeClass("d-none");
            if(data != null){
                let objectoParticipante = data[0];
                $("#form1vez").attr("value", "0");
                $("#formdni").attr("value", objectoParticipante.dni);
                $("#formnombre").attr("value",objectoParticipante.nombre);
                $("#formapellido").attr("value",objectoParticipante.apellido);
                $("#formfechanacimiento").attr("value",objectoParticipante.fechanacimiento);
                let genero = document.getElementById("formgenero");
                (objectoParticipante.genero==0) ? genero.removeChild(genero.lastElementChild) : genero.removeChild(genero.firstElementChild);
            }else{
                $("#form1vez").attr("value", "1");
            }
        }, "json"); //de esta manera detectamos la respuesta de que tipo es
    });
</script>
</body>

</html>