<?php
class ControlParticipante
{
    /**
     * @param Array $datos, son todos los datos necesarios para inscribir un Participante
     * @return Participante en caso de que se encuentren todos los datos sino retorna null
     */
    private function cargarObjeto($datos)
    {
        $objetoParticipante = null;
        if (array_key_exists('id', $datos) && array_key_exists('dni', $datos) && array_key_exists('nombre', $datos) && array_key_exists('apellido', $datos) && array_key_exists('genero', $datos) && array_key_exists('fechanacimiento', $datos)) {
            $objetoGenero = new Genero();
            $objetoGenero->setId($datos['genero']);
            $objetoGenero->cargar();
            $objetoParticipante = new Participante();
            $objetoParticipante->setear($datos['id'], $datos['dni'], $datos['nombre'], $datos['apellido'], $objetoGenero, $datos['fechanacimiento']);
        }
        return $objetoParticipante;
    }

    /**
     * @param Array $datos, son todos los datos necesarios para cargar un Participante
     * @return Participante en caso de que se encuentren todos los datos sino retorna null
     */
    private function cargarObjetoConClave($datos)
    {
        $objetoParticipante = null;
        if (array_key_exists('dni', $datos)) {
            $objetoParticipante = new Participante();
            $objetoParticipante->setId("bd-".$datos['dni']);
            $objetoParticipante->cargar();
        }
        return $objetoParticipante;
    }

    /**
     * @param Array $datos, son los datos ingresados por el formulario
     * @return boolean
     */
    public function alta($datos)
    {
        $exito = false;
        $datosParticipante['id'] = "bd-".$datos['dni'];
        $datosParticipante['dni'] = $datos['dni'];
        $datosParticipante['nombre'] = $datos['nombre'];
        $datosParticipante['apellido'] = $datos['apellido'];
        $datosParticipante['genero'] = $datos['genero'];
        $datosParticipante['fechanacimiento'] = $datos['fechanacimiento'];
        $objetoParticipante = $this->cargarObjeto($datosParticipante);
        if ($objetoParticipante != null && $objetoParticipante->insertar()) {
            $objetoCurso = new Curso();
            $objetoCurso->setLegajo($datos['legajo']);
            $objetoCurso->cargar();
            $objetoCursa = new Cursa();
            $objetoCursa->setear($objetoParticipante, $objetoCurso);
            $exito = $objetoCursa->insertar();
        }
        return $exito;
    }

    /**
     * @param Array $datos, son todos los datos de un Participante existente
     * @return boolean
     */
    public function baja($datos)
    {
        $exito = false;
        $objetoParticipante = $this->cargarObjetoConClave($datos);
        $objetoParticipante->setActivo(0);
        if ($objetoParticipante != null && $objetoParticipante->modificar()) {
            $exito = true;
        }
        return $exito;
    }

    /**
     * son los datos ingresados por el formulario
     * verificamos si existe sino le damos el alta e inscribimos inmediatamente
     * si existe vemos cuantos cursos esta inscripto y que no se repita modalidad
     * @param Array $datos
     * @return String
     */
    public function registrar($datos)
    {
        $mensaje = "";
        $objetoParticipante = new Participante();
        $objetoParticipante->setDni($datos['dni']);
        if ($objetoParticipante->cargarParticipanteConDni()) {
            $cursosInscriptos = Cursa::listarCursos("idparticipante='" . $objetoParticipante->getId() . "'");
            switch (count($cursosInscriptos)) {
                case 0:
                    $cursoInscribir = new Curso();
                    $cursoInscribir->setLegajo($datos['legajo']);
                    $cursoInscribir->cargar();
                    $objetoCursa = new Cursa();
                    $objetoCursa->setear($objetoParticipante, $objetoCursa);
                    $mensaje = ($objetoCursa->insertar()) ? "Inscripto con exito" : "Error al insertar en BD";
                    break;
                case 1:
                    //si ya esta en un curso vemos la modalidad en la que se encuentra
                    $cursoInscribir = new Curso();
                    $cursoInscribir->setLegajo($datos['legajo']);
                    $cursoInscribir->cargar();
                    if ($cursoInscribir->getModalidad() == $cursosInscriptos[0]->getModalidad()) {
                        $mensaje = "Ya estas en un curso con la misma modalidad";
                    } else {
                        $objetoCursa = new Cursa();
                        $objetoCursa->setear($objetoParticipante, $cursoInscribir);
                        $mensaje = ($objetoCursa->insertar()) ? "Inscripto con exito" : "Error al insertar en BD";
                    }
                    break;
                default:
                    $mensaje = "Ya estas en 2 cursos inscriptos";
                    break;
            }
        } else {
            //no esta registrado, le damos el alta a un nuevo participante
            $mensaje = ($this->alta($datos)) ? "Inscripto con exito" : "Error al insertar en BD";
        }
        return $mensaje;
    }

    /**
     * @param Array $datos, son los nuevos a modificar un curso existente
     * @return boolean
     */
    public function modificar($datos)
    {
        $mensaje = "";
        if (strncmp($datos['id'], 'bd-', 3) === 0) {
            $objetoParticipante = $this->cargarObjetoConClave($datos);
            if (isset($datos['dni']) && $datos['dni'] != null) {
                $objetoParticipante->setDni($datos['dni']);
            }
            if (isset($datos['nombre']) && $datos['nombre'] != null) {
                $objetoParticipante->setNombre($datos['nombre']);
            }
            if (isset($datos['apellido']) && $datos['apellido'] != null) {
                $objetoParticipante->setApellido($datos['apellido']);
            }
            if ($objetoParticipante != null && $objetoParticipante->modificar()) {
                $mensaje = "Cambios registrados";
            }else{
                $mensaje = "Error en la consulta de la BD";
            }
        }
        return $mensaje;
    }
}
