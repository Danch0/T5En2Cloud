<?php

class Ficha{
	protected $mydb;

	public function __construct() {
  	global $db;
		$this->mydb = $db;
  }

	public function get() {
		//Manadamos llamar la funcion getAll('NombreTabla') de la clase myDB()
    // return $this->mydb->getAll('ficha_ma');
    return $this->mydb->getAll('ficha_ma');
	}

	public function getRegistro($id) {
		//Manadamos llamar la funcion getRegistro('NombreTabla', array('id_tabla' => $id)) de la clase myDB()
    return $this->mydb->getRegistro('ficha_ma',array('id_ficha' => $id));
	}

	public function delete($id) {
		$consulta = $this->mydb->delete('ficha_ma',array('id_ficha' => $id));
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

    $estado = $this->mydb->post('ficha_ma', $data);
	  if ($estado != 0)
 	    return json_encode(array('estado'=>true,'mensaje'=>'Datos insertados correctamente.','newId'=>$estado));
 		else
			return json_encode(array('estado'=>false,'mensaje'=>'Error al insertar datos en la tabla.'));
	}

	public function put($req, $id) {
		$data = $req->getParsedBody();
		if (is_null($data))
			return json_encode(array('estado'=>false,'mensaje'=>'Los datos recibidos no corresponden a formato json'));
	  
		$estado = $this->mydb->put('ficha_ma',array('id_ficha'=>$id), $data);
	  if ($estado != 0)
	     return json_encode(array('estado'=>true,'mensaje'=>'Datos actualizados correctamente.'));
	  else
	     return json_encode(array('estado'=>false,'mensaje'=>'Error al insertar datos en la tabla.'));
	}

}

?>