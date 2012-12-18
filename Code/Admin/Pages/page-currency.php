<?php
	require_once(dirname(__FILE__) . "\..\..\Classes\Database\db.php");
	require_once(dirname(__FILE__) . '\..\..\Classes\Codegen\table.php');
	require_once(dirname(__FILE__) . '\..\interface.page-generator.php');

	class PageCurrency extends PageModule implements PageGenerator{
		function __construct(){
			$this->options['link'] = 'currency';
			$this->options['name'] = 'Currencies';
			parent::__construct();
        	$this->buttons[] = array(
        			"name" => "Add", 
        			"href" => "?page=edit{$this->options['link']}&action=add", 
        			"icon" => "plus");
		}

		public function generate() {
			$query = "SELECT * FROM `currency`";

			$code = "";

			$DB = MySql::getInstance();
			$DB->ExecuteSQL($query);
			$Data = $DB->GetRecordSet();

			$settings['column_names'] = ["Code", "EUR Ratio"];
			$settings['column_widths'] = ["", ""];
			$settings['column_hidden'] = [true, false, false];
			$settings['id_col'] = "id";
			$settings['page'] = "edit{$this->options['link']}";
			
			$code = "";
			$code .= CodegenTable::Table($Data, $settings);

			return $code;
		}
	}

	new PageCurrency();
?>