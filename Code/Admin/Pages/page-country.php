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

			$code = "";
			
			$DB = MySql::getInstance();
			$DB->ExecuteSQL($query);
			$Data = $DB->GetRecordSet();

			$settings['column_names'] = ["Name"];
			$settings['column_widths'] = [""];
			$settings['column_hidden'] = [true, false];
			$settings['id_col'] = "id";
			$settings['page'] = "editcountry";
			
			$code .= Formatter::Table($Data, $settings);
			return $code;
		}
	}

	new PageCountry();
?>