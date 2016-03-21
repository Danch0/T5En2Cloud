<?php

class Usuario{
	protected $mydb;

	public function __construct() {
  	global $db;
		$this->mydb = $db;
  }

	public function get() {
		//Manadamos llamar la funcion getAll('NombreTabla') de la clase myDB()
    // return $this->mydb->getAll('usuario_ma');
    return $this->mydb->getUsuarios();
	}

	public function getRegistro($id) {
		//Manadamos llamar la funcion getRegistro('NombreTabla', array('id_tabla' => $id)) de la clase myDB()
    return $this->mydb->getUsuario($id);
	}

	public function delete($id) {
		$consulta = $this->mydb->delete('usuario_ma',array('id_usuario' => $id));
	  if ($consulta == 1)
			return json_encode(array('estado'=>true,'mensaje'=>'El registro con id:'.$id.' ha sido borrado correctamente.'));
		else
			return json_encode(array('estado'=>false,'mensaje'=>'ERROR: registro no se ha encontrado en la tabla.'));
	}

	public function post($req) {
		$data = $req->getParsedBody();
		if (is_null($data)) {
			return json_encode(array('estado'=>false,'mensaje'=>'Los datos recibidos no corresponden a formato valido'));
		};
		$data['password_txt'] = crypt($data['password_txt'], ".TRU350LUT10N5}");
		$data['activo_bol'] = TRUE;

    $estado = $this->mydb->post('usuario_ma', $data);
	  if ($estado != 0)
 	    return json_encode(array('estado'=>true,'mensaje'=>'Datos insertados correctamente.','newId'=>$estado));
 		else
			return json_encode(array('estado'=>false,'mensaje'=>'Error al insertar datos en la tabla.'));
	}

	public function put($req, $id) {
		$data = $req->getParsedBody();
		if (is_null($data))
			return json_encode(array('estado'=>false,'mensaje'=>'Los datos recibidos no corresponden a formato json'));
		
		$user = $this->getRegistro($id);
    $data['password_txt'] = hash_equals($user[0]['password_txt'], $data['password_txt']) ? $data['password_txt']:crypt($data['password_txt']);
		
		$estado = $this->mydb->put('usuario_ma',array('id_usuario'=>$id), $data);
	  if ($estado != 0)
	     return json_encode(array('estado'=>true,'mensaje'=>'Datos actualizados correctamente.'));
	  else
	     return json_encode(array('estado'=>false,'mensaje'=>'Error al insertar datos en la tabla.'));
	}

}

?>