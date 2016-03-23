<?php
// Extendemos los metodos de MyController
class Doctor extends MyController {

  public function __construct($container)
  {
    /** 
    * Generamos el esqueleto y mandamos campos requeridos
    * MyPDO(container: objeto contenedor de slim,"nombre de la tabla para PUT, DELETE, POST", "nombre de la tabla para GET", Obtenemos el objeto PDO del contenedor de slim para db)
    * este objeto lo asignamo a la variable $db de la clase Doctor
    */
    parent::__construct($container,"doctor_ma","doctor_ma","id_doctor");
  }
}
?>