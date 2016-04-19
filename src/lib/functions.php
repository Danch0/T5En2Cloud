<?php
// Extendemos los metodos de MyController
class Functions extends MyController {

  public function __construct($container)
  {
    /** 
    * Generamos el esqueleto y mandamos campos requeridos
    * MyPDO(container: objeto contenedor de slim,"nombre de la tabla para PUT, DELETE, POST", "nombre de la tabla para GET", Obtenemos el objeto PDO del contenedor de slim para db)
    * este objeto lo asignamo a la variable $db de la clase Ficha
    */
    parent::__construct($container,"usuario_ma","vw_usuario_det","id_usuario");
  }
	public function myfunction($args = array(), $data = null) {
    $method = isset($data['method'])? $data['method']:"";

    if($method != ""){ 
      switch ($method) {
        case 'validaFicha':
          return $this->db->validaFicha(isset($data['ficha'])? $data['ficha']:"");
          break;
        case 'reporte':
          return $this->db->reporte();
          break;
        default:
          array('estado'=>false,'mensaje'=>"Sin metodo especificado");
          break;
      }
    }else
      return array('estado'=>false,'mensaje'=>"Sin metodo especificado");


    // $response = $this->db->login($data);
    // if ($response['estado']) {
    //   $usuario = $response['result']['usuario'];
    //   unset($usuario['id_usuario']);
    //   unset($usuario['id_doctor_doctor_ma']);
    //   unset($usuario['password_txt']);
    //   unset($usuario['fecha_alta_dt']);
    //   unset($usuario['ultimo_login_dt']);
    //   unset($usuario['deleted_bool']);
    //   return array('estado'=>true, 'mensaje'=>'ok', 'result' => array('estado'=>true, 'mensaje'=>'ok', 'id_cliente' => $response['result']['id_cliente'], 
    //     'token' => $response['result']['token'], 'usuario'=>$usuario));
    // }else
    //   return array('estado'=>false,'mensaje'=>$response['mensaje']);
	}
}

?>