<?php
	require_once(dirname(__FILE__) . "\..\..\Classes\mysql.php");
	require_once(dirname(__FILE__) . '\..\..\Classes\formatter.php');
	require_once(dirname(__FILE__) . '\..\interface.page-generator.php');

	class PageEditCategory extends PageModule implements PageGenerator{
		function __construct(){
			$this->options['link'] = 'editcategory';
			$this->options['name'] = 'Categories Editor';
			$this->options['inSidebar'] = false;
			parent::__construct();
		}

		public function generate() {
			$code = "";
			switch ($this->action){
				case 'save':
					$code .= Formatter::ArraySimpleDump2($_POST, "POST Data");
					$query = Formatter::QuerySaveEditor($_POST, "category");

					
					$DB = MySql::getInstance();
					$DB->ExecuteSQL($query);

					$code .= Formatter::Redirect('category', 3000, "Redirecting to list in 3 seconds.");
					break;
				case 'delete':
					$DB = MySql::getInstance();
					$DB->ExecuteSQL(Formatter::QueryDeleteEntry('category', $_GET["id"]));

					$code .= Formatter::Redirect('category', 3000, "Redirecting to list in 3 seconds.");

					break;
				case 'edit':
					$query = Formatter::QueryLoadEditor("category", $_GET["id"]);

					
					$DB = MySql::getInstance();
					$DB->ExecuteSQL($query);
					$Data = $DB->GetRecordSet();

					$code .= Formatter::ArraySimpleDump2($Data[0], "<i>$query</i>");

				case 'add':
					if (empty($Data)) {
						$Data[0] = array("id" => null, "name" => null, "parent_id" => null, "cimage_id" => null);
					}
					$FormData['id'] = $Data[0]["id"];
					$FormData['page'] = "editcategory";
					$FormData['fields'][] = ["label" => "ID",
											 "type" => "text", 
											 "id" => "", 
											 "value" => $Data[0]["id"], 
											 "disabled" => true];
					
					$FormData['fields'][] = ["label" => "Category",
											 "type" => "text", 
											 "id" => "name", 
											 "value" => $Data[0]["name"]];

					$FormData['fields'][] = ["label" => "Parent",
											 "type" => "select", 
											 "id" => "parent_id", 
											 "value" => $Data[0]["parent_id"],
											 "values" => Formatter::GetDataForSelect('category', 'name', true)];

					$FormData['fields'][] = ["label" => "Image",
											 "type" => "select", 
											 "id" => "cimage_id", 
											 "value" => $Data[0]["cimage_id"],
											 "values" => Formatter::GetDataForSelect('cimage', 'url')];

					$code .= Formatter::Form($FormData, NULL);
				break;

			}
			return $code;
		}
	}

	new PageEditCategory();
?>