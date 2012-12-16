<?php
	require_once(dirname(__FILE__) . "\..\..\Classes\Database\db.php");
	require_once(dirname(__FILE__) . '\..\..\Classes\formatter.php');
	require_once(dirname(__FILE__) . '\..\interface.page-generator.php');

	class PageEditCurrency extends PageModule implements PageGenerator{
		function __construct(){
			$this->options['link'] = 'editcurrency';
			$this->options['name'] = 'Currencies Editor';
			$this->options['inSidebar'] = false;
			parent::__construct();
			switch ($this->action) {
				case 'edit':
					break;
				case 'save':
					break;
				case 'delete':
					break;
			}
		}

		public function generate() {
			$code = "";
			switch ($this->action){
				case 'edit':
					$db = DB::getInstance();
					$currency = R::load('currency', $_GET["id"]);
					$array = $currency->export();

					$FormData['id'] = $array["id"];
					$FormData['page'] = "editcurrency";
					$FormData['fields'][] = ["label" => "ID",
											 "type" => "text", 
											 "id" => "", 
											 "value" => $array["id"], 
											 "disabled" => true];
					
					$FormData['fields'][] = ["label" => "Name",
											 "type" => "text", 
											 "id" => "name", 
											 "value" => $array["name"]];

					$FormData['fields'][] = ["label" => "Symbol",
											 "type" => "text", 
											 "id" => "symbol", 
											 "value" => $array["symbol"]];
					$code .= Formatter::Form($FormData, NULL);
				break;
				
				case 'save':
					$code .= Formatter::ArraySimpleDump2($_POST, "POST Data");
					
					$db = DB::getInstance();
                                        $currency = R::dispense('currency');
                                        $currency->import($_POST);
                                        R::store($currency);
					
					$code .= Formatter::Redirect('currency', 3000, "Redirecting to list in 3 seconds.");
				break;
				
				case 'delete':
					$db = DB::getInstance();
					$currency = R::load('currency', $_GET["id"]);
					R::trash($currency);
				break;

			}
			return $code;
		}
	}

	new PageEditCurrency();
?>