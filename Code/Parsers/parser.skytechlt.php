<?php
    require_once("../Shared/utils.php");
    require("../Classes/shop-parser.php");
		
	class SimpleShopParser extends ShopParser{	

		function __construct($DB, $Reader){
			parent::__construct($DB, $Reader);
			// set module consts
			$this->Options['name'] = "Skytech.lt parser module";

			$this->Templates->Load($this->GetName());
		}
				
		private function ReadyCheck(){
			if (empty($this->DB)) {
				die("Error: SimpleShopParser->DB is not defined!");
			}
			if (empty($this->Reader)) {
				die("Error: SimpleShopParser->Reader is not defined!");
			}
			return true;
		}
		
		function Parse(){
			if ($this->ReadyCheck() === true) {
				$this->ExtractData();
			} else {
				die("SimpleShopParser->Parse: Error not ready");
			}
		}
		
		function GetData() {	
			return $this->GetOutput();
		}
	}
?>