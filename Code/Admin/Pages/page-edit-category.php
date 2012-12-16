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
					$code .= Formatter::ArraySimpleDump2($_POST, "POST Data");
					$query = Formatter::QuerySaveEditor($_POST, "category");

					
					$DB = MySql::getInstance();
					$DB->ExecuteSQL($query);

					$code .= Formatter::Redirect('category', 3000, "Redirecting to list in 3 seconds.");
					break;
				case 'edit':
					$query = Formatter::QueryLoadEditor("category", $_GET["id"]);

					
					$DB = MySql::getInstance();
					$DB->ExecuteSQL($query);
					$Data = $DB->GetRecordSet();

					$code .= Formatter::ArraySimpleDump2($Data[0], "<i>$query</i>");

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

					$FormData['fields'][] = ["label" => "Parent ID",
											 "type" => "text", 
											 "id" => "parent_id", 
											 "value" => $Data[0]["parent_id"]];

					$FormData['fields'][] = ["label" => "Image ID",
											 "type" => "text", 
											 "id" => "cimage_id", 
											 "value" => $Data[0]["cimage_id"]];
					$code .= Formatter::Form($FormData, NULL);
				break;

			}
			return $code;
		}
	}

	new PageEditCategory();
?>