<?php
/**
 * 	Use this as template for making new Admin Panel pages. 
 * 
 *  Required Changes:
 *  1) Rename class
 *  2) change body of generate() method with appropriate code
 * 
 * **/
    require_once(dirname(__FILE__) . "\..\..\Classes\Database\db.php");
    require_once(dirname(__FILE__) . '\..\..\Classes\formatter.php');
    require_once(dirname(__FILE__) . '\..\interface.page-generator.php');

    class PageShop extends PageModule implements PageGenerator{
        function __construct(){
            $this->options['link'] = 'shop';
            $this->options['name'] = 'Shops';
            parent::__construct();
        }

        public function generate() {
			$query = "SELECT shop.*, country.name as cname, parser.name as pname FROM `shop`, `country`, `parser` WHERE (shop.country_id = country.id) AND (shop.parser_id = parser.id)";

			$code = "";

			$DB = MySql::getInstance();
			$DB->ExecuteSQL($query);
			$Data = $DB->GetRecordSet();

            $settings['column_names'] = ["Name", "URL", "Referral URL", "Country", "Parser"];
            $settings['column_widths'] = ["", "", "", "", ""];
            $settings['column_hidden'] = [true, true, true, false, false, false, false, false];
            $settings['id_col'] = "id";
            $settings['page'] = "editshop";

            $code = "";
            $code .= Formatter::Table($Data, $settings);

            return $code;
        }
    }

    new PageShop();
?>