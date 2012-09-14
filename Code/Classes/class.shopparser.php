<?php
	class ShopParser{
		protected $Hostname;	
		
		public  $LastError;				
		private $DBLink;
		private $Reader;
		private $Buffer;
		private $DOM;
		private $Nodes;
		
		
		function ShopParser($DB, $R){
			$this->DBLink = $DB;
			$this->Reader = $R;
			$this->DOM = new DOMDocument();
		}
		
		function GetContent($PageUrl) {
			$this->Buffer = $this->Reader->Get($PageUrl);
			return $this->Buffer;
		}
		
		function Load() {
			if (!empty($this->Buffer)) {
				$this->DOM->loadHTML($Buffer);
				return true;
			} else {
				$this->LastError = "Error: Buffer is empty";
				return false;
			}
		}
		
		function ExtractData() {
			$this->Nodes = $this->DOM->getElementsByTagName('table');
		}
		
		function GetOutput() {
			return $this->Nodes;
		}
	}
?>