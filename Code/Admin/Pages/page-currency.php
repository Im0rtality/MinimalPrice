<?php
	require_once(dirname(__FILE__) . "\..\..\Classes\Database\db.php");
	require_once(dirname(__FILE__) . '\..\..\Classes\formatter.php');
	require_once(dirname(__FILE__) . '\..\interface.page-generator.php');

	class PageCurrency extends PageModule implements PageGenerator{
		function __construct(){
			$this->options['link'] = 'currency';
			$this->options['name'] = 'Currencies';
			parent::__construct();
		}

		public function generate() {
			$db = DB::getInstance();
			$currencies = R::findAll('currency');
			$data = R::exportAll($currencies);
			
			$settings['column_names'] = ["Name", "Symbol"];
			$settings['column_widths'] = ["", ""];
			$settings['column_hidden'] = [true, false, false];
			$settings['id_col'] = "id";
			$settings['page'] = "editcurrency";
			
			$code = "";
			$code .= Formatter::Table($data, $settings);

			return $code;
		}
	}

	new PageCurrency();
?>