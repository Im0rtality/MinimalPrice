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
					$code .= dump($_POST);
					$query = "UPDATE `country` SET ";
					foreach($_POST as $key => $value) {
						if ($key != 'id') {
							$query .= "{$key}='{$value}'";
						}
					}
					$query .= "WHERE id = {$_POST["id"]} LIMIT 1";

					
					$DB = MySql::getInstance();
					$DB->ExecuteSQL($query);
					break;
				case 'edit':
					$query = "SELECT * FROM `country` WHERE id = {$_GET["id"]} LIMIT 1";

					
					$DB = MySql::getInstance();
					$DB->ExecuteSQL($query);
					$Data = $DB->GetRecordSet();
					$code .= dump($Data);
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