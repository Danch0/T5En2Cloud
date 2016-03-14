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
  //Obtener usuarios
  public function getUsuarios($limit = 0)
  {
    $limit = $limit != 0 ? string($limit) : "1000";
    // echo $limit;
    $consulta = $this->mydb->prepare("(select * from usuario_ma where isnull(usuario_ma.id_doctor_doctor_ma)) union (select u.id_usuario, u.id_doctor_doctor_ma, u.id_rol_rol_cat, d.nombre_txt, d.apellido_pat_txt, d.apellido_mat_txt, u.username_txt, u.password_txt, u.email_txt, u.fecha_alta_dt, u.ultimo_login_dt, u.activo_bol from usuario_ma as u join doctor_ma as d on u.id_doctor_doctor_ma = d.id_doctor) limit ".$limit);
    $consulta->execute();
    // Retornamos los resultados en un array asociativo.
    return $consulta->fetchAll(PDO::FETCH_ASSOC);
  }
  //Obtener usuarios
  public function getUsuario($id)
  {
    $query = "(select * from usuario_ma as u1 where isnull(u1.id_doctor_doctor_ma) and u1.id_usuario = ".$id.") union (select u.id_usuario, u.id_doctor_doctor_ma, u.id_rol_rol_cat, d.nombre_txt, d.apellido_pat_txt, d.apellido_mat_txt, u.username_txt, u.password_txt, u.email_txt, u.fecha_alta_dt, u.ultimo_login_dt, u.activo_bol from usuario_ma as u join doctor_ma as d on u.id_doctor_doctor_ma = d.id_doctor where id_usuario = ".$id.")";
    // echo $query;
    $consulta = $this->mydb->prepare($query);
    $consulta->execute();
    // Retornamos los resultados en un array asociativo.
    return $consulta->fetchAll(PDO::FETCH_ASSOC);
  }
  //Obtener registros de una tabla
  public function getAll($tableName, $limit = 0)
  {
    $limit = $limit != 0 ? string($limit) : "1000";
  	$consulta = $this->mydb->prepare("select * from ".$tableName." limit ".$limit);
		$consulta->execute();
    // Retornamos los resultados en un array asociativo.
    $result = ($consulta->fetchAll(PDO::FETCH_ASSOC));
    // var_dump($result);
    // $json = '{"a":"Hola","b":"Mundo","c":3,"d":4,"e":5}';
    // $json = '';
    try {
      foreach ($result as $key => $value) {
        foreach ($value as $key2 => $value2) {
          if(is_array(json_decode($value2,true))){
            $result[$key][$key2] = json_decode($value2,true);
          }
        }
      }
      return $result;
        // return(json_decode($json));
    } catch (Exception $e) {
        return json_encode(array('estado'=>false,'mensaje'=>$e->getMessage()));
    }
    // var_dump(json_decode($json, true));
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

    // $data['id_endodoncia_endodoncia_ma'] = 1;
    try {
      foreach ($data as $key => $value) {
        $keys .= $key.",";
        $param = '{"';
        if (strpos($key, 'json') !== false) {
          if (is_array($value)) {
            foreach ($value as $key2 => $value2) {
              $param .= $value2.'": 1,"';
            }
            $param = substr($param, 0, -2);
            $param .= '}';
          }else {
            $param .= $value.'": 1}';
          }
        }else {
          $param = $value;
        }
        $data[$key] = $param;
        $params[$cont] = $param;


        $values .= "?,";
        $cont += 1;

      }
      // var_dump($data);
      $keys = substr($keys, 0, -1);
      $values = substr($values, 0, -1);

      $query = "insert into ".$tableName."(".$keys.") values (".$values.")";
      $consulta = $this->mydb->prepare($query);
      //mandamos el insert con los parametros recibidos
      if($consulta->execute($params) == true) {
        $newId = $this->mydb->lastInsertId();
        if ($tableName == "paciente_ma") {
          $newIdExp = $this->getRegistro("expediente_ma",array('id_paciente_paciente_ma' => $newId));
          return array('newId' => $newId, 'newIdExp' => $newIdExp[0]['id_expediente']);
        }
        return $newId;
      }else
        return 0;
    } catch (Exception $e) {
        return json_encode(array('estado'=>false,'mensaje'=>$e->getMessage()));
    }

    // foreach ($data as $key => $value) {
    //   $keys .= $key.",";
    //   $params[$cont] = $value;
    //   $values .= "?,";
    //   $cont += 1;
    // }
    // $keys = substr($keys, 0, -1);
    // $values = substr($values, 0, -1);

    // $consulta = $this->mydb->prepare("insert into ".$tableName."(".$keys.") 
    //       values (".$values.")");
    // //mandamos el insert con los parametros recibidos
    // return $consulta->execute($params) == true ? $this->mydb->lastInsertId() : 0;
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
    // var_dump($query);
    // var_dump($params);
    $consulta = $this->mydb->prepare($query);
    //mandamos el insert con los parametros recibidos
    $consulta->execute($params);
    return $consulta->rowCount();
  }
}

$db = new myDB();

?>