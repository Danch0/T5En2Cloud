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
    // Preparamos la consulta a la tabla.
    $consulta = $this->mydb->prepare("select * from doctor_ma");
    $consulta->execute();
    // Retornamos los resultados en un array asociativo.
    return $consulta->fetchAll(PDO::FETCH_ASSOC);
	}

	// public function get()
	// {
 //    // Preparamos la consulta a la tabla.
 //    $consulta = $this->mydb->prepare("select * from doctor_ma");
 //    $consulta->execute();
 //    // Retornamos los resultados en un array asociativo.
 //    echo json_encode($consulta->fetchAll(PDO::FETCH_ASSOC));
	// }

	public function getDoc($id)
	{
		$consulta = $this->mydb->prepare("select * from doctor_ma where id_doctor=:param1");
		// En el execute es dónde asociamos el :param1 con el valor que le toque.
		$consulta->execute(array(':param1' => $id));
    // Retornamos los resultados en un array asociativo.
		return $consulta->fetchAll(PDO::FETCH_ASSOC);
	}

	public function delete($id)
	{
		$consulta = $this->mydb->prepare("delete from doctor_ma where id_doctor=:id");
		$consulta->execute(array(':id'=>$id));
	  if ($consulta->rowCount() == 1)
			return json_encode(array('estado'=>true,'mensaje'=>'El doctor con id:'.$id.' ha sido borrado correctamente.'));
		else
			return json_encode(array('estado'=>false,'mensaje'=>'ERROR: ese registro no se ha encontrado en la tabla.'));
	}

	public function post($req)
	{
		// $fecha = date("Y-m-d H:i:s");
		$data = $req->getParsedBody();
		// $keys= "";$values= "";
		// $params = array();
		// $cont = 0;

		if (is_null($data)) {
			return json_encode(array('estado'=>false,'mensaje'=>'Los datos recibidos no corresponden a formato json'));
		};
		// Preparamos el array con los datos recivbidos.
  	// $params = array(
			//     		$data['nombre_txt'],$data['apellido_pat_txt'],$data['apellido_mat_txt'],$data['domicilio_1_txt'],
			//     		$data['domicilio_2_txt'],$data['colonia_txt'],$data['ciudad_txt'],$data['estado_txt'],
			//     		$data['codigo_postal_txt'],$data['tel_casa_txt'],$data['tel_mov_txt'],$data['email_txt'],
			//     		$data['id_especialidad_especialidad_cat'],$fecha
			//       );
    // foreach ($data as $key => $value) {
    //   $keys .= $key.",";
    //   $params[$cont] = $value;
    //   $values .= "?,";
    //   $cont += 1;
    // }
    // $keys = substr($keys, 0, -1);
    // $values = substr($values, 0, -1);

		// $consulta = $this->mydb->prepare("insert into doctor_ma(nombre_txt,apellido_pat_txt,apellido_mat_txt,domicilio_1_txt,domicilio_2_txt,colonia_txt,ciudad_txt,estado_txt,codigo_postal_txt,tel_casa_txt,tel_mov_txt,email_txt,id_especialidad_especialidad_cat,fecha_alta_dt) 
		// 			values (?,?,?,?,?,?,?,?,?,?,?,?,?,?)");
		// $query = "insert into doctor_ma(".$keys.") values (".$values.")";
		// var_dump($query);
		// $consulta = $this->mydb->prepare("insert into doctor_ma(".$keys.") 
		// 			values (".$values.")");
		// //mandamos el insert con los parametros recibidos
  //   $estado = $consulta->execute($params) == true ? $this->mydb->lastInsertId() : 0;
		$estado = $this->mydb->post('doctor_ma', $data);
	  if ($estado != 0)
 	    return json_encode(array('estado'=>true,'mensaje'=>'Datos insertados correctamente.','newId'=>$estado));
 		else
			return json_encode(array('estado'=>false,'mensaje'=>'Error al insertar datos en la tabla.'));
	}

	public function put($req, $id)
	{
		$data = $req->getParsedBody();
		var_dump($data);
		if (is_null($data))
			return json_encode(array('estado'=>false,'mensaje'=>'Los datos recibidos no corresponden a formato json'));

	 //  $params = array(
		// 		    		$data['nombre_txt'],$data['apellido_pat_txt'],$data['apellido_mat_txt'],$data['domicilio_1_txt'],
		// 		    		$data['domicilio_2_txt'],$data['colonia_txt'],$data['ciudad_txt'],$data['estado_txt'],
		// 		    		$data['codigo_postal_txt'],$data['tel_casa_txt'],$data['tel_mov_txt'],$data['email_txt'],
		// 		    		$data['id_especialidad_especialidad_cat'],$data['fecha_alta_dt'],$id
		// 		      );
		// $consulta = $this->mydb->prepare("update doctor_ma set nombre_txt=?,apellido_pat_txt=?,apellido_mat_txt=?,domicilio_1_txt=?,domicilio_2_txt=?,colonia_txt=?,
		// 	ciudad_txt=?,estado_txt=?,codigo_postal_txt=?,tel_casa_txt=?,tel_mov_txt=?,email_txt=?,id_especialidad_especialidad_cat=?,fecha_alta_dt=? where id_doctor=?");
		// //mandamos el query de UPDATE con los parametros recibidos
  //   $consulta->execute($params);
		// $estado = $consulta->rowCount();
		$estado = $this->mydb->put('doctor_ma',array('id_doctor'=>$id), $data);
	  if ($estado != 0)
	     return json_encode(array('estado'=>true,'mensaje'=>'Datos actualizados correctamente.'));
	  else
	     return json_encode(array('estado'=>false,'mensaje'=>'Error al actualizar datos en la tabla.'));
	}

}

?>