<?php
class Cursa
{
    private $objetoParticipante;
    private $objetoCurso;
    private $mensajeOperacion;

    public function __construct()
    {
        $this->objetoParticipante = new Participante();
        $this->objetoCurso = new Curso();
        $this->mensajeOperacion = "";
    }

    /**
     * @param Participante $objetoParticipante
     * @param Curso $objetoCurso
     */
    public function setear($objetoParticipante, $objetoCurso)
    {
        $this->objetoParticipante = $objetoParticipante;
        $this->objetoCurso = $objetoCurso;
    }

    //OBSERVADORAS
    /**
     * @return Participante
     */
    public function getParticipante()
    {
        return $this->objetoParticipante;
    }

    /**
     * @return Curso
     */
    public function getCurso()
    {
        return $this->objetoCurso;
    }

    //MODIFICADORAS
    /**
     * @param Participante $objetoParticipante
     */
    public function setParticipante($objetoParticipante)
    {
        $this->objetoParticipante = $objetoParticipante;
    }

    /**
     * @param Curso $objetoCurso
     */
    public function setCurso($objetoCurso)
    {
        $this->objetoCurso = $objetoCurso;
    }

    /**
     * @param string $mensaje
     */
    public function setMensajeOperacion($mensaje)
    {
        $this->mensajeOperacion = $mensaje;
    }

    /**
     * solo necesitamos que la persona/curso tenga su id seteado para cargar todos los demas valores
     * @return boolean
     */
    public function cargar()
    {
        $resp = false;
        $base = new BaseDatos();
        $sql = "SELECT * FROM cursa WHERE legajo = '" . $this->getCurso()->getLegajo() . "' AND idparticipante = " . $this->getParticipante()->getId() . ";";
        if ($base->Iniciar()) {
            $res = $base->Ejecutar($sql);
            if ($res > -1) {
                if ($res > 0) {
                    $fila = $base->Registro();
                    $objetoCurso = new Curso();
                    $objetoCurso->setLegajo($fila['legajo']);
                    $objetoCurso->cargar();
                    $coleccionPersonas = (json_decode(file_get_contents("https://weblogin.muninqn.gov.ar/api/Examen")))->value;
                    $objetoParticipante = $this->cargarParticipante($coleccionPersonas, $fila['idparticipante']);
                    $this->setear($objetoParticipante, $objetoCurso);
                    $resp = true;
                }
            }
        } else {
            $this->setMensajeOperacion("Cursa->listar: " . $base->getError());
        }
        return $resp;
    }

    /**
     * una vez que la Persona/Curso tenga sus valores seteados insertamos un nuevo curso inscripto
     * con estos valores en la base de datos
     * @return boolean
     */
    public function insertar()
    {
        $resp = false;
        $base = new BaseDatos();
        $sql = "INSERT INTO cursa (idparticipante,legajo)  VALUES('" . $this->getParticipante()->getId() . "','" . $this->getCurso()->getLegajo() . "');";
        if ($base->Iniciar()) {
            if ($base->Ejecutar($sql)) {
                $resp = true;
            } else {
                $this->setMensajeOperacion("Cursa->insertar: " . $base->getError());
            }
        } else {
            $this->setMensajeOperacion("Cursa->insertar: " . $base->getError());
        }
        return $resp;
    }

    /**
     * para borrar el Cursa de manera permanente lo debemos hacer en la base de datos
     * entonces al estar seteada el id, nos basta para buscarlos y realizar un DELETE
     * @return boolean
     */
    public function eliminar()
    {
        $resp = false;
        $base = new BaseDatos();
        $sql = "DELETE FROM cursa WHERE idparticipante=" . $this->getParticipante()->getId() . " AND legajo='" . $this->getCurso()->getLegajo() . "';";
        if ($base->Iniciar()) {
            if ($base->Ejecutar($sql)) {
                return true;
            } else {
                $this->setMensajeOperacion("Cursa->eliminar: " . $base->getError());
            }
        } else {
            $this->setMensajeOperacion("Cursa->eliminar: " . $base->getError());
        }
        return $resp;
    }

    /**
     * @param string $parametro
     * @return array
     */
    public static function listarParticipantes($parametro = "")
    {
        $arreglo = array();
        $base = new BaseDatos();
        $sql = "SELECT * FROM cursa ";
        if ($parametro != "") {
            $sql .= 'WHERE ' . $parametro;
        }
        $res = $base->Ejecutar($sql);
        if ($res > -1) {
            if ($res > 0) {
                $coleccionPersonas = (json_decode(file_get_contents("https://weblogin.muninqn.gov.ar/api/Examen")))->value;
                while ($fila = $base->Registro()) {
                    $objParticipante = Cursa::cargarParticipante($coleccionPersonas, $fila['idparticipante']);
                    array_push($arreglo, $objParticipante);
                }
            }
        } else {
            Cursa::setMensajeOperacion("Cursa->listar: " . $base->getError());
        }
        return $arreglo;
    }

    /**
     * cargamos todos los cursos que esta realizando el participante
     * @param String $parametro
     * @return Array
     */
    public static function listarCursos($parametro = "")
    {
        $arreglo = array();
        $base = new BaseDatos();
        $sql = "SELECT * FROM cursa ";
        if ($parametro != "") {
            $sql .= 'WHERE ' . $parametro;
        }
        $res = $base->Ejecutar($sql);
        if ($res > -1) {
            if ($res > 0) {
                while ($fila = $base->Registro()) {
                    $objCurso = new Curso();
                    $objCurso->setLegajo($fila['legajo']);
                    $objCurso->cargar();
                    array_push($arreglo, $objCurso);
                }
            }
        } else {
            Cursa::setMensajeOperacion("Cursa->listar: " . $base->getError());
        }
        return $arreglo;
    }

    /**
     * con los datos desde la api junto con el id de la persona inscripta en el curso cargamos el objeto participante
     * dependiendo si se encuentra en nuestra base de datos o se encuentra sus datos en la api
     * @param Array $coleccionPersonas
     * @param String $idParticipante
     * @return Participante
     */
    public static function cargarParticipante($coleccionPersonas, $idParticipante)
    {
        $objParticipante = new Participante();
        if (strncmp($idParticipante, 'bd-', 3) === 0) {
            $objParticipante->setId($idParticipante);
            $objParticipante->cargar();
        } else {
            $personaEncontrada = false;
            $contador = 0;
            while (!$personaEncontrada && $contador<100) {
                if ($coleccionPersonas[$contador]->id == $idParticipante) {
                    $participante = $coleccionPersonas[$contador];
                    $objGenero = new Genero();
                    $objGenero->setId($participante->genero->id);
                    $objGenero->cargar();
                    $apellidoNombre = explode(",", $participante->razonSocial); //[apellido,nombre]
                    $fechaNacimiento = (explode("T", $participante->fechaNacimiento))[0]; //solo guarda la fecha, omite el horario
                    $objParticipante->setear($participante->id, $participante->dni, $apellidoNombre[0], $apellidoNombre[1], $objGenero, $fechaNacimiento);
                    $personaEncontrada = true;
                }
                $contador++;
            }
        }
        return $objParticipante;
    }
}
