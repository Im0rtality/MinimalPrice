<?php
	class WPTTemplate {
		public $XPath;
		public $Tmpl;
		public $Skip;
		public $FieldCount;
				
		public function load($XPath, $Tmpl, $Skip, $FieldCount){
			$this->XPath	  =	$XPath;
			$this->Tmpl   = $Tmpl;
			$this->Skip       = $Skip;
			$this->FieldCount = $FieldCount;
		}

		public function loadFromDatabase($TemplateId){
			die("WPTTemplate->loadFromDatabase() is not implemented!");
		}
	}
?>