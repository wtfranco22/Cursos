<?php
class Participante
{
    private $id;
    private $dni;
    private $nombre;
    private $apellido;
    private $genero; //objeto
    private $fechaNacimiento;
    private $cursos;
    private $mensajeOperacion;

    public function __construct()
    {
        $this->id = 0;
        $this->dni = 0;
        $this->nombre = "";
        $this->apellido = "";
        $this->genero = new Genero();
        $this->fechaNacimiento = "";
        $this->cursos = [];
        $this->mensajeOperacion = "";
    }

    /**
     * @param $id int
     * @param $dni int
     * @param $nombre String
     * @param $apellido String
     * @param $objGenero Genero
     * @param $fechaNacimiento String
     */
    public function setear($id, $dni, $nombre, $apellido, $objGenero, $fechaNacimiento)
    {
        $this->id = $id;
        $this->dni = $dni;
        $this->nombre = $nombre;
        $this->apellido = $apellido;
        $this->genero = $objGenero->getId();
        $this->fechaNacimiento = $fechaNacimiento;
    }

    //OBSERVADORES
    /**
     * @return String
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return int
     */
    public function getDni()
    {
        return $this->dni;
    }

    /**
     * @return String
     */
    public function getNombre()
    {
        return $this->nombre;
    }

    /**
     * @return String
     */
    public function getApellido()
    {
        return $this->apellido;
    }

    /**
     * @return Genero
     */
    public function getGenero()
    {
        return $this->genero;
    }

    /**
     * @return String
     */
    public function getFechaNacimiento()
    {
        return $this->fechaNacimiento;
    }

    /**
     * @return boolean
     */
    public function getActivo()
    {
        return $this->activo == 1;
    }

    /**
     * @return Array
     */
    public function getCursos()
    {
        return $this->cursos;
    }

    /**
     * @return String
     */
    public function getMensajeOperacion()
    {
        return $this->mensajeOperacion;
    }

    //MODIFICADORES
    /**
     * @param String $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @param int $dni
     */
    public function setDni($dni)
    {
        $this->dni = $dni;
    }

    /**
     * @param String $nombre
     */
    public function setNombre($nombre)
    {
        $this->nombre = $nombre;
    }

    /**
     * @param String $apellido
     */
    public function setApellido($apellido)
    {
        $this->apellido = $apellido;
    }

    /**
     * @param Genero $objGenero
     */
    public function setGenero($objGenero)
    {
        $this->genero = $objGenero;
    }

    /**
     * @param String $fechaNacimiento
     */
    public function setFechaNacimiento($fechaNacimiento)
    {
        $this->fechaNacimiento = $fechaNacimiento;
    }

    /**
     * @param int $activo
     */
    public function setActivo($activo)
    {
        $this->activo = $activo;
    }

    /**
     * @param Array $cursos
     */
    public function setCursos($cursos)
    {
        $this->cursos = $cursos;
    }

    /**
     * @param string $mensaje
     */
    public function setMensajeOperacion($mensaje)
    {
        $this->mensajeOperacion = $mensaje;
    }

    /**
     * solo necesitamos que el Participante tenga su id seteado para cargar todos los demas valores
     * @return boolean
     */
    public function cargar()
    {
        $resp = false;
        $base = new BaseDatos();
        $sql = "SELECT * FROM participante WHERE id = '" . $this->getId() . "';";
        if ($base->Iniciar()) {
            $res = $base->Ejecutar($sql);
            if ($res > -1) {
                if ($res > 0) {
                    $fila = $base->Registro();
                    $objGenero = new Genero();
                    $objGenero->setId($fila['genero']);
                    $objGenero->cargar();
                    $this->setear($fila['id'], $fila['dni'], $fila['nombre'], $fila['apellido'], $objGenero, $fila['fechanacimiento']);
                    $resp = true;
                }
            }
        } else {
            $this->setMensajeOperacion("Participante->listar: " . $base->getError());
        }
        return $resp;
    }

