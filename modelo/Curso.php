<?php
class Curso
{
    private $legajo;
    private $nombre;
    private $descripcion;
    private $modalidad;
    private $participantes;
    private $activo;
    private $mensajeOperacion;

    public function __construct()
    {
        $this->legajo = "";
        $this->nombre = "";
        $this->descripcion = "";
        $this->modalidad = "";
        $this->participantes = [];
        $this->activo = 1;
        $this->mensajeOperacion = "";
    }

    /**
     * @param String $legajo
     * @param String $nombre
     * @param String $descripcion
     * @param String $modalidad
     */
    public function setear($legajo, $nombre, $descripcion, $modalidad)
    {
        $this->legajo = $legajo;
        $this->nombre = $nombre;
        $this->descripcion = $descripcion;
        $this->modalidad = $modalidad;
    }

    //OBSERVADORES
    /**
     * @return String
     */
    public function getLegajo()
    {
        return $this->legajo;
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
    public function getDescripcion()
    {
        return $this->descripcion;
    }

    /**
     * @return String
     */
    public function getModalidad()
    {
        return $this->modalidad;
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
    public function getParticipantes()
    {
        return $this->participantes;
    }

    //MODIFICADORES
    /**
     * @param String $legajo
     */
    public function setLegajo($legajo)
    {
        $this->legajo = $legajo;
    }

    /**
     * @param String $nombre
     */
    public function setNombre($nombre)
    {
        $this->nombre = $nombre;
    }

    /**
     * @param String $descripcion
     */
    public function setDescripcion($descripcion)
    {
        $this->descripcion = $descripcion;
    }

    /**
     * @param String $modalidad
     */
    public function setModalidad($modalidad)
    {
        $this->modalidad = $modalidad;
    }

    /**
     * @param string $mensaje
     */
    public function setMensajeOperacion($mensaje)
    {
        $this->mensajeOperacion = $mensaje;
    }

    /**
     * @param int $activo
     */
    public function setActivo($activo)
    {
        $this->activo = (($activo == 1) ? 1 : 0);
    }

    /**
     * @param Array $participantes
     */
    public function setParticipantes($participantes)
    {
        $this->participantes = $participantes;
    }

    /**
     * solo necesitamos que el Curso tenga su id seteado para cargar todos los demas valores
     * @return boolean
     */
    public function cargar()
    {
        $resp = false;
        $base = new BaseDatos();
        $sql = "SELECT * FROM curso WHERE legajo = '" . $this->getLegajo() . "'";
        if ($base->Iniciar()) {
            $res = $base->Ejecutar($sql);
            if ($res > -1) {
                if ($res > 0) {
                    $fila = $base->Registro();
                    $this->setear($fila['legajo'], $fila['nombre'], $fila['descripcion'], $fila['modalidad']);
                    $resp = true;
                }
            }
        } else {
            $this->setmensajeoperacion("Curso->listar: " . $base->getError());
        }
        return $resp;
    }

    /**
     * una vez que el Curso tenga sus valores seteados insertamos un nuevo Curso
     * con estos valores en la base de datos
     * @return boolean
     */
    public function insertar()
    {
        $resp = false;
        $base = new BaseDatos();
        $sql = "INSERT INTO curso (legajo,nombre,descripcion,modalidad)  VALUES('" . $this->getLegajo() . "','" . $this->getNombre() . "','" . $this->getDescripcion() . "','" . $this->getModalidad() . "');";
        if ($base->Iniciar()) {
            if ($base->Ejecutar($sql)) {
                $resp = true;
            } else {
                $this->setMensajeOperacion("Curso->insertar: " . $base->getError());
            }
        } else {
            $this->setMensajeOperacion("Curso->insertar: " . $base->getError());
        }
        return $resp;
    }

    /**
     * si seteamos nuevos datos no nos alcanza utilizar un metodo set sobre Curso
     * sino que debemos reflejar los nuevos cambios sobre la base de datos
     * @return boolean
     */
    public function modificar()
    {
        $resp = false;
        $base = new BaseDatos();
        $sql = "UPDATE curso SET nombre='" . $this->getNombre() . "', descripcion='" . $this->getDescripcion() . "', modalidad='" . $this->getModalidad() . "' WHERE legajo='" . $this->getLegajo() . "'";
        if ($base->Iniciar()) {
            if ($base->Ejecutar($sql)) {
                $resp = true;
            } else {
                $this->setMensajeOperacion("Curso->modificar: " . $base->getError());
            }
        } else {
            $this->setMensajeOperacion("Curso->modificar: " . $base->getError());
        }
        return $resp;
    }

    /**
     * para borrar el Curso de manera permanente lo debemos hacer en la base de datos
     * entonces al estar seteada el legajo, nos basta para buscarlos y realizar un DELETE
     * @return boolean
     */
    public function eliminar()
    {
        $resp = false;
        $base = new BaseDatos();
        $sql = "DELETE FROM curso WHERE legajo='" . $this->getLegajo() . "'";
        if ($base->Iniciar()) {
            if ($base->Ejecutar($sql)) {
                return true;
            } else {
                $this->setMensajeOperacion("Curso->eliminar: " . $base->getError());
            }
        } else {
            $this->setMensajeOperacion("Curso->eliminar: " . $base->getError());
        }
        return $resp;
    }

    /**
     * guardamos los Cursos en un arreglo para poder manipular sobre ellos,
     * tenemos el parametro para cualquier especificacion sobre la busqueda de los Cursos
     * @param string $parametro
     * @return array
     */
    public static function listar($parametro = "")
    {
        $arreglo = array();
        $base = new BaseDatos();
        $sql = "SELECT * FROM curso ";
        if ($parametro != "") {
            $sql .= 'WHERE ' . $parametro;
        }
        $res = $base->Ejecutar($sql);
        if ($res > -1) {
            if ($res > 0) {
                while ($fila = $base->Registro()) {
                    $curso = new Curso();
                    $curso->setear($fila['legajo'], $fila['nombre'], $fila['descripcion'], $fila['modalidad']);
                    $curso->cargarParticipantes();
                    array_push($arreglo, $curso);
                }
            }
        } else {
            Curso::setMensajeOperacion("Curso->listar: " . $base->getError());
        }
        return $arreglo;
    }

    /**
     * Como muchas personas estan un mismo Curso entonces en cada Curso podemos
     * tener una coleccion de las personas del mismo
     */
    public function cargarParticipantes()
    {
        $participantes = Cursa::listarParticipantes("legajo='" . $this->getLegajo() . "'");
        $this->setParticipantes($participantes);
    }
}
