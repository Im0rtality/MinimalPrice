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
					$query = Formatter::QueryLoadEditor("currency", $_GET["id"]);

					
					$DB = MySql::getInstance();
					$DB->ExecuteSQL($query);
					$Data = $DB->GetRecordSet();

					$code .= Formatter::ArraySimpleDump2($Data[0], "<i>$query</i>");

				case 'add':
					if (empty($Data)) {
						$Data[0] = array("id" => null, "code" => null, "eur_ratio" => null);
					}
					$FormData['id'] = $Data[0]["id"];
					$FormData['page'] = "editcurrency";
					$FormData['fields'][] = ["label" => "ID",
											 "type" => "text", 
											 "id" => "", 
											 "value" => $Data[0]["id"], 
											 "disabled" => true];
					
					$FormData['fields'][] = ["label" => "Code",
											 "type" => "text", 
											 "id" => "code", 
											 "value" => $Data[0]["code"]];

					$FormData['fields'][] = ["label" => "EUR Ratio",
											 "type" => "text", 
											 "id" => "eur_ratio", 
											 "value" => $Data[0]["eur_ratio"]];
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
					$code .= Formatter::Redirect('currency', 3000, "Redirecting to list in 3 seconds.");
				break;

			}
			return $code;
		}
	}

	new PageEditCurrency();
?>