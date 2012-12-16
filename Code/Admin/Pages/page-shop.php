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
            $db = DB::getInstance();
            $shops = R::findAll('shop');
            $data = R::exportAll($shops);

            $settings['column_names'] = ["Country", "Parser", "Name", "URL", "Referral URL"];
            $settings['column_widths'] = ["", "", "", "", ""];
            $settings['column_hidden'] = [true, false, false, false, false, false];
            $settings['id_col'] = "id";
            $settings['page'] = "editshop";

            $code = "";
            $code .= Formatter::Table($data, $settings);

            return $code;
        }
    }

    new PageShop();
?>