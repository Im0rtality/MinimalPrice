<?php
	class TemplateManager{
		private $Template;
		private $DB;
		
		function _construct($DB){
			$this->DB = $DB;
		}
		
		function Load($ID) {
			debug("TemplateManager->Load('$ID')");
			// load $this->Template from DB
			// for now lets hardcode data for skytech.lt
			$classname = "productListing";
			
			$this->Template['product-list'] = "//table[contains(@class,\"$classname\")][1]//tr";
		}
		
		function GetItem($Piece) {
			debug("TemplateManager->GetItem('$Piece')");
			if (!empty($this->Template[$Piece])) {
				return $this->Template[$Piece];
			}			
			die("Error: TemplateManager->Template[$Piece] is not defined!");
		}
	}
?>