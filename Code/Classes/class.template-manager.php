<?php
	class TemplateManager{
		private $Template;
		private $DB;
		
		function _construct($DB){
			$this->DB = $DB;
		}
		
		function Load($ID) {
			// load $this->Template from DB
			// for now lets hardcode data for skytech.lt
			$classname = "productListing";
			
			$this->Template['product-list'] = "//table[contains(@class,\"productListing\")][1]//tr";
			$this->Template['product-list-skip'] = 1;
			$this->Template['product-list-field-count'] = 3;
			$this->Template['product-list-item'] = "<template><td>{serial}</td><td></td><td><a href='{href}'></a> </td><td></td><td>{price}</td></template>";
		}
		
		function GetItem($Piece) {
			if (!empty($this->Template[$Piece])) {
				return $this->Template[$Piece];
			}			
			die("Error: TemplateManager->Template[$Piece] is not defined!");
		}
	}
?>