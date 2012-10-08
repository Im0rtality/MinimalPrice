<?php
/**
 * 	Use this as template for making new Admin Panel pages. 
 * 
 *  Required Changes:
 *  1) Rename class
 *  2) change body of generate() method with appropriate code
 * 
 * **/
	require_once(dirname(__FILE__) . "\..\..\Classes\mysql.php");
	require_once(dirname(__FILE__) . '\..\..\Classes\formatter.php');
	require_once(dirname(__FILE__) . '\..\interface.page-generator.php');

	class PageShop extends PageModule implements PageGenerator{
		function __construct(){
			$this->options['link'] = 'shop';
			$this->options['name'] = 'Shops';
			parent::__construct();
		}

		public function generate() {
			$query = "SELECT * FROM `shop`";

			$code = "<h1>{$this->options['name']}</h1>";
			
			$DB = MySql::getInstance();
			$DB->ExecuteSQL($query);
			$Data = $DB->GetRecordSet();
			$code .= Formatter::DbDataToHtmlTable($Data);

			return $code;
		}
	}

	new PageShop();
?>