    /**
     * una vez que el Participante tenga sus valores seteados insertamos un nuevo Curso
     * con estos valores en la base de datos
     * @return boolean
     */
    public function insertar()
    {
        $resp = false;
        $base = new BaseDatos();
        $sql = "INSERT INTO participante (id ,dni, nombre, apellido, genero, fechanacimiento)  VALUES('bd-" . $this->getDni() . "', " . $this->getDni() . " , '" . $this->getNombre() . "' ,'" . $this->getApellido() . "' ," . $this->getGenero() . " , '" . $this->getFechaNacimiento() . "' );";
        if ($base->Iniciar()) {
            if ($base->Ejecutar($sql)) {
                //al ejecutar nos devuelve la cantidad de inserciones realizadas, nuestro id
                $resp = true;
            } else {
                $this->setMensajeOperacion("Participante->insertar: " . $base->getError());
            }
        } else {
            $this->setMensajeOperacion("Participante->insertar: " . $base->getError());
        }
        return $resp;
    }

    /**
     * si seteamos nuevos datos no nos alcanza utilizar un metodo set sobre Participante
     * sino que debemos reflejar los nuevos cambios sobre la base de datos
     * @return boolean
     */
    public function modificar()
    {
        $resp = false;
        if (strncmp($this->getId(), 'bd-', 3) === 0){
            $base = new BaseDatos();
            $sql = "UPDATE participante SET nombre='" . $this->getNombre() . "', apellido='" . $this->getApellido() . "', fechanacimiento='" . $this->getFechaNacimiento() . "' WHERE id=" . $this->getId() . ";";
            if ($base->Iniciar()) {
                if ($base->Ejecutar($sql)) {
                    $resp = true;
                } else {
                    $this->setMensajeOperacion("Participante->modificar: " . $base->getError());
                }
            } else {
                $this->setMensajeOperacion("Participante->modificar: " . $base->getError());
            }
        }
        return $resp;
    }

    /**
     * para borrar el Participante de manera permanente lo debemos hacer en la base de datos
     * entonces al estar seteada el id, nos basta para buscarlos y realizar un DELETE
     * @return boolean
     */
    public function eliminar()
    {
        $resp = false;
        $base = new BaseDatos();
        $sql = "DELETE FROM participante WHERE id='" . $this->getId() . "'";
        if ($base->Iniciar()) {
            if ($base->Ejecutar($sql)) {
                return true;
            } else {
                $this->setMensajeOperacion("Participante->eliminar: " . $base->getError());
            }
        } else {
            $this->setMensajeOperacion("Participante->eliminar: " . $base->getError());
        }
        return $resp;
    }

    /**
     * cargamos el objeto Participante si es que se encuentra en la BD o la api
     * @return boolean
     */
    public function cargarParticipanteConDni()
    {
        $resp = false;
        $base = new BaseDatos();
        $sql = "SELECT * FROM participante WHERE dni = '" . $this->getDni() . "'";
        if ($base->Iniciar()) {
            $res = $base->Ejecutar($sql);
            if ($res > -1) {
                if ($res > 0) {
                    $fila = $base->Registro();
                    $objGenero = new Genero();
                    $objGenero->setId($fila['genero']);
                    $objGenero->cargar();
                    $this->setear($fila['id'], $fila['dni'], $fila['nombre'], $fila['apellido'], $objGenero, $fila['fechanacimiento']);
                    $resp = true;
                } else {
                    $coleccionPersonas = (json_decode(file_get_contents("https://weblogin.muninqn.gov.ar/api/Examen")))->value;
                    $personaEncontrada = false;
                    $contador = 0;
                    while (!$personaEncontrada && $contador < 100) {
                        if ($coleccionPersonas[$contador]->dni == $this->getDni()) {
                            $participante = $coleccionPersonas[$contador];
                            $objGenero = new Genero();
                            $objGenero->setId($participante->genero->id);
                            $objGenero->cargar();
                            $apellidoNombre = explode(",", $participante->razonSocial); //[apellido,nombre]
                            $fechaNacimiento = (explode("T", $participante->fechaNacimiento))[0]; //solo guarda la fecha, omite el horario
                            $this->setear($participante->id, $participante->dni, $apellidoNombre[0], $apellidoNombre[1], $objGenero, $fechaNacimiento);
                            $personaEncontrada = true;
                            $resp = true;
                        }
                        $contador++;
                    }
                }
            }
        } else {
            $this->setMensajeOperacion("Participante->listar: " . $base->getError());
        }
        return $resp;
    }
}
