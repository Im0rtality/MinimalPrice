<?php
	require_once(dirname(__FILE__) . "\..\..\Classes\mysql.php");
	require_once(dirname(__FILE__) . '\..\..\Classes\Codegen\table.php');
	require_once(dirname(__FILE__) . '\..\interface.page-generator.php');

	class PageProducts extends PageModule implements PageGenerator{
		function __construct(){
			$this->options['link'] = 'products';
			$this->options['name'] = 'Merchandise';
			parent::__construct();
		}

		public function generate() {
			$query = "SELECT merchandise.id, merchandise.url, merchandise.cost, currency.code FROM `merchandise`, `currency` WHERE (merchandise.currency_id = currency.id)";

			$code = "";
			
			$DB = MySql::getInstance();
			$DB->ExecuteSQL($query);
			$Data = $DB->GetRecordSet();

			$settings['column_names'] = ["URL", "Price", "Currency"];
			$settings['column_widths'] = ["", "20px", "20px"];
			$settings['column_hidden'] = [true, false, false, false];
			$settings['id_col'] = "id";
			$settings['page'] = "edit{$this->options['link']}";
			
			$code .= CodegenTable::Table($Data, $settings);
			return $code;
		}
	}

	new PageProducts();
?>