<?php

// Definimos conexion de la base de datos.
// Lo haremos utilizando PDO con el driver mysql.
define('BD_SERVIDOR', 'localhost');
define('BD_NOMBRE', '2cloud');
define('BD_USUARIO', 'root');
define('BD_PASSWORD', 'T00R123');
/**
* 
*/
class MyDB extends PDO
{
	private $mydb;
  private $deletLogic;

	public function __construct()
  {
  	$this->deletLogic = array('doctor_ma' => true, 'paciente_ma' => true, 'ficha_ma' => true );
    
		$this->mydb = new PDO('mysql:host=' . BD_SERVIDOR . ';dbname=' . BD_NOMBRE . ';charset=utf8', BD_USUARIO, BD_PASSWORD);
		$this->mydb->exec("set names utf8");
		$dns = 'mysql'.':dbname='.BD_NOMBRE.";host=".BD_SERVIDOR; 
	  parent::__construct( $dns, BD_USUARIO, BD_PASSWORD ); 
  }
  //conprueba si la tabla es de borrado logico isdeletLogic('tableName')
  private function isdeletLogic($tableName = '') {
    $consulta = $this->mydb->prepare("select * from ".$tableName." limit 1");
    $consulta->execute();
    $result = ($consulta->fetchAll(PDO::FETCH_ASSOC));
    // var_dump($result[0]);
    if (count($result) != 1)
      return false;
    else 
      return array_key_exists("deleted_bool", $result[0]);
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
    try {
      $limit = $limit != 0 ? string($limit) : "1000";
      if ($this->isdeletLogic($tableName)) {
        $consulta = $this->mydb->prepare("select * from ".$tableName." where deleted_bool <> 1 OR isnull(deleted_bool) limit ".$limit);
      }else
        $consulta = $this->mydb->prepare("select * from ".$tableName." limit ".$limit);
  		$consulta->execute();
      // Retornamos los resultados en un array asociativo.
      $result = ($consulta->fetchAll(PDO::FETCH_ASSOC));
      return $this->parseJsonToArray($result);
    } catch (PDOException $e) {
      return array('estado'=>false,'mensaje'=>$e->getMessage());
    }
  }
  //Convierte en array los campo json obtenidos de la BD
  private function parseJsonToArray($data='')
  {
    $dataParse = $data;
    try {
      foreach ($data as $key => $value) {
        foreach ($value as $key2 => $value2) {
          if(is_array(json_decode($value2,true))){
            $dataParse[$key][$key2] = json_decode($value2,true);
          }
        }
      }
      return $dataParse;
    } catch (Exception $e) {
        return array('estado'=>false,'mensaje'=>$e->getMessage());
    }
  } 
  // Obtener registro especifico de tabla ?
  public function getRegistro($tableName, $awhere)
  {
    try {
      $where = "";
      foreach ($awhere as $key => $value) {
        $where .= $key."='".$value."' "; 
      }
      // var_dump("select * from ".$tableName." where ".$where);
    	$consulta = $this->mydb->prepare("select * from ".$tableName." where ".$where);
      $consulta->execute();
      // Retornamos los resultados en un array asociativo.
      $result = $consulta->fetchAll(PDO::FETCH_ASSOC);
      return $this->parseJsonToArray($result);
    } catch (PDOException $e) {
      return array('estado'=>false,'mensaje'=>$e->getMessage());
    }
  }
  // Ejecutamos query
  public function getQuery($query)
  {
    try {
      $consulta = $this->mydb->prepare($query);
      $consulta->execute();
      $result = $consulta->fetchAll(PDO::FETCH_ASSOC);
      return $this->parseJsonToArray($result);
    } catch (PDOException $e) {
      return array('estado'=>false,'mensaje'=>$e->getMessage());
    }
  }
  // Borrar registro especifico de tabla ?
  public function delete($tableName, $awhere)
  {
    try {
      // return array('estado'=>true,'mensaje'=>$this->isdeletLogic($tableName));
      $where = "";
      foreach ($awhere as $key => $value) {
        $where .= $key."=".$value." "; 
      }
      if ($this->isdeletLogic($tableName)) {
        $consulta = $this->mydb->prepare("update ".$tableName." set deleted_bool=1 where ".$where);
      }else
        $consulta = $this->mydb->prepare("delete from ".$tableName." where ".$where);
      $consulta->execute();
      // Retornamos los resultados en un array asociativo.
      return $consulta->rowCount();
    } catch (PDOException $e) {
      return json_encode(array('estado'=>false,'mensaje'=>$e->getMessage()));
    }

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
    // $keys= "";$values= "";
    // $params = array();
    // $cont = 0;

    // // $data['id_endodoncia_endodoncia_ma'] = 1;
    // try {
    //   foreach ($data as $key => $value) {
    //     $keys .= $key.",";
    //     $param = '{"';
    //     if (strpos($key, 'json') !== false) {
    //       if (is_array($value)) {
    //         foreach ($value as $key2 => $value2) {
    //           $param .= $value2.'": 1,"';
    //         }
    //         $param = substr($param, 0, -2);
    //         $param .= '}';
    //       }else {
    //         $newArray = explode(",",$value);
    //         if (count($newArray !=0)) {
    //           foreach ($newArray as $key2 => $value2) {
    //             $param .= $value2.'": 1,"';
    //           }
    //           $param = substr($param, 0, -2);
    //           $param .= '}';
    //         }else
    //           $param .= $value.'": 1}';
    //       }
    //     }else {
    //       $param = $value;
    //     }
    //     // var_dump($param);
    //     // $data[$key] = $param;
    //     $params[$cont] = $param;


    //     $values .= "?,";
    //     $cont += 1;

    //   }
    //   // var_dump($params);
    //   $keys = substr($keys, 0, -1);
    //   $values = substr($values, 0, -1);
    try {
      $myParse = $this->parseArrayToJsonQuery($data, "POST");
      if ($myParse['estado']) {
        $query = "insert into ".$tableName."(".$myParse['keys'].") values (".$myParse['values'].")";
        $consulta = $this->mydb->prepare($query);
        //mandamos el insert con los parametros recibidos
        if($consulta->execute($myParse['params']) == true) {
          $newId = $this->mydb->lastInsertId();
          if ($tableName == "paciente_ma") {
            $newIdExp = $this->getRegistro("expediente_ma",array('id_paciente_paciente_ma' => $newId));
            return array('newId' => $newId, 'newIdExp' => $newIdExp[0]['id_expediente']);
          }
          return $newId;
        }else
          return 0;
      }else
        return json_encode(array('estado'=>false,'mensaje'=>$myParse['mensaje']));
    } catch (PDOException $e) {
        return json_encode(array('estado'=>false,'mensaje'=>$e->getMessage()));
    }
  }
  //Convierte en array los campo json obtenidos de la BD
  private function parseArrayToJsonQuery($data='', $method)
  {
    $keys= "";$values= "";
    $params = array();
    $cont = 0;

    // $data['id_endodoncia_endodoncia_ma'] = 1;
    try {
      foreach ($data as $key => $value) {
        $keys .= $method == "POST" ? $key.",":$key."=?,";
        $param = '{"';
        if (strpos($key, 'json') !== false) {
          if (is_array($value)) {
            foreach ($value as $key2 => $value2) {
              $param .= $value2.'": 1,"';
            }
            $param = substr($param, 0, -2);
            $param .= '}';
          }else {
            $newArray = explode(",",$value);
            if (count($newArray !=0)) {
              foreach ($newArray as $key2 => $value2) {
                $param .= $value2.'": 1,"';
              }
              $param = substr($param, 0, -2);
              $param .= '}';
            }else
              $param .= $value.'": 1}';
          }
        }else {
          $param = $value;
        }
        // var_dump($param);
        // $data[$key] = $param;
        $params[$cont] = $param;


        $values .= "?,";
        $cont += 1;

      }
      // var_dump($params);
      $keys = substr($keys, 0, -1);
      $values = substr($values, 0, -1);

      return array('params' => $params, 'keys' => $keys, 'values' => $values, 'estado'=>true,'mensaje'=>"ok");
    } catch (Exception $e) {
        return array('estado'=>false,'mensaje'=>$e->getMessage());
    }
  } 
  public function put($tableName,$awhere,$data)
  {
    // $keys= "";
    // $params = array();
    // $cont = 0;
    // $where = "";

    // foreach ($data as $key => $value) {
    //   $keys .= $key."=?,";
    //   $params[$cont] = $value;
    //   $cont += 1;
    // }
    // $keys = substr($keys, 0, -1);

    try {
      $myParse = $this->parseArrayToJsonQuery($data, "PUT");
      if ($myParse['estado']) {
        $where = "";
        foreach ($awhere as $key => $value) {
          $where .= $key."=".$value." "; 
        }
        $query = "update ".$tableName." set ".$myParse['keys']." where ".$where;
        // var_dump($query);
        // var_dump($params);
        $consulta = $this->mydb->prepare($query);
        //mandamos el insert con los parametros recibidos
        $consulta->execute($myParse['params']);
        return $consulta->rowCount();
        // if ($consulta->rowCount() != 0)
        //    return json_encode(array('estado'=>true,'mensaje'=>'Datos actualizados correctamente.'));
        // else
        //    return json_encode(array('estado'=>false,'mensaje'=>'Error al actualizar datos en la tabla.'));
      }else
        return json_encode(array('estado'=>false,'mensaje'=>$myParse['mensaje']));
    } catch (PDOException $e) {
        return json_encode(array('estado'=>false,'mensaje'=>$e->getMessage()));
    }

    
  }
}

$db = new myDB();

?>