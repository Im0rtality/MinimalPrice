<?php
	require_once(dirname(__FILE__) . "/mysql.php");

	class TemplateManager{
		private $Template;
		private $Name;
		private $DB;
		
		function __construct(){
			$this->DB = MySql::getInstance();
		}
		
		function Load($ID) {
			// load $this->Template from DB
			// for now lets hardcode data for skytech.lt
			$this->DB->ExecuteSQL("SELECT * FROM parser_template WHERE (id={$ID}) LIMIT 1");			
			$Data = $this->DB->GetRecordSet()[0];
			$this->Name = $Data['name'];
			$this->Template = json_decode($Data['value'], true);

			/*
				$this->Template['product-list'] = "//table[contains(@class,\"productListing\")][1]//tr";
				$this->Template['product-list-skip'] = 1;
				$this->Template['product-list-field-count'] = 3;
				$this->Template['product-list-item'] = "<template><td>{serial}</td><td></td><td><a href='{href}'></a> </td><td></td><td>{price}</td></template>";
			*/
			debug($this->Template);
		}
		
		function GetItem($Piece) {
			if (!empty($this->Template[$Piece])) {
				return $this->Template[$Piece];
			}			
			die("Error: TemplateManager->Template[$Piece] is not defined!");
		}
	}
?>