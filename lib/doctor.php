<?php
// require 'myConnection.php';
// $db = new myDB();

class Doctor{
	protected $mydb;

	public function __construct()
  {
  	global $db;
		$this->mydb = $db;
  }

	public function get()
	{
    return $this->mydb->getAll('doctor_ma');
	}

	public function getDoc($id)
	{
		$consulta = $this->mydb->prepare("select * from doctor_ma where id_doctor=:param1");
		// En el execute es dónde asociamos el :param1 con el valor que le toque.
		$consulta->execute(array(':param1' => $id));
    // Retornamos los resultados en un array asociativo.
		return $consulta->fetchAll(PDO::FETCH_ASSOC);
	}

	public function delete($id) {
		$consulta = $this->mydb->delete('doctor_ma',array('id_doctor' => $id));
	  if ($consulta == 1)
			return json_encode(array('estado'=>true,'mensaje'=>'El registro con id:'.$id.' ha sido borrado correctamente.'));
		else
			return json_encode(array('estado'=>false,'mensaje'=>'ERROR: registro no se ha encontrado en la tabla.'));
	}

	public function post($req)
	{
		$data = $req->getParsedBody();

		if (is_null($data)) {
			return json_encode(array('estado'=>false,'mensaje'=>'Los datos recibidos no corresponden a formato json'));
		};
		
		$estado = $this->mydb->post('doctor_ma', $data);
	  if ($estado != 0)
 	    return json_encode(array('estado'=>true,'mensaje'=>'Datos insertados correctamente.','newId'=>$estado));
 		else
			return json_encode(array('estado'=>false,'mensaje'=>'Error al insertar datos en la tabla.'));
	}

	public function put($req, $id)
	{
		$data = $req->getParsedBody();
		// var_dump($data);
		if (is_null($data))
			return json_encode(array('estado'=>false,'mensaje'=>'Los datos recibidos no corresponden a formato json'));

		// return $this->mydb->put('doctor_ma',array('id_doctor'=>$id), $data);
		$estado = $this->mydb->put('doctor_ma',array('id_doctor'=>$id), $data);
	  if ($estado != 0)
	     return json_encode(array('estado'=>true,'mensaje'=>'Datos actualizados correctamente.'));
	  else
	     return json_encode(array('estado'=>false,'mensaje'=>'Error al actualizar datos en la tabla.'));
	}

}

?>