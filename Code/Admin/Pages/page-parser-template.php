<?php
	require_once(dirname(__FILE__) . "\..\..\Classes\mysql.php");
	require_once(dirname(__FILE__) . '\..\..\Classes\formatter.php');
	require_once(dirname(__FILE__) . '\..\interface.page-generator.php');

	class PageParserTemplate extends PageModule implements PageGenerator{
		function __construct(){
			$this->options['link'] = 'shopparser';
			$this->options['name'] = 'Shop Parsers';
			parent::__construct();
        	$this->buttons[] = array(
        			"name" => "Add", 
        			"href" => "?page=edit{$this->options['link']}&action=add", 
        			"icon" => "plus");
		}

		public function generate() {
			$query = "SELECT * FROM `parser`";

			$code = "";
			
			$DB = MySql::getInstance();
			$DB->ExecuteSQL($query);
			$Data = $DB->GetRecordSet();

			$settings['column_names'] = ["Name"];
			$settings['column_widths'] = [""];
			$settings['column_hidden'] = [true, false, true];
			$settings['id_col'] = "id";
			$settings['page'] = "edit{$this->options['link']}";
			
			$code .= Formatter::Table($Data, $settings);

			return $code;
		}
	}

	new PageParserTemplate();
?>