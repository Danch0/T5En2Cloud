<?php

// Definimos conexion de la base de datos.
// Lo haremos utilizando PDO con el driver mysql.
define('BD_SERVIDOR', 'localhost');
define('BD_NOMBRE', '2cloud');
define('BD_USUARIO', 'root');
define('BD_PASSWORD', 'T00R123');

// class MyDB
// {
// 	// const HOST = "201.144.121.132";
// 	// const USER = "czamudio";	
// 	// const PASS = "kiosco";
// 	// const DB = "registroSanLuis";
// 	private $mydb;
	
// 	public function Open($image = false){
// 		$this->mydb = new PDO('mysql:host=' . BD_SERVIDOR . ';dbname=' . BD_NOMBRE . ';charset=utf8', BD_USUARIO, BD_PASSWORD);
// 		$this->mydb->exec("set names utf8");
// 	}

// 	public function Query($sql){
//         logSW("entro al query");
// 		$query = $this->mydb->query($sql);
// 		// if ($resultado = mysqli_fetch_array($query, MYSQLI_ASSOC)) {
// 		//   die('Consulta no válida: ' . $this->mysqli->error);
// 		// }
//         $response = $query->fetch_array();
//         $query->free();
// 		return $response;
// 	}

// 	public function Close(){
// 		$this->mydb->close();
// 	}
// }

//$db = new PDO('mysql:host=' . BD_SERVIDOR . ';dbname=' . BD_NOMBRE . ';charset=utf8', BD_USUARIO, BD_PASSWORD);
//$db->exec("set names utf8");

/**
* 
*/
class MyDB extends PDO
{
	public $mydb;

	// public function __construct(){
	//   $dns = 'mysql'.':dbname='.BD_NOMBRE.";host=".BD_SERVIDOR; 
	//   parent::__construct( $dns, BD_USUARIO, BD_PASSWORD ); 
	// }
	public function __construct()
  {
  	//global $db;
		$this->mydb = new PDO('mysql:host=' . BD_SERVIDOR . ';dbname=' . BD_NOMBRE . ';charset=utf8', BD_USUARIO, BD_PASSWORD);
		$this->mydb->exec("set names utf8");
		$dns = 'mysql'.':dbname='.BD_NOMBRE.";host=".BD_SERVIDOR; 
	  parent::__construct( $dns, BD_USUARIO, BD_PASSWORD ); 
  }
  //Obtener registros de una tabla
  public function getAll($tableName, $limit = 0)
  {
    $limit = $limit != 0 ? string($limit) : "1000";
  	$consulta = $this->mydb->prepare("select * from ".$tableName." limit ".$limit);
		$consulta->execute();
    // Retornamos los resultados en un array asociativo.
    return $consulta->fetchAll(PDO::FETCH_ASSOC);
  }
  // Obtener registro especifico de tabla ?
  public function getRegistro($tableName, $awhere)
  {
    $where = "";
    foreach ($awhere as $key => $value) {
      $where .= $key."=".$value." "; 
    }
  	$consulta = $this->mydb->prepare("select * from ".$tableName." where ".$where);
    $consulta->execute();
    // Retornamos los resultados en un array asociativo.
    return $consulta->fetchAll(PDO::FETCH_ASSOC);
  }
  // Borrar registro especifico de tabla ?
  public function delete($tableName, $awhere)
  {
    $where = "";
    foreach ($awhere as $key => $value) {
      $where .= $key."=".$value." "; 
    }
    $consulta = $this->mydb->prepare("delete from ".$tableName." where ".$where);
    $consulta->execute();
    // Retornamos los resultados en un array asociativo.
    return $consulta->rowCount();
  }
}

$db = new myDB();

?>