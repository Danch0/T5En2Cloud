<?php
//require 'myConnection.php';
//$db = new MyDB();

class Especialidad{
	protected $mydb;

	public function __construct() {
  	global $db;
		$this->mydb = $db;
  }

	public function get() {
		//Manadamos llamar la funcion getAll('NombreTabla') de la clase myDB()
    return $this->mydb->getAll('especialidad_cat');
	}

	public function getRegistro($id) {
		//Manadamos llamar la funcion getAll('NombreTabla', array('name' => $name, 'title' => $title, 'status' => $status)) de la clase myDB()
    return $this->mydb->getRegistro('especialidad_cat',array('id_especialidad' => $id));
	}

	public function delete($id) {
		$consulta = $this->mydb->delete('especialidad_cat',array('id_especialidad' => $id));
	  if ($consulta == 1)
			return json_encode(array('estado'=>true,'mensaje'=>'El registro con id:'.$id.' ha sido borrado correctamente.'));
		else
			return json_encode(array('estado'=>false,'mensaje'=>'ERROR: registro no se ha encontrado en la tabla.'));
	}

	public function post($req) {
		$fecha = date("Y-m-d H:i:s");
		$data = $req->getParsedBody();

		if (is_null($data)) {
			return json_encode(array('estado'=>false,'mensaje'=>'Los datos recibidos no corresponden a formato valido'));
		};
		// Preparamos el array con los datos recivbidos.
  	$params = array(
			    		$data['especialidad_txt']
			      );
		$consulta = $this->mydb->prepare("insert into especialidad_cat(especialidad_txt) 
					values (?)");
		//mandamos el insert con los parametros recibidos
    $estado = $consulta->execute($params) == true ? $this->mydb->lastInsertId() : 0;
	  if ($estado != 0)
 	    return json_encode(array('estado'=>true,'mensaje'=>'Datos insertados correctamente.','newId'=>$estado));
 		else
			return json_encode(array('estado'=>false,'mensaje'=>'Error al insertar datos en la tabla.'));
	}

	public function put($req, $id) {
		$data = $req->getParsedBody();
		if (is_null($data))
			return json_encode(array('estado'=>false,'mensaje'=>'Los datos recibidos no corresponden a formato json'));

	  $params = array(
				    		$data['especialidad_txt'],$id
				      );
		$consulta = $this->mydb->prepare("update especialidad_cat set especialidad_txt=? where id_especialidad=?");
		//mandamos el query de UPDATE con los parametros recibidos
    $consulta->execute($params);
	  if ($consulta->rowCount() != 0)
	     return json_encode(array('estado'=>true,'mensaje'=>'Datos actualizados correctamente.'));
	  else
	     return json_encode(array('estado'=>false,'mensaje'=>'Error al insertar datos en la tabla.'));
	}

}

?>