<?php

class Test_Model
{
	public function __construct(){}

	public function select_test($field=NULL, $value=NULL) {
		$db = new DB();
		if(isset($field) && isset($value)){
			$where = $field."="."'".$value."'";
			$TestItems = $db->select('test','*', $where);
		}
		else {
			$TestItems = $db->select('test');
		}

		return $TestItems;
	}

	public function insert_test(array $fields, array $values) {
		$db = new DB();
		$insertRecord = $db->insert('test', $fields, $values);
		return $insertRecord;
	}

	public function update_test($fields, $values, $wherefield, $wherevalue) {
		$db = new DB();
		$updateRecord = $db->update('test', $fields, $values, $wherefield, $wherevalue);
		return $updateRecord;
	}

	public function delete_test($field, $value) {
		$db = new DB();
		$deleteRecord = $db->delete('test', $field, $value);
		return $deleteRecord;
	}

	public function sp_test($sp, $params) {
		$db = new DB();
		$execSP = $db->execStoredProcedure($sp, $params);
		return $execSP;
	}

	public function fn_test($fn, $params) {
		$db = new DB();
		$execFn = $db->execFunction($fn, $params);
		return $execFn;
	}
}
?>
