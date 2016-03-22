<?php
class MyPDO
{
  protected $tableName = "";
  protected $tableId = "";
  protected $deleteLogic = false;
  protected $pdo = null;

  public function __construct($tableName,$tableId,$pdo) {
    $this->tableName = $tableName;
    $this->tableId = $tableId;
    $this->pdo = $pdo;
    self::isdeleteLogic();
  }
  
 //  function __construct() {}

	// public function tableName($tableName='')
 //  {
 //    $this->tableName = $tableName;
 //  }
 //  public function tableId($tableId='')
 //  {
 //    $this->tableId = $tableId;
 //  }
 //  public function pdo($pdo='')
 //  {
 //    $this->pdo = $pdo;
 //    $this->isdeleteLogic();
 //  }

  // public $tableName;
  // public $tableId;
  // public $deleteLogic;
  // public $pdo;

  // function __construct() {}

  //conprueba si la tabla es de borrado logico
  public function isdeleteLogic() {
    $consulta = $this->pdo->prepare("select * from ".$this->tableName." limit 1");
    $consulta->execute();
    $result = ($consulta->fetchAll(PDO::FETCH_ASSOC));
    // var_dump($result[0]);
    if (count($result) != 1)
      $this->deleteLogic = false;
    else 
      $this->deleteLogic = array_key_exists("deleted_bool", $result[0]);
  }
  //Obtener registros de una tabla
  public function getAll($limit = 0)
  {
    try {
      $limit = $limit != 0 ? string($limit) : "1000";
      if ($this->deleteLogic) {
        $consulta = $this->pdo->prepare("select * from ".$this->tableName." where deleted_bool <> 1 OR isnull(deleted_bool) limit ".$limit);
      }else
        $consulta = $this->pdo->prepare("select * from ".$this->tableName." limit ".$limit);
  		$consulta->execute();
      // Retornamos los resultados en un array asociativo.
      $result = ($consulta->fetchAll(PDO::FETCH_ASSOC));
      return array('estado'=>true,'mensaje'=>"ok", 'result'=> $this->parseJsonToArray($result));
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
    	$consulta = $this->pdo->prepare("select * from ".$tableName." where ".$where);
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
      $consulta = $this->pdo->prepare($query);
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
        $consulta = $this->pdo->prepare("update ".$tableName." set deleted_bool=1 where ".$where);
      }else
        $consulta = $this->pdo->prepare("delete from ".$tableName." where ".$where);
      $consulta->execute();
      // Retornamos los resultados en un array asociativo.
      return $consulta->rowCount();
    } catch (PDOException $e) {
      return json_encode(array('estado'=>false,'mensaje'=>$e->getMessage()));
    }

  }
  public function post($tableName,$data)
  {
    try {
      $myParse = $this->parseArrayToJsonQuery($data, "POST");
      if ($myParse['estado']) {
        $query = "insert into ".$tableName."(".$myParse['keys'].") values (".$myParse['values'].")";
        $consulta = $this->pdo->prepare($query);
        //mandamos el insert con los parametros recibidos
        if($consulta->execute($myParse['params']) == true) {
          $newId = $this->pdo->lastInsertId();
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
        $consulta = $this->pdo->prepare($query);
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

?>