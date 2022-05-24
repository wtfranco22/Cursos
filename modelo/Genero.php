<?php
class Genero
{
    private $id;
    private $textId;
    private $value;
    private $participantes;
    private $mensajeOperacion;

    public function __construct()
    {
        $this->id = 0;
        $this->textId = '';
        $this->value = "";
        $this->participantes = [];
        $this->mensajeOperacion = "";
    }

    /**
     * @param int $id
     * @param String $textId
     * @param String $value
     */
    public function setear($id, $textId, $value)
    {
        $this->id = $id;
        $this->textId = $textId;
        $this->value = $value;
    }

    //OBSERVADORES
    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return String
     */
    public function getTextId()
    {
        return $this->textId;
    }

    /**
     * @return String
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @return Array
     */
    public function getParticipantes(){
        return $this->participantes;
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
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @param String $textId
     */
    public function setTextId($textId)
    {
        $this->textId = $textId;
    }

    /**
     * @param String $value
     */
    public function setValue($value)
    {
        $this->value = $value;
    }

    /**
     * @param Array $participantes
     */
    public function setParticipantes($participantes)
    {
        $this->participantes = $participantes;
    }

    /**
     * @param String $mensaje
     */
    public function setMensajeOperacion($mensaje)
    {
        $this->mensajeOperacion = $mensaje;
    }

    /**
     * solo necesitamos que el Genero tenga su id seteado para cargar todos los demas valores
     * @return boolean
     */
    public function cargar()
    {
        $resp = false;
        $base = new BaseDatos();
        $sql = "SELECT * FROM genero WHERE id = " . $this->getId() . ";";
        if ($base->Iniciar()) {
            $res = $base->Ejecutar($sql);
            if ($res > -1) {
                if ($res > 0) {
                    $fila = $base->Registro();
                    $this->setear($fila['id'], $fila['textId'], $fila['value']);
                    $resp = true;
                }
            }
        } else {
            $this->setMensajeOperacion("Genero->listar: " . $base->getError());
        }
        return $resp;
    }

    /**
     * una vez que el Genero tenga sus valores seteados insertamos un nuevo Genero
     * con estos valores en la base de datos
     * @return boolean
     */
    public function insertar()
    {
        $resp = false;
        $base = new BaseDatos();
        $sql = "INSERT INTO genero (textId, "."value".")  VALUES('" . $this->getTextId() . "' , '" . $this->getValue() . "' );";
        if ($base->Iniciar()) {
            if ($idG = $base->Ejecutar($sql)) {
                //al ejecutar nos devuelve la cantidad de inserciones realizadas, nuestro id
                $this->setId($idG);
                $resp = true;
            } else {
                $this->setMensajeOperacion("Genero->insertar: " . $base->getError());
            }
        } else {
            $this->setMensajeOperacion("Genero->insertar: " . $base->getError());
        }
        return $resp;
    }

    /**
     * si seteamos nuevos datos no nos alcanza utilizar un metodo set sobre Genero
     * sino que debemos reflejar los nuevos cambios sobre la base de datos
     * @return boolean
     */
    public function modificar()
    {
        $resp = false;
        $base = new BaseDatos();
        $sql = "UPDATE genero SET textId='" . $this->getTextId() . "', value='" . $this->getValue() . "', WHERE id=" . $this->getId() . ";";
        if ($base->Iniciar()) {
            if ($base->Ejecutar($sql)) {
                $resp = true;
            } else {
                $this->setMensajeOperacion("Genero->modificar: " . $base->getError());
            }
        } else {
            $this->setMensajeOperacion("Genero->modificar: " . $base->getError());
        }
        return $resp;
    }

    /**
     * para borrar el Genero de manera permanente lo debemos hacer en la base de datos
     * entonces al estar seteada el id, nos basta para buscarlos y realizar un DELETE
     * @return boolean
     */
    public function eliminar()
    {
        $resp = false;
        $base = new BaseDatos();
        $sql = "DELETE FROM genero WHERE id=" . $this->getId();
        if ($base->Iniciar()) {
            if ($base->Ejecutar($sql)) {
                return true;
            } else {
                $this->setMensajeOperacion("Genero->eliminar: " . $base->getError());
            }
        } else {
            $this->setMensajeOperacion("Genero->eliminar: " . $base->getError());
        }
        return $resp;
    }
}
