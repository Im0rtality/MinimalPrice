<?php
    require_once(dirname(__FILE__) . "/../../config.php");
    require_once(dirname(__FILE__) . '/RedBean/rb.php');

    class DB {
        protected static $instance;

        

        private function __construct()
        {
            R::setup('mysql:host=' . MYSQL_HOST . ';dbname=' . MYSQL_NAME, MYSQL_USER, MYSQL_PASS);
            R::debug(false);
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
        
        public static function escapeArray($array)
        {
            foreach($array as $key => $value) {
                $array[$key] = mysql_real_escape_string($value);
            }
            return $array;
        }
    }
?>




