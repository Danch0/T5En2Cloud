<?php
class Fichapdo extends MyPDO{

	public function __construct($pdo) {
		parent::__construct("ficha_ma","id_ficha",$pdo);
    // ***************
		// parent::tableName("ficha_ma");
  // 	parent::tableId("id_ficha");
  // 	parent::pdo($pdo);
  //   $this->tableName = "ficha_ma";
  //   $this->tableId = "id_ficha";
  //   $this->pdo = $pdo;
  //   self::isdeleteLogic();
    // ***************
		// parent::$tableName = "ficha_ma";
  //   $this->tableName = parent::$tableName;
  //   parent::$tableId = "id_ficha";
  //   $this->tableId = parent::$tableName;
  //   parent::$pdo = $pdo;
  //   $this->pdo = parent::$pdo;
  //   parent::isdeleteLogic();
  //   self::isdeleteLogic();
    // ***************
	}
	// public function Fichapdo($pdo) {
	// 	MyPDO::tableName("ficha_ma");
 //  	MyPDO::tableId("id_ficha");
 //  	MyPDO::pdo($pdo);
 //  }
}
?>