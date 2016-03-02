<?php
//require 'myConnection.php';
//$db = new MyDB();

class Login{
	protected $mydb;

	public function __construct() {
  	global $db;
		$this->mydb = $db;
  }
	public function login($req) {
		$data = $req->getParsedBody();

		if (is_null($data)) {
			return json_encode(array('estado'=>false,'mensaje'=>'Los datos recibidos no corresponden a formato valido'));
		};
		// var_dump($data);
		//Manadamos llamar la funcion getAll('NombreTabla', array('name' => $name, 'title' => $title, 'status' => $status)) de la clase myDB()
		// $psw = crypt($data["password_txt"], ".TRU350LUT10N5}");
    $registro = $this->mydb->getRegistro('usuario_ma',array('username_txt' => $data['username_txt']));
    // var_dump($registro);
    if(count($registro) != 1)
    	return json_encode(array('estado'=>false,'mensaje'=>'ERROR: registro no se ha encontrado en la tabla.'));
    if (hash_equals($registro[0]["password_txt"], crypt($data["password_txt"], ".TRU350LUT10N5}")))
    	return json_encode(array('estado'=>true,'mensaje'=>'Usuario y password verificados'));
	}

}

?>