<?php
	require_once("..\Abstracts\database-table.php");
	class Product implements DatabaseTable{
		protected $DB;
		
		
		function __construct($DB){
			$this->DB = $DB;
		}

		function get() {

		}
		
		function set() {

		}

		function load($id) {
			
		}

		function store() {

		}
	}
?>