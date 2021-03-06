<?php
	require_once(dirname(__FILE__) . "\..\..\Classes\mysql.php");
	require_once(dirname(__FILE__) . '\..\..\Classes\Codegen\form.php');
	require_once(dirname(__FILE__) . '\..\..\Classes\Codegen\query.php');
	require_once(dirname(__FILE__) . '\..\..\Classes\Codegen\misc.php');
	require_once(dirname(__FILE__) . '\..\interface.page-generator.php');

	class PageEditShop extends PageModule implements PageGenerator{
		function __construct(){
			$this->options['link'] = 'editshop';
			$this->options['name'] = 'Edit Shop';
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
				case 'save':
					$code .= CodegenMisc::ArraySimpleDump2($_POST, "POST Data");
					$query = CodegenQuery::QuerySaveEditor($_POST, "shop");

					
					$DB = MySql::getInstance();
					$DB->ExecuteSQL($query);

					$code .= CodegenMisc::Redirect('shop', 3000, "Redirecting to list in 3 seconds.");
					break;
				case 'delete':
					$DB = MySql::getInstance();
					$DB->ExecuteSQL(CodegenQuery::QueryDeleteEntry('shop', $_GET["id"]));

					$code .= CodegenMisc::Redirect('shop', 3000, "Redirecting to list in 3 seconds.");
					break;
			case 'edit':
					$query = CodegenQuery::QueryLoadEditor("shop", $_GET["id"]);

					
					$DB = MySql::getInstance();
					$DB->ExecuteSQL($query);
					$Data = $DB->GetRecordSet();

					$code .= CodegenMisc::ArraySimpleDump2($Data[0], "<i>$query</i>");

				case 'add':
					if (empty($Data)) {
						$Data[0] = array("id" => null, "parser_id" => null, "country_id" => null, "name" => null, "url" => null, "referral_url" => null);
					}
					$FormData['id'] = $Data[0]["id"];
					$FormData['page'] = "editshop";

					$FormData['fields'][] = ["label" => "ID",
											 "type" => "text", 
											 "id" => "", 
											 "value" => $Data[0]["id"], 
											 "disabled" => true];
					
					$FormData['fields'][] = ["label" => "Parser",
											 "type" => "select", 
											 "id" => "parser_id", 
											 "value" => $Data[0]["parser_id"], 
											 "values" => CodegenQuery::GetDataForSelect('parser', 'name')];

					$FormData['fields'][] = ["label" => "Country",
											 "type" => "select", 
											 "id" => "country_id", 
											 "value" => $Data[0]["country_id"], 
											 "values" => CodegenQuery::GetDataForSelect('country', 'name')];

					$FormData['fields'][] = ["label" => "Name",
											 "type" => "text", 
											 "id" => "name", 
											 "value" => $Data[0]["name"]];

					$FormData['fields'][] = ["label" => "URL",
											 "type" => "text", 
											 "id" => "url", 
											 "value" => $Data[0]["url"]];

					$FormData['fields'][] = ["label" => "Referral",
											 "type" => "text", 
											 "id" => "referral_url", 
											 "value" => $Data[0]["referral_url"]];

					$code .= CodegenForm::Form($FormData, NULL);
				break;

			}
			return $code;
		}
	}

	new PageEditShop();
?>