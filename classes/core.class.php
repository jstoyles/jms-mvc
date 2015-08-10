<?php

abstract class Controller_Base {
	private $url, $pathfile;
	public $params;
	public $error = NULL;
	public $response = NULL;

	public function __construct($url, $pathfile, $params){
		if(!is_array($params)){ $this->error = 'Variable "params" must be an array'; }
		$this->url = $url;
		$this->pathfile = $pathfile;
		$this->params = $params;
	}
	private function __clone(){} // Make private to block from cloning

	public function init() {
		if(!file_exists(CONTROLLERS_DIR . '/' . $this->pathfile . '.php')) {
			$this->error = 'missing controller file ' . CONTROLLERS_DIR . '/' . $this->pathfile . '<br />';
		}
		else { $this->response = $this->pathfile; }
	}
}

abstract class Model_Base {
	private $url, $pathfile;
	public $params;
	public $error = NULL;
	public $response = NULL;

	public function __construct($url, $pathfile, $params){
		if(!is_array($params)){ $this->error = 'Variable "params" must be an array'; }
		$this->url = $url;
		$this->pathfile = $pathfile;
		$this->params = $params;
	}
	private function __clone(){} // Make private to block from cloning

	public function init() {
		if(!file_exists(MODELS_DIR . '/' . $this->pathfile . '.php')) {
			//Do Nothing
		}
		else { $this->response = $this->pathfile; }
	}
}

abstract class View_Base
{
	private $data = array(); // Holds variables assigned to template
	private $render = false; // Holds render status of view.

	public function __construct($template) { // Accept a template to load
		$file = VIEWS_DIR . '/' . strtolower($template) . '.php';

		if (file_exists($file)) {
			$this->render = $file; // Trigger render to include file when this model is destroyed. If we render it now, we wouldn't be able to assign variables to the view!
		}		
	}
	private function __clone(){} // Make private to block from cloning

	public function assign($variable , $value) { // Receives assignments from controller and stores in local data array
		$this->data[$variable] = $value;
	}

	public function __destruct() {
		$data = $this->data; //Parse data variables into local variables, so that they render to the view
		include($this->render); //Render view
	}
}

abstract class Database_Base
{
	private static $dbconnect;
	private $dbtype, $dbhost, $dbname, $dbuser, $dbpassword;
	public $error = NULL;

	public function __construct($dbtype=DB_TYPE, $dbhost=MYSQL_HOST, $dbname=MYSQL_DB, $dbuser=MYSQL_USER, $dbpassword=MYSQL_PASSWORD)
	{
		$this->dbtype = $dbtype;
		$this->dbhost = $dbhost;
		$this->dbname = $dbname;
		$this->dbuser = $dbuser;
		$this->dbpassword = $dbpassword;
	}
	private function __clone(){} // Make private to block from cloning

	public function dbConnect() {
		if (!self::$dbconnect){
			try {
				switch(strtolower($this->dbtype)){
					case'sqlite':
						self::$dbconnect = new PDO('sqlite:'.CODE_BASE.'/db/db.sqlite'); //For use with sqlite db - make sure folder is writable!
						break;
					case'mysql':
						self::$dbconnect = new PDO('mysql:host='.$this->dbhost.';dbname='.$this->dbname, $this->dbuser, $this->dbpassword, array(PDO::ATTR_PERSISTENT=>true)); //For use with mysql
						break;
				}
			}
			catch (PDOException $e) {
				$this->$error = 'Connection failed: '.$e->getMessage();
			}
		}
		return self::$dbconnect;
	}

	public function select($table, $fields='*', $where=NULL, $orderby=NULL, $orderdir='ASC', $overridesql=NULL){
		$c = $this->dbConnect();
		if(isset($overridesql)){ $sql = $overridesql; }
		else{
			$sql = ' SELECT ' . $fields;
			$sql .= ' FROM ' . $table;
			if(isset($where)){ $sql .= ' WHERE ' . $where; }
			if(isset($orderby)){ $sql .= ' ORDER BY ' . $orderby . ' ' . $orderdir; }
		}
		$q = $c->prepare($sql);
		$q->execute();
		return $q->fetchAll();
	}

	public function insert($table, array $fields, array $values, $overridesql=NULL){
		$c = $this->dbConnect();
		if(isset($overridesql)){ $sql = $overridesql; }
		else{
			$sql = ' INSERT INTO ' . $table;
			$sql .= ' ( ';
			$cols = '';
			foreach($fields as $field){ $cols .= "".$field.","; }
			$sql .= substr($cols, 0, -1);
			$sql .= ' ) ';
			$sql .= ' VALUES( ';
			$vals = '';
			foreach($values as $value){ $vals .= "'".$value."',"; }
			$sql .= substr($vals, 0, -1);
			$sql .= ' ) ';
		}
		$q = $c->prepare($sql);
		$q->execute();
		return 'Record(s) Inserted';
	}

	public function update($table, $fields, $values, $wherefield, $wherevalue, $overridesql=NULL){
		$c = $this->dbConnect();
		if(isset($overridesql)){ $sql = $overridesql; }
		else{
			$sql = ' UPDATE ' . $table;
			$updateSet = '';
			for($x=0; $x<count($fields); $x++){
				$updateSet .= $fields[$x] . "=" . "'" . $values[$x] . "',";
			}
			$updateSet .= substr($updateSet, 0, -1);
			$sql .= " SET " . $updateSet;
			$sql .= " WHERE " . $wherefield . "=" . "'" . $wherevalue . "'";
		}
		$q = $c->prepare($sql);
		$q->execute();
		return 'Record Updated';
	}

	public function delete($table, $field, $value, $overridesql=NULL){
		$c = $this->dbConnect();
		if(isset($overridesql)){ $sql = $overridesql; }
		else{
			$sql = ' DELETE FROM ' . $table;
			$sql .= " WHERE " . $field . "=" . "'" . $value . "'";
		}
		$q = $c->prepare($sql);
		$q->execute();
		return 'Record Deleted';
	}

	public function execStoredProcedure($sp, array $params){
		$paramStr = '';
		foreach($params as $param){ $paramStr .= "'".$param."',"; }
		$paramStr = substr($paramStr, 0, -1);

		switch(strtolower($this->dbtype)){
			case'mysql':
				$sql = " CALL " . $sp . "(" . $paramStr . ") ";
				break;
			case'mssql':
				$sql = " EXEC " . $sp . " " . $paramStr . " ";
				break;
		}
		$c = $this->dbConnect();
		$q = $c->prepare($sql);
		$q->execute();
		return $q->fetchAll();
	}

	public function execFunction($fn, array $params){
		$paramStr = '';
		foreach($params as $param){ $paramStr .= "'".$param."',"; }
		$paramStr = substr($paramStr, 0, -1);

		$sql = " SELECT " . $fn . "(" . $paramStr . ") ";
		$c = $this->dbConnect();
		$q = $c->prepare($sql);
		$q->execute();
		return $q->fetchAll();
	}
}

?>
