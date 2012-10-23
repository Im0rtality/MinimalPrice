<?php
	require_once(dirname(__FILE__) . "\..\..\Classes\mysql.php");
	require_once(dirname(__FILE__) . '\..\..\Classes\formatter.php');
	require_once(dirname(__FILE__) . '\..\interface.page-generator.php');

	class PageCountry extends PageModule implements PageGenerator{
		function __construct(){
			$this->options['link'] = 'country';
			$this->options['name'] = 'Countries';
			parent::__construct();
		}

		public function generate() {
			$query = "SELECT * FROM `country`";

			$code = "<h1>{$this->options['name']}</h1>";
			
			$DB = MySql::getInstance();
			$DB->ExecuteSQL($query);
			$Data = $DB->GetRecordSet();
			$code .= Formatter::DbDataToHtmlTable($Data);

			return $code;
		}
	}

	new PageCountry();
?>