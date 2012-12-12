<?php
	require_once(dirname(__FILE__) . "\..\..\Classes\mysql.php");
	require_once(dirname(__FILE__) . '\..\..\Classes\formatter.php');
	require_once(dirname(__FILE__) . '\..\interface.page-generator.php');

	class PageParserTemplate extends PageModule implements PageGenerator{
		function __construct(){
			$this->options['link'] = 'shopparsers';
			$this->options['name'] = 'Shop Parsers';
			parent::__construct();
		}

		public function generate() {
			$query = "SELECT * FROM `parser_template`";

			$code = "";
			
			$DB = MySql::getInstance();
			$DB->ExecuteSQL($query);
			$Data = $DB->GetRecordSet();

			$settings['column_names'] = ["Name"];
			$settings['column_widths'] = [""];
			$settings['column_hidden'] = [true, false, true];
			$settings['id_col'] = "id";
			$settings['page'] = "editshopparser";
			
			$code .= Formatter::Table($Data, $settings);

			return $code;
		}
	}

	new PageParserTemplate();
?>