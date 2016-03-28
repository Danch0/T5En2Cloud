<?php
// Extendemos los metodos de MyController
class Ficha extends MyController {

  public function __construct($container)
  {
    /** 
    * Generamos el esqueleto y mandamos campos requeridos
    * MyPDO(container: objeto contenedor de slim,"nombre de la tabla para PUT, DELETE, POST", "nombre de la tabla para GET", Obtenemos el objeto PDO del contenedor de slim para db)
    * este objeto lo asignamo a la variable $db de la clase Ficha
    */
    parent::__construct($container,"ficha_ma","vw_ficha_det","id_ficha");
  }

  // public function get($args = null)
  // {
  // 	$limit = array_key_exists('limit', $args) ? string($args['limit']) : "1000";
  // 	$filter = array_key_exists('filter', $args)? $args['filter']:$this->tableId;
  //   // Comprobamos si no existe el parametro filter ni id obtenemos todos los registros
  //   if(!array_key_exists('filter', $args) && !array_key_exists('id', $args))
	 //    return $this->db->getQuery("select ex.id_expediente, p.id_paciente, CONCAT(p.nombre_txt,' ',p.apellido_pat_txt,' ',
  //   		p.apellido_mat_txt) as nombre_completo, f.* from paciente_ma as p join expediente_ma as ex on p.id_paciente = ex.id_paciente_paciente_ma 
  //   		join ficha_ma as f on ex.id_expediente = f.id_expediente_expediente_ma limit ".$limit);
  //   else {
  //   	return $this->db->getQuery("select ex.id_expediente, p.id_paciente, CONCAT(p.nombre_txt,' ',p.apellido_pat_txt,' ',
  //   		p.apellido_mat_txt) as nombre_completo, f.* from paciente_ma as p join expediente_ma as ex on p.id_paciente = ex.id_paciente_paciente_ma 
  //   		join ficha_ma as f on ex.id_expediente = f.id_expediente_expediente_ma where ".$filter." = '".$args['id']."'");
  //   }
  // }
}
?>