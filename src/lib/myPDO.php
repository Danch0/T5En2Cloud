<?php
class MyPDO
{
  protected $tableName = "";
  protected $tableGet = "";
  protected $deleteLogic = false;
  protected $pdo = null;

  public function __construct($tableName,$tableGet,$pdo) {
    $this->tableName = $tableName;
    $this->tableGet = $tableGet;
    $this->pdo = $pdo;
    self::isdeleteLogic();
  }
  // HAce una busqueda de fichas filtrando por la primera letra del apellido paterno
  public function buscaFicha($value='')
  {
    try{
      $query = "select * from vw_ficha_det where apellido_pat_txt like CONCAT('".$value."', '%')" ;
      $consulta = $this->pdo->prepare($query);
      //mandamos el insert con los parametros recibidos
      $consulta->execute();
      // Retornamos los resultados en un array asociativo.
      $result = $consulta->fetchAll(PDO::FETCH_ASSOC);
      return array('estado'=>true,'mensaje'=>"ok", 'result'=> $result);
    } catch (PDOException $e) {
      return array('estado'=>false,'mensaje'=>$e->getMessage());
    } catch (Exception $e2){
      return array('estado'=>false,'mensaje'=>$e2->getMessage());
    }
  }
  // Reporte
  public function reporte($value='')
  {
    try{
      $query = "select id_usuario_alta_usuario_ma, count(*) as total from vw_endodoncia_det where deleted_bool = 0 group by id_usuario_alta_usuario_ma ";
      $consulta = $this->pdo->prepare($query);
      //mandamos el insert con los parametros recibidos
      $consulta->execute();
      // Retornamos los resultados en un array asociativo.
      $result = $consulta->fetchAll(PDO::FETCH_ASSOC);
      return array('estado'=>true,'mensaje'=>"ok", 'result'=> $result);
    } catch (PDOException $e) {
      return array('estado'=>false,'mensaje'=>$e->getMessage());
    } catch (Exception $e2){
      return array('estado'=>false,'mensaje'=>$e2->getMessage());
    }
  }
  // Valida No. ficha, devuelve el numero de fichas encontradoas con la ficha espeficiada
  public function validaFicha($value='')
  {
    try{
      $query = "select * from ficha_ma where ficha_txt like CONCAT('".$value."', '%')" ;
      $consulta = $this->pdo->prepare($query);
      //mandamos el insert con los parametros recibidos
      $consulta->execute();
      // Retornamos los resultados en un array asociativo.
      $result = $consulta->fetchAll(PDO::FETCH_ASSOC);
      return array('estado'=>true,'mensaje'=>"ok", 'result'=> count($result));
    } catch (PDOException $e) {
      return array('estado'=>false,'mensaje'=>$e->getMessage());
    } catch (Exception $e2){
      return array('estado'=>false,'mensaje'=>$e2->getMessage());
    }
  }
  // Desactivar token
  public function logout($value='')
  {
    try{
      $query = "update token_ma set activo_bool=? where id_cliente_txt = '".$value."' ";
      $consulta = $this->pdo->prepare($query);
      //mandamos el insert con los parametros recibidos
      $consulta->execute(array(0));
      if($consulta->rowCount() == 1)
        return array('estado'=>true,'mensaje'=>'Token desactivado, id_cliente:'.$value, 'result'=>array('estado'=>true,'mensaje'=>'OK'));
      else
        throw new Exception('Registro en la tabla no encontrado.');
    } catch (PDOException $e) {
      return array('estado'=>false,'mensaje'=>$e->getMessage());
    } catch (Exception $e2){
      return array('estado'=>false,'mensaje'=>$e2->getMessage());
    }
  }
  // Genera un string random
  private function generateRandomString($length = 25) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
  }
  // Validar usuario y contaseña, genera un id y token si es valido
  public function login($data='')
  {
    try{
      $registro = $this->getRegistro(array('username_txt' => $data['username_txt']));
      // var_dump($registro['result'][0]['activo_bol'] == 1);
      if ($registro['estado']) { $registro = $registro['result'];
        if(count($registro) == 1) { $registro = $registro[0];
          if($registro['activo_bol'] == 1){
            if (hash_equals($registro["password_txt"], crypt($data["password_txt"], ".TRU350LUT10N5}"))) {
              $this->put(array('id_usuario' => $registro['id_usuario'] ), array('ultimo_login_dt' => date("Y-m-d H:i:s") ) );
              $token = $this->newToken($registro['id_usuario'],$data['ip_address']);
              if ($token['estado']) {
                return array('estado'=>true, 'mensaje'=>'ok', 'result' => array('estado'=>true, 'mensaje'=>'ok', 'id_cliente' => $token['result']['id_cliente'], 
                  'token' => $token['result']['token'], 'usuario'=>$registro));
              }else
                return array('estado'=>false,'mensaje'=>$token['mensaje']);
            }
          }
        }
      }else
        return array('estado'=>false,'mensaje'=>$registro['mensaje']);
      return array('estado'=>false,'mensaje'=>'Usuario o password incorrecto.');
    } catch (PDOException $e) {
      return array('estado'=>false,'mensaje'=>$e->getMessage());
    } catch (Exception $e2){
      return array('estado'=>false,'mensaje'=>$e2->getMessage());
    }
  }
  // Validar tojen
  public function isTokenValid($myheaders = array())
  {
    try{
      $registro = $this->getRegistro(array('id_cliente_txt' => $myheaders['PHP_AUTH_USER'][0]));
      // var_dump($registro['estado']);
      if ($registro['estado']) { 
        $registro = $registro['result'];
        if(count($registro) >= 1) { 
          $registro = $registro[count($registro) -1];
          if($registro['activo_bool'] == 1){
            $date1 = new DateTime($registro['fecha_alta_dt']);
            $date2 = new DateTime(date("Y-m-d H:i:s"));
            $interval = $date1->diff($date2)->h;
            if($interval <= 8){
              if (hash_equals($registro["token_txt"], $myheaders['PHP_AUTH_PW'][0]) && $registro["ip_cliente_txt"] == $myheaders['ip_address']) 
                return array('estado'=>true, 'mensaje'=>'ok', 'result' => array('estado'=>true, 'mensaje'=>'ok'));
            }else
              throw new Exception('Token invalido');
          }
        }
      }else
        throw new Exception($registro['mensaje']);
      throw new Exception('Credenciales obligatorias.');
    } catch (PDOException $e) {
      return array('estado'=>false,'mensaje'=>$e->getMessage());
    } catch (Exception $e2){
      return array('estado'=>false,'mensaje'=>$e2->getMessage());
    }
  }
  // Registra token
  public function newToken($id_usuario = "", $ip_address = "")
  {
    try {
      $id_cliente = $this->generateRandomString();
      $token = crypt($this->generateRandomString(15), ".TRU350LUT10N5}");
      $newData = array($id_cliente,$id_usuario,$token,$ip_address,date("Y-m-d H:i:s"),1);
      $query = "insert into token_ma(id_cliente_txt,id_usuario_usuario_ma,token_txt,ip_cliente_txt,fecha_alta_dt,activo_bool) values (?,?,?,?,?,?)";
      $consulta = $this->pdo->prepare($query);
      //mandamos el insert con los parametros recibidos
      if($consulta->execute($newData) == true) {
        $newId = $this->pdo->lastInsertId();
        return array('estado'=>true,'mensaje'=>"Insert into token_ma newId:".$newId,
            'result'=> array('estado'=>true, 'mensaje'=>"Ok", 'id_cliente' => $id_cliente, 'token' => $token));
      }else
        return array('estado'=>false,'mensaje'=>"Error desconocido");
    } catch (PDOException $e) {
        return array('estado'=>false,'mensaje'=>$e->getMessage());
    }
  }
  //conprueba si la tabla es de borrado logico
  public function isdeleteLogic() {
    try{
      $consulta = $this->pdo->prepare("select * from ".$this->tableName." limit 1");
      $consulta->execute();
      $result = ($consulta->fetchAll(PDO::FETCH_ASSOC));
      if (count($result) != 1)
        $this->deleteLogic = false;
      else 
        $this->deleteLogic = array_key_exists("deleted_bool", $result[0]);
    } catch (PDOException $e) {
      return array('estado'=>false,'mensaje'=>$e->getMessage());
    }
  }
  //Obtener registros de una tabla
  public function getAll($limit = "")
  {
    try {
      $limit = $limit != "" ? $limit : "1000";
      if ($this->deleteLogic) {
        $consulta = $this->pdo->prepare("select * from ".$this->tableGet." where deleted_bool <> 1 OR isnull(deleted_bool) limit ".$limit);
      }else
        $consulta = $this->pdo->prepare("select * from ".$this->tableGet." limit ".$limit);
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
  public function getRegistro($awhere = array())
  {
    try {
      $where = "";
      foreach ($awhere as $key => $value) {
        $where .= $key."='".$value."' "; 
      }
      if ($this->deleteLogic) {
        $consulta = $this->pdo->prepare("select * from ".$this->tableGet." where (deleted_bool <> 1 OR isnull(deleted_bool)) and ".$where);
      }else
        $consulta = $this->pdo->prepare("select * from ".$this->tableGet." where ".$where);
      $consulta->execute();
      // Retornamos los resultados en un array asociativo.
      $result = $consulta->fetchAll(PDO::FETCH_ASSOC);
      return array('estado'=>true,'mensaje'=>"ok", 'result'=> $this->parseJsonToArray($result));
    } catch (PDOException $e) {
      return array('estado'=>false,'mensaje'=>$e->getMessage());
    } catch (Exception $e2){
      return array('estado'=>false,'mensaje'=>$e2->getMessage());
    }
  }
  // Ejecutamos query
  public function getQuery($query)
  {
    try {
      $consulta = $this->pdo->prepare($query);
      $consulta->execute();
      $result = $consulta->fetchAll(PDO::FETCH_ASSOC);
      return array('estado'=>true,'mensaje'=>"ok", 'result'=> $this->parseJsonToArray($result));
    } catch (PDOException $e) {
      return array('estado'=>false,'mensaje'=>$e->getMessage());
    }
  }
  // Borrar registro especifico de tabla ?
  public function delete($awhere)
  {
    try {
      // return array('estado'=>true,'mensaje'=>$this->isdeletLogic($tableName));
      $where = "";
      $fileDelete = null;
      foreach ($awhere as $key => $value) {
        $where .= $key."=".$value." ";
        $fileDelete = $value;
      }
      if ($this->deleteLogic) {
        $consulta = $this->pdo->prepare("update ".$this->tableName." set deleted_bool=1 where ".$where);
      }else
        $consulta = $this->pdo->prepare("delete from ".$this->tableName." where ".$where);
      $consulta->execute();
      // Retornamos los resultados en un array asociativo.
      if($consulta->rowCount() == 1)
        return array('estado'=>true,'mensaje'=>'Registro con id:'.$fileDelete.' borrado correctamente.', 'result'=>array('estado'=>true,'mensaje'=>'Registro con id:'.$fileDelete.' borrado correctamente.'));
      else
        return array('estado'=>false,'mensaje'=>'Registro en la tabla no encontrado.');
    } catch (PDOException $e) {
      return array('estado'=>false,'mensaje'=>$e->getMessage());
    }

  }
  public function post($data)
  {
    try {
      if(array_key_exists("password_txt", $data))
        $data['password_txt'] = crypt($data['password_txt'], ".TRU350LUT10N5}");
      $myParse = $this->parseArrayToJsonQuery($data, "POST");
      if ($myParse['estado']) {
        $query = "insert into ".$this->tableName."(".$myParse['keys'].") values (".$myParse['values'].")";
        $consulta = $this->pdo->prepare($query);
        //mandamos el insert con los parametros recibidos
        if($consulta->execute($myParse['params']) == true) {
          $newId = $this->pdo->lastInsertId();
          if ($this->tableName == "paciente_ma") {
            $result = $this->getRegistro(array('id_paciente' => $newId));
            $newIdExp = $result['result'][0]['id_expediente'];
            return array('estado'=>true,'mensaje'=>"Insert into ".$this->tableName.' newId:'.$newId.' newIdExp:'.$newIdExp,
              'result'=> array('estado'=>true, 'mensaje'=>"Registro insertado correctamente con id: ".$newId, 'newId' => $newId, 
                'newIdExp' => $newIdExp));
          }
          return array('estado'=>true,'mensaje'=>"Insert into ".$this->tableName.' newId:'.$newId,
              'result'=> array('estado'=>true, 'mensaje'=>"Registro insertado correctamente con id: ".$newId, 'newId' => $newId));
        }else
          return array('estado'=>false,'mensaje'=>"Error desconocido");
      }else
        return array('estado'=>false,'mensaje'=>$myParse['mensaje']);
    } catch (PDOException $e) {
        return array('estado'=>false,'mensaje'=>$e->getMessage());
    }
  }
  //Convierte en json los campos recibidos
  private function parseArrayToJsonQuery($data='', $method)
  {
    $keys= "";$values= "";
    $params = array();
    $cont = 0;
    $isKey = true;

    try {
      foreach ($data as $key => $value) {
        $keys .= $method == "POST" ? $key.",":$key."=?,";
        $value = filter_var($value, FILTER_SANITIZE_STRING);
        $param = '{"';
        if (strpos($key, 'json') !== false) {
          $explodeArray = explode(",",$value);
          $newArray = array();
          $oldKey = "";
          if($value==null || $value=="null")
            $param = null;
          else {
            foreach ($explodeArray as $key => $value) {
              if ($isKey) {
                $value = trim($value);
                $oldKey = $value;
                $newArray[$value] = "";
                $isKey = false;
              }else {
                $newArray[$oldKey] = $value;
                $isKey = true;
              }
            }
            $param = json_encode($newArray);
          }
        }else {
          if($value==null || $value=="null")
            $param = null;
          else
            $param = $value;
        }
        $params[$cont] = $param;

        $values .= "?,";
        $cont += 1;
      }
      $keys = substr($keys, 0, -1);
      $values = substr($values, 0, -1);

      return array('params' => $params, 'keys' => $keys, 'values' => $values, 'estado'=>true,'mensaje'=>"ok");
    } catch (Exception $e) {
        return array('estado'=>false,'mensaje'=>$e->getMessage());
    }
  } 
  public function put($awhere,$data)
  {
    try {
      if(array_key_exists("password_txt", $data))
      {
        $user = $this->getRegistro($awhere);
        if ($user['estado']) {
          $user = $user['result'];
          if (count($user) == 1) {
            $user = $user[0];
            $data['password_txt'] = hash_equals($user['password_txt'], $data['password_txt']) ? $data['password_txt']:crypt($data['password_txt'], ".TRU350LUT10N5}");
          }else
            throw new Exception("Ocurrio algun problema mientras se intentava actualizar, intentelo mas tarde.");
        }else
          throw new Exception($user['mensaje']);
      }
      $myParse = $this->parseArrayToJsonQuery($data, "PUT");
      if ($myParse['estado']) {
        $where = "";
        $fieldAfected = '';
        foreach ($awhere as $key => $value) {
          $where .= $key."=".$value." "; 
          $fieldAfected = $value;
        }
        $query = "update ".$this->tableName." set ".$myParse['keys']." where ".$where;
        $consulta = $this->pdo->prepare($query);
        //mandamos el insert con los parametros recibidos
        $consulta->execute($myParse['params']);
        if($consulta->rowCount() == 1)
          return array('estado'=>true,'mensaje'=>'Registro con id:'.$fieldAfected.' actualizado correctamente.', 'result'=>array('estado'=>true,'mensaje'=>'Registro con id:'.$fieldAfected.' actualizado correctamente.'));
        else
          return array('estado'=>false,'mensaje'=>'Registro en la tabla no encontrado.');
      }else
        return array('estado'=>false,'mensaje'=>$myParse['mensaje']);
    } catch (PDOException $e) {
        return array('estado'=>false,'mensaje'=>$e->getMessage());
    } catch (Exception $e2) {
        return array('estado'=>false,'mensaje'=>$e2->getMessage());
    }

    
  }
}

?>