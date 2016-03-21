<?php

class EndodonciaDiente{
	protected $mydb;

	public function __construct() {
  	global $db;
		$this->mydb = $db;
  }

	public function get() {
		//Manadamos llamar la funcion getAll('NombreTabla') de la clase myDB()
    // return $this->mydb->getAll('endodoncia_diente_det');
    return $this->mydb->getAll('vw_endodoncia_det');
	}

	public function getRegistro($id = 0, $filter = "") {
		//Manadamos llamar la funcion getRegistro('NombreTabla', array('id_tabla' => $id)) de la clase myDB()
		$where = $filter != ""? $filter:'id';
    return $this->mydb->getRegistro('vw_endodoncia_det',array($where => $id));
	}

	public function delete($id) {
		$consulta = $this->mydb->delete('endodoncia_diente_det',array('id' => $id));
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

    $estado = $this->mydb->post('endodoncia_diente_det', $data);
	  if ($estado != 0)
 	    return json_encode(array('estado'=>true,'mensaje'=>'Datos insertados correctamente.','newId'=>$estado));
 		else
			return json_encode(array('estado'=>false,'mensaje'=>'Error al insertar datos en la tabla.'));
	}

	public function put($req, $id) {
		$data = $req->getParsedBody();
		if (is_null($data))
			return json_encode(array('estado'=>false,'mensaje'=>'Los datos recibidos no corresponden a formato json'));
	  
		$estado = $this->mydb->put('endodoncia_diente_det',array('id'=>$id), $data);
	  if ($estado != 0)
	     return json_encode(array('estado'=>true,'mensaje'=>'Datos actualizados correctamente.'));
	  else
	     return json_encode(array('estado'=>false,'mensaje'=>'Error al insertar datos en la tabla.'));
	}

}

?>