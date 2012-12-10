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
			$query = "SELECT shop.id, shop.name, shop.url, country.name as cname FROM `shop`, `country` WHERE (shop.country_id = country.id)";

			$code = "";
			
			$DB = MySql::getInstance();
			$DB->ExecuteSQL($query);
			$Data = $DB->GetRecordSet();

			$settings['column_names'] = ["Name", "URL", "Country"];
			$settings['column_widths'] = ["", "", ""];
			$settings['column_hidden'] = [true, false, false, false];
			$settings['id_col'] = "id";
			$settings['page'] = "editshop";
			
			$code .= Formatter::Table($Data, $settings);

			return $code;
		}
	}

	new PageShop();
?>