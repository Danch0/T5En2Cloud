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
    parent::__construct($container,"usuario_ma","vw_usuario_det","id_usuario");
  }
	public function post($data = null) {
    $registro = $this->db->getRegistro(array('username_txt' => $data['username_txt']));
    // var_dump($registro);activo_bol
    if($registro['estado']) {
      if(count($registro['result']) == 1){
        if (hash_equals($registro['result'][0]["password_txt"], crypt($data["password_txt"], ".TRU350LUT10N5}"))) {
          $usuario = $registro['result'][0];
          unset($usuario['id_usuario']);
          unset($usuario['id_doctor_doctor_ma']);
          unset($usuario['password_txt']);
          unset($usuario['fecha_alta_dt']);
          unset($usuario['ultimo_login_dt']);
          unset($usuario['deleted_bool']);
          return array('estado'=>true, 'mensaje'=>'ok', 'result' => array('estado'=>true, 'mensaje'=>'ok','usuario'=>$usuario));
        }
      }
      return array('estado'=>false,'mensaje'=>'Usuario o Contraseña incorrectos.');
    }else
      return array('estado'=>false,'mensaje'=>$registro['mensaje']);
	}

}

?>