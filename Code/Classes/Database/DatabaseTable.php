<?php
	require_once(dirname(__FILE__) . "\..\..\Classes\mysql.php");

	class DatabaseTable{
		private $DB;
		private $Query;
		private $Table;

		function __construct(){
			$this->DB = MySql::getInstance();

		}

		function SelectTable($Table) {
			$this->Table = $Table;
		}

		function GetItems() {
			$this->Query = "SELECT * FROM {$this->Table}";

			$this->DB->ExecuteSQL($this->Query);

			return $this->DB->GetDataSet();
		}

		/**
		 * Argument structure
		 * $ItemData = array('id' => 1, 'field1' => 'text', 'field2' => '0') ;
		 */
		function AddItem($ItemData) {
			// INSERT INTO ... ... ...
		}

		function GetItemByField($Field, $Value) {
			// SELECT * FROM ... WHERE ($Field = $Value) LIMIT 1
		}

		function GetItem($Id) {
			return $this->GetItemByField('id', $Id);
		}

		function GetItemsByField($Field, $Value) {
			// SELECT * FROM ... WHERE ($Field = $Value) LIMIT 1
		}

		function DeleteItem($Id) {
			// DELETE FROM ... WHERE (id = $id) LIMIT 1
		}

	}
?>