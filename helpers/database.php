<?php

class Database {
	private static $_instance;
	private static $_instance1;
	public static function getInstance($instance = 1, $databaseName = null) {
		if ($instance == 1) {
			if (! self::$_instance) {
				self::$_instance = new self ( DBTYPE, DBNAME );
			}
			return self::$_instance;
		} else if ($instance == 2) {
			if (! self::$_instance1) {
				if ($databaseName == null)
					throw new Exception ( 'Database name is empty' );
				self::$_instance1 = new self ( DBTYPE, $databaseName );
			}
			return self::$_instance1;
		}
	}
	private function __construct($dbType, $dbname) {
	try {			
			$arry = array ();
			if ($dbType == 'mysql') {
				$DSN = "mysql:host=" . DB_SERVER . ";port=3306;dbname=" . $dbname;
				$arry = array (
						PDO::ATTR_PERSISTENT => true,
						PDO::ATTR_EMULATE_PREPARES => false 
				);
			} else if ($dbType == 'mssql') {
				$DSN = "dblib:host=" . DB_SERVER . ":" . DB_PORT . ";dbname=" . $dbname;
				$arry = array(
						PDO::ATTR_TIMEOUT => "200"
				);
			} else if ($dbType == 'mssql_local') {
				$DSN = "sqlsrv:server=tcp:" . DB_SERVER . ",1433 ; Database = $dbname".";ConnectionPooling=0";
				$arry = array(
						PDO::ATTR_TIMEOUT => "200"
				);
			}
			$this->pdo = new PDO ( $DSN, DB_USER, DB_PASS, $arry );
			$this->pdo->setAttribute ( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
		} catch ( PDOException $e ) {
			header ( "HTTP/1.1" . " 502 " . " Bad Gateway - Please contact API Administrator -" . $e->getMessage () );
		}
	}
	public function __destruct() {
	}
	
	/*
	 * $statement - pass the query (SELECT * FROM <table_name>)
	 */
	public function query($statement) {
		try {
			$stmt = $this->pdo->query ( $statement );
			return $stmt->fetchAll ( PDO::FETCH_ASSOC );
		} catch ( Exception $e ) {
			// echo $e->getMessage ();
			throw new Exception ( $e->getMessage () );
		}
	}
	
	public function queryDB($statement) {
		try {
			$stmt = $this->pdo->query ( $statement );
			return true;
		} catch ( Exception $e ) {
			// echo $e->getMessage ();
			throw new Exception ( $e->getMessage () );
		}
	}
	
	public function queryWithLimit($statement, $skip = 0, $take = 0) {
		try {
			if ($take == 0) {
				if ($skip > 0)
					$statement = $statement . " OFFSET " . $skip . " ROWS";
			} else {
				$statement = $statement . " OFFSET " . $skip . " ROWS FETCH NEXT " . $take . " ROWS ONLY";
			}
			
			$stmt = $this->pdo->query ( $statement );
			return $stmt->fetchAll ( PDO::FETCH_ASSOC );
		} catch ( Exception $e ) {
			throw new Exception ( $e->getMessage () );
		}
	}
	
	/*
	 * $statement - pass the query (SELECT * FROM <table_name>)
	 * $objectName - Class Name
	 */
	public function queryWithMapObj($statement, $objectName) {
		try {
			$stmt = $this->pdo->query ( $statement );
			$stmt->setFetchMode ( PDO::FETCH_CLASS, $objectName );
			return $stmt->fetchAll ();
		} catch ( Exception $e ) {
			// echo $e->getMessage ();
			throw new Exception ( $e->getMessage () );
		}
	}
	
	/*
	 * $statement - INSERT INTO <table_name> VALUES();
	 */
	public function insert($statement, $IsAutoIncrement = false) {
		$std = new stdClass ();
		try {
			$stmt = $this->pdo->prepare ( $statement )->execute ();
			$std->id = 0;
			if ($IsAutoIncrement == true)
				$std->id = $this->pdo->lastInsertId ();
			$std->error = $this->pdo->errorCode ();
			$std->errorInfo = $this->pdo->errorInfo ();
			
			return $std;
		} catch ( Exception $e ) {
			// echo $e->getMessage ();
			throw new Exception ( $e->getMessage () );
		}
	}
	
	/*
	 * $statement - UPDATE someTable SET name = :name WHERE id = :id
	 */
	public function update($statement) {
		$std = new stdClass ();
		try {
			
			$stmt = $this->pdo->prepare ( $statement );
			$stmt->execute ();
			
			$std->count = $stmt->rowCount ();
			$std->error = $this->pdo->errorCode ();
			$std->errorInfo = $this->pdo->errorInfo ();
		} catch ( Exception $e ) {
			// echo $e->getMessage ();
			throw new Exception ( $e->getMessage () );
		}
		return $std;
	}
	
	/*
	 * $statement - DELETE FROM someTable WHERE id = :id
	 */
	public function delete($statement) {
		$std = new stdClass ();
		try {
			
			$stmt = $this->pdo->prepare ( $statement );
			$stmt->execute ();
			
			$std->count = $stmt->rowCount ();
			$std->error = $this->pdo->errorCode ();
			$std->errorInfo = $this->pdo->errorInfo ();
		} catch ( Exception $e ) {
			throw new Exception ( $e->getMessage () );
		}
		
		return $std;
	}
	
	/* Begin Transaction */
	public function beginTransaction() {
		$this->pdo->beginTransaction ();
	}
	
	/* Commit */
	public function commit() {
		$this->pdo->commit ();
	}
	
	/* RollBack */
	public function rollback() {
		$this->pdo->rollBack ();
	}
}

?>