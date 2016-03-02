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
      $where .= $key."='".$value."' "; 
    }
    // var_dump("select * from ".$tableName." where ".$where);
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
  public function login($tableName, $awhere)
  {
    // $where = "";
    // foreach ($awhere as $key => $value) {
    //   $where .= $key."=".$value." AND";
    // }
    // $where = substr("where", 0, -1);
    // $consulta = $this->mydb->prepare("delete from ".$tableName." where ".$where);
    // $consulta->execute();
    // // Retornamos los resultados en un array asociativo.
    // return $consulta->rowCount();
  }
  public function post($tableName,$data)
  {
    $keys= "";$values= "";
    $params = array();
    $cont = 0;

    foreach ($data as $key => $value) {
      $keys .= $key.",";
      $params[$cont] = $value;
      $values .= "?,";
      $cont += 1;
    }
    $keys = substr($keys, 0, -1);
    $values = substr($values, 0, -1);

    $consulta = $this->mydb->prepare("insert into ".$tableName."(".$keys.") 
          values (".$values.")");
    //mandamos el insert con los parametros recibidos
    return $consulta->execute($params) == true ? $this->mydb->lastInsertId() : 0;
  }
  public function put($tableName,$awhere,$data)
  {
    $keys= "";
    $params = array();
    $cont = 0;
    $where = "";

    foreach ($data as $key => $value) {
      $keys .= $key."=?,";
      $params[$cont] = $value;
      $cont += 1;
    }
    $keys = substr($keys, 0, -1);

    foreach ($awhere as $key => $value) {
      $where .= $key."=".$value." "; 
    }
    $query = "update ".$tableName." set ".$keys." where ".$where;
    var_dump($query);
    $consulta = $this->mydb->prepare("update ".$tableName." set ".$keys." where ".$where);
    //mandamos el insert con los parametros recibidos
    $consulta->execute($params);
    return $consulta->rowCount();
  }
}

$db = new myDB();

?>