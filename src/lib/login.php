<?php
// Extendemos los metodos de MyController
class Login extends MyController {

  public function __construct($container)
  {
    /** 
    * Generamos el esqueleto y mandamos campos requeridos
    * MyPDO(container: objeto contenedor de slim,"nombre de la tabla para PUT, DELETE, POST", "nombre de la tabla para GET", Obtenemos el objeto PDO del contenedor de slim para db)
    * este objeto lo asignamo a la variable $db de la clase Ficha
    */
    parent::__construct($container,"usuario_ma","usuario_ma","id_usuario");
  }
	public function post($data = null) {
    $registro = $this->db->getRegistro(array('username_txt' => $data['username_txt']));
    // var_dump($registro);activo_bol
    if(count($registro['result']) != 1)
    	return array('estado'=>false,'mensaje'=>'ERROR: registro no se ha encontrado en la tabla.');
    if (hash_equals($registro['result'][0]["password_txt"], crypt($data["password_txt"], ".TRU350LUT10N5}")))
    	return array('estado'=>true, 'mensaje'=>'ok', 'result' => array('estado'=>true, 'mensaje'=>'ok','activo_bol'=>$registro['result'][0]["activo_bol"],'id_rol_rol_cat'=>$registro['result'][0]["id_rol_rol_cat"]));
	}

}

?>