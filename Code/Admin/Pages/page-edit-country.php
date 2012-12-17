<?php
	require_once(dirname(__FILE__) . "\..\..\Classes\mysql.php");
	require_once(dirname(__FILE__) . '\..\..\Classes\formatter.php');
	require_once(dirname(__FILE__) . '\..\interface.page-generator.php');

	class PageEditCountry extends PageModule implements PageGenerator{
		function __construct(){
			$this->options['link'] = 'editcountry';
			$this->options['name'] = 'Countries Editor';
			$this->options['inSidebar'] = false;
			parent::__construct();
		}

		public function generate() {
			$code = "";
			switch ($this->action){
				case 'save':
					$code .= Formatter::ArraySimpleDump2($_POST, "POST Data");
					$query = Formatter::QuerySaveEditor($_POST, "country");

					
					$DB = MySql::getInstance();
					$DB->ExecuteSQL($query);

					$code .= Formatter::Redirect('country', 3000, "Redirecting to list in 3 seconds.");
					break;
				case 'delete':
					$DB = MySql::getInstance();
					$DB->ExecuteSQL(Formatter::QueryDeleteEntry('country', $_GET["id"]));

					$code .= Formatter::Redirect('country', 3000, "Redirecting to list in 3 seconds.");
					break;
				case 'edit':
					$query = Formatter::QueryLoadEditor("country", $_GET["id"]);

					
					$DB = MySql::getInstance();
					$DB->ExecuteSQL($query);
					$Data = $DB->GetRecordSet();

					$code .= Formatter::ArraySimpleDump2($Data[0], "<i>$query</i>");
				case 'add':
					if (empty($Data)) {
						$Data[0] = array("id" => null, "name" => null);
					}
					$FormData['id'] = $Data[0]["id"];
					$FormData['page'] = "editcountry";
					$FormData['fields'][] = ["label" => "ID",
											 "type" => "text", 
											 "id" => "", 
											 "value" => $Data[0]["id"], 
											 "disabled" => true];
					
					$FormData['fields'][] = ["label" => "Name",
											 "type" => "text", 
											 "id" => "name", 
											 "value" => $Data[0]["name"]];

					$code .= Formatter::Form($FormData, NULL);
				break;

			}
			return $code;
		}
	}

	new PageEditCountry();
?>