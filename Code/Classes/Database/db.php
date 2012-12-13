<?php
	require_once(dirname(__FILE__) . "/../../config.php");
	require_once(dirname(__FILE__) . '/RedBean/rb.php');
	
	class db {
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
				self::$instance = new db();
			}
			return self::$instance;
		}
	}
?>




