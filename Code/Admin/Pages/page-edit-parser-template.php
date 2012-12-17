<?php
	require_once(dirname(__FILE__) . "\..\..\Classes\mysql.php");
	require_once(dirname(__FILE__) . '\..\..\Classes\formatter.php');
	require_once(dirname(__FILE__) . '\..\interface.page-generator.php');

	class PageEditShopParser extends PageModule implements PageGenerator{
		function __construct(){
			$this->options['link'] = 'editshopparser';
			$this->options['name'] = 'Shop Parser Editor';
			$this->options['inSidebar'] = false;
			parent::__construct();
			$this->table = 'parser';
		}

		public function generate() {
			$code = "";
			switch ($this->action){
				case 'save':
					$code .= Formatter::ArraySimpleDump2($_POST, "POST Data");
					$query = Formatter::QuerySaveEditor($_POST, $this->table);

					
					$DB = MySql::getInstance();
					$DB->ExecuteSQL($query);

					$code .= Formatter::Redirect('shopparser', 3000, "Redirecting to list in 3 seconds.");
					break;
				case 'edit':
					$query = Formatter::QueryLoadEditor($this->table, $_GET["id"]);

					
					$DB = MySql::getInstance();
					$DB->ExecuteSQL($query);
					$Data = $DB->GetRecordSet();

					$code .= Formatter::ArraySimpleDump2($Data[0], "<i>$query</i>");

				case 'add':
					if (empty($Data)) {
						$Data[0] = array("id" => null, "name" => null, "value" => null);
					}
					$FormData['id'] = $Data[0]["id"];
					$FormData['page'] = "editshopparser";
					$FormData['fields'][] = ["label" => "ID",
											 "type" => "text", 
											 "id" => "", 
											 "value" => $Data[0]["id"], 
											 "disabled" => true];
					
					$FormData['fields'][] = ["label" => "Name",
											 "type" => "text", 
											 "id" => "name", 
											 "value" => $Data[0]["name"]];

					$FormData['fields'][] = ["label" => "Value",
											 "type" => "textarea", 
											 "id" => "value", 
											 "value" => $Data[0]["value"]];

					$code .= Formatter::Form($FormData, NULL);
				break;

			}
			return $code;
		}
	}

	new PageEditShopParser();
?>