<?php
class ControlCurso{
    /**
     * @param Array $datos, son todos los datos necesarios para crear un curso
     * @return Curso en caso de que se encuentren todos los datos sino retorna null
     */
    private function cargarObjeto($datos){
        $objetoCurso = null;
        if(array_key_exists('legajo',$datos) && array_key_exists('nombre',$datos) && array_key_exists('descripcion',$datos) && array_key_exists('modalidad',$datos)){
            $objCurso = new Curso();
            $objCurso->setear($datos['legajo'],$datos['nombre'],$datos['descripcion'],$datos['modalidad']);
        }
        return $objetoCurso;
    }

    /**
     * @param Array $datos, son todos los datos necesarios para cargar un Curso
     * @return Curso en caso de que se encuentren todos los datos sino retorna null
     */
    private function cargarObjetoConClave($datos)
    {
        $objetoParticipante = null;
        if (array_key_exists('legajo', $datos) ) {
            $objetoParticipante = new Curso();
            $objetoParticipante->setLegajo($datos['legajo']);
            $objetoParticipante->cargar();
        }
        return $objetoParticipante;
    }

    /**
     * @param Array $datos, son los datos ingresados por el formulario al crear un nuevo curso
     * @return boolean
     */
    public function alta($datos){
        $exito = false;
        $datosCurso['id'] = 1;
        $datosCurso['nombre'] = $datos['nombre'];
        $datosCurso['descripcion'] = $datos['descripcion'];
        $datosCurso['modalidad'] = $datos['modalidad'];
        $objetoCurso = $this->cargarObjeto($datosCurso);
        if($objetoCurso != null && $objetoCurso->insertar()){
            $exito = true;
        }
        return $exito;
    }

    /**
     * @param Array $datos, son todos los datos de un curso existente
     * @return boolean
     */
    public function baja($datos){
        $exito = false;
        $objetoCurso = $this->cargarObjetoConClave($datos);
        $objetoCurso->setActivo(0);
        if($objetoCurso != null && $objetoCurso->modificar()){
            $exito = true;
        }
        return $exito;
    }

    /**
     * @param Array $datos, son los nuevos a modificar un curso existente
     * @return boolean
     */
    public function modificar($datos){
        $exito = false;
        $objetoCurso = $this->cargarObjetoConClave($datos);
        if(isset($datos['nombre']) && $datos['nombre']!=null){
            $objetoCurso->setNombre($datos['nombre']);
        }
        if(isset($datos['descripcion']) && $datos['descripcion']!=null){
            $objetoCurso->setDescripcion($datos['descripcion']);
        }
        if(isset($datos['modalidad']) && $datos['modalidad']!=null){
            $objetoCurso->setModalidad($datos['modalidad']);
        }
        if($objetoCurso != null && $objetoCurso->modificar()){
            $exito = true;
        }
        return $exito;
    }

    public function buscarCursos($param){
        return Curso::listar("modalidad='".$param."'");
    }
}
