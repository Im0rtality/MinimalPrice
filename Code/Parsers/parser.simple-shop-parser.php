<?php
     require_once(dirname(__FILE__) . "/../Shared/utils.php");
     require_once(dirname(__FILE__) . "/../Classes/shop-parser.php");
		
	class SimpleShopParser extends ShopParser{	
		private $ShopId;

		function __construct($ShopId){
			parent::__construct();
			// set module consts
			$this->ShopId = $ShopId;
			$this->Templates->Load($this->ShopId);
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