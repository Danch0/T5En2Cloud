<?php
// Extendemos los metodos de MyController
class Usuario extends MyController {

  public function __construct($container)
  {
    /** 
    * Generamos el esqueleto y mandamos campos requeridos
    * MyPDO(container: objeto contenedor de slim,"nombre de la tabla para PUT, DELETE, POST", "nombre de la tabla para GET", Obtenemos el objeto PDO del contenedor de slim para db)
    * este objeto lo asignamo a la variable $db de la clase Ficha
    */
    parent::__construct($container,"usuario_ma","usuario_ma","id_usuario");
  }

  public function get($args = null)
  {
  	$limit = array_key_exists('limit', $args) ? string($args['limit']) : "1000";
  	$filter = array_key_exists('filter', $args)? $args['filter']:$this->tableId;
    // Comprobamos si no existe el parametro filter ni id obtenemos todos los registros
    if(!array_key_exists('filter', $args) && !array_key_exists('id', $args))
	    return $this->db->getQuery("(select * from usuario_ma where isnull(usuario_ma.id_doctor_doctor_ma)) union (select u.id_usuario, u.id_doctor_doctor_ma, u.id_rol_rol_cat, d.nombre_txt, d.apellido_pat_txt, d.apellido_mat_txt, u.username_txt, u.password_txt, u.email_txt, u.fecha_alta_dt, u.ultimo_login_dt, u.activo_bol from usuario_ma as u join doctor_ma as d on u.id_doctor_doctor_ma = d.id_doctor) limit ".$limit);
    else {
    	return $this->db->getQuery("(select * from usuario_ma as u1 where isnull(u1.id_doctor_doctor_ma) and u1.".$filter." = '".$args['id']."') union (select u.id_usuario, u.id_doctor_doctor_ma, u.id_rol_rol_cat, d.nombre_txt, d.apellido_pat_txt, d.apellido_mat_txt, u.username_txt, u.password_txt, u.email_txt, u.fecha_alta_dt, u.ultimo_login_dt, u.activo_bol from usuario_ma as u join doctor_ma as d on u.id_doctor_doctor_ma = d.id_doctor where ".$filter." = '".$args['id']."')");
    }
  }
}
?>