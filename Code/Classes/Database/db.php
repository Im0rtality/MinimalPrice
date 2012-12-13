<?php
	require_once(dirname(__FILE__) . "/../../config.php");
	require_once(dirname(__FILE__) . '/RedBean/rb.php');
	
	class DB {
		protected static $instance;
		
		
		
		private function __construct()
		{
			R::setup('mysql:host=' . MYSQL_HOST . ';dbname=' . MYSQL_NAME, MYSQL_USER, MYSQL_PASS);
			R::freeze( true );	
		}
		
		public function __destruct() 
		{
		   R::close();
		}	
		
		private function __clone()
		{}
		
		public static function getInstance()
		{
			if (!self::$instance)
			{
				self::$instance = new DB();
			}
			return self::$instance;
		}
		
		
		
		//----- methods for working with data -----
		public function updateRow($tableName, $data) {
			$table = R::load($tableName, $data["id"]);
					
			foreach($data as $key => $value) { // key and value from associative array
				if ($key != 'id') {
					$table->$key = $value;
				}
			}
			R::store($table);
		}
	}
?>